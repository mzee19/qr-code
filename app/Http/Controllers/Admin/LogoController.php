<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Logo;
use Session;
use Hashids;
use Auth;
use Storage;
use DataTables;

class LogoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!have_right(78))
            access_denied();
        $data = [];
        if($request->ajax())
        {
            $db_record = Logo::orderBy('created_at','DESC');

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
                $image = '<div style="width: 100px; height: auto"><img src="'.checkImage(asset('storage/logos/' . $row->image),'placeholder.png',$row->image).'" class="img-responsive" alt="" id="image" style="max-width: 50%;"></div>';

                return $image;
            });

            $datatable = $datatable->addColumn('action', function($row)
            {
                $actions = '<span class="actions">';

                if (have_right(22)){
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/logos/" . Hashids::encode($row->id).'/edit').'" title="Edit"><i class="fa fa-pencil"></i></a>';
                    $actions .= '&nbsp;<form method="POST" action="' . url("admin/logos/" . Hashids::encode($row->id)) . '" accept-charset="UTF-8" style="display:inline">';
                }

                if (have_right(23)) {
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this record?\');" title="Delete">';
                    $actions .= '<i class="fa fa-trash"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                    $actions .= '</span>';
                }

                return $actions;
            });

            $datatable = $datatable->rawColumns(['image','status','action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.logos.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!have_right(21))
            access_denied();

        $data['model'] = new Logo();
        $data['action'] = "Add";
        return view('admin.logos.form')->with($data);
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

        $validator = Validator::make($request->all(), [
            'title' => ['required','string','max:100'],
            'image' => 'file|mimes:jpeg,jpg,png|max:'.config('constants.file_size')
        ]);

        if ($validator->fails())
        {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if($input['action'] == 'Add')
        {
            $model = new Logo();
            $flash_message = 'Logo has been created successfully.';
        }
        else
        {
            $model = Logo::findOrFail($input['id']);
            $flash_message = 'Logo has been updated successfully.';
        }

        if (!empty($request->files) && $request->hasFile('image'))
        {
            $file = $request->file('image');

            // *********** //
            // Upload File //
            // *********** //

            $target_path = 'public/logos';
            $filename = 'logo-'. uniqid() .'.'.$file->getClientOriginalExtension();

            // **************** //
            // Delete Old File
            // **************** //

            if($input['action'] == 'Edit')
            {
               $old_file = public_path() . '/storage/logos/' . $model->image;
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
        return redirect('admin/logos');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!have_right(22))
            access_denied();

        $id = Hashids::decode($id)[0];
        $data['action'] = "Edit";
        $data['model'] = Logo::findOrFail($id);
        return view('admin.logos.form')->with($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!have_right(23))
            access_denied();

        $id = Hashids::decode($id)[0];
        Logo::destroy($id);

        Session::flash('flash_success', 'Logo has been deleted successfully.');
        return redirect('admin/logos');
    }
}
