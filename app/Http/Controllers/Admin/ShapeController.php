<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Shape;
use Session;
use Hashids;
use Auth;
use Storage;
use DataTables;

class ShapeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!have_right(77))
            access_denied();
        $data = [];

        if($request->ajax())
        {
            $db_record = Shape::orderBy('created_at','DESC');

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->editColumn('status', function($row)
            {
                $status = '<span class="label label-danger">Disable</span>';
                if ($row->status == 1)
                {
                    $status = '<span class="label label-success">Active</span>';
                }
                return $status;
            });

            $datatable = $datatable->editColumn('image', function($row)
            {
                $image = '<div style="width: 100px; height: auto"><img src="'.checkImage(asset('storage/shapes/' . $row->image),'placeholder.png',$row->image).'" class="img-responsive" alt="" id="image" style="max-width: 50%;"></div>';

                return $image;
            });

            $datatable = $datatable->editColumn('category', function($row)
            {
                $types = config('constants.shape_types');
                return $types[$row->type];
            });

            $datatable = $datatable->addColumn('action', function($row)
            {
                $actions = '<span class="actions">';

                if(have_right(19))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/shapes/" . Hashids::encode($row->id).'/edit').'" title="Edit"><i class="fa fa-pencil"></i></a>';
                }
                if(have_right(91))
                {
                    // $actions .= '&nbsp;<form method="POST" action="'.url("admin/shapes/" . Hashids::encode($row->id)).'" accept-charset="UTF-8" style="display:inline">';
                    // $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    // $actions .= '<input name="_token" type="hidden" value="'.csrf_token().'">';
                    // $actions .= '<button class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this record?\');" title="Delete">';
                    // $actions .= '<i class="fa fa-trash"></i>';
                    // $actions .= '</button>';
                    // $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['image','category','status','action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.shapes.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['model'] = new Shape();
        $data['action'] = "Add";
        return view('admin.shapes.form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['status'] = $request->status;

        $validator = Validator::make($request->all(), [
            'name' => ['required','string','max:100'],
            'image' => 'file|mimes:jpeg,jpg,png,svg|max:'.config('constants.file_size')
        ]);

        if ($validator->fails())
        {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if($input['action'] == 'Add')
        {
            $model = new Shape();
            $flash_message = 'Shape has been created successfully.';
        }
        else
        {
            $model = Shape::findOrFail($input['id']);
            $flash_message = 'Shape has been updated successfully.';
        }

        if (!empty($request->files) && $request->hasFile('image'))
        {
            $file = $request->file('image');

            // *********** //
            // Upload File //
            // *********** //

            $target_path = 'public/shapes';
            $filename = 'shape-'. uniqid() .'.'.$file->getClientOriginalExtension();

            // **************** //
            // Delete Old File
            // **************** //

            if($input['action'] == 'Edit')
            {
                $old_file = public_path() . '/storage/shapes/' . $model->image;
                if (file_exists($old_file) && !empty($model->image))
                {
                    Storage::delete($target_path . '/' . $model->image);
                }
            }

            $path = $file->storeAs($target_path, $filename);
            $input['image'] = $filename;
        }

        $model->fill($input);
        $model->save();

        $request->session()->flash('flash_success', $flash_message);
        return redirect('admin/shapes');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!have_right(19))
            access_denied();

        $id = Hashids::decode($id)[0];
        $data['action'] = "Edit";
        $data['model'] = Shape::findOrFail($id);
        return view('admin.shapes.form')->with($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = Hashids::decode($id)[0];
        Shape::destroy($id);

        Session::flash('flash_success', 'Shapes has been deleted successfully.');
        return redirect('admin/shapes');
    }
}
