<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\GenerateQrCode;
use App\Models\Logo;
use App\Models\Shape;
use Illuminate\Http\Request;
use DataTables;
use Hashids;
use Session;

class QrCodeTemplatesController extends Controller
{

    public function qrCodeTypeData($data)
    {
        switch ($data['type']) {
            case 'url':
                return $data['qrcodeUrl'];
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!have_right(79))
            access_denied();

        $data = [];
        if (request()->ajax()) {
            $db_record = GenerateQrCode::where('user_id',null)->orderBy('created_at', 'DESC');
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->editColumn('status', function ($row) {
                $status = '<span class="label label-danger">Disable</span>';
                if ($row->status == 1) {
                    $status = '<span class="label label-success">Active</span>';
                }
                return $status;
            });
            $datatable = $datatable->editColumn('image', function($row)
            {
                $image = '<div style="width: 100px; height: auto"><img src="'.checkImage(asset('storage/admin-qr-codes/' . $row->image),'placeholder.png',$row->image).'" class="img-responsive" alt="" id="image" style="max-width: 50%;"></div>';
                return $image;
            });
            $datatable = $datatable->addColumn('action', function ($row) {
                $actions = '<span class="actions">';
                if (have_right(25)){
                    $actions .= '&nbsp;<a class="btn btn-primary" href="' . url("admin/qr-code-templates/" . Hashids::encode($row->id) . '/edit') . '" title="Edit"><i class="fa fa-pencil"></i></a>';
                }

                if (have_right(26)){
                    $actions .= '&nbsp;<form method="POST" action="' . url("admin/qr-code-templates/" . Hashids::encode($row->id)) . '" accept-charset="UTF-8" style="display:inline">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="' . csrf_token() . '">';
                    $actions .= '<button class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this record?\');" title="Delete">';
                    $actions .= '<i class="fa fa-trash"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['status','image', 'action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.qr-code-templates.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!have_right(24))
            access_denied();

        $data['model'] = new GenerateQrCode();
        $data['action'] = "Add";
        $data['unique_id'] = uniqid();
        $data['logos'] = Logo::where('status', 1)->get();
        $data['shapes'] = Shape::where('type', 1)->where('status', 1)->get();
        $data['eyeFrames'] = Shape::where('type', 2)->where('status', 1)->get();
        $data['eyeBallShapes'] = Shape::where('type', 3)->where('status', 1)->get();
        return view('admin.qr-code-templates.form')->with($data);
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
        $inputWithExceptData = $request->except('_token', 'action', 'id','status', 'config', 'logo_image', 'name', 'contentType','image_id');
        $userData = $request->except('_token', 'action', 'id','status', 'config', 'logo_image', 'name', 'contentType','image_id');

        $configData = json_decode($request->config, true);

        if ($input['action'] == 'Add') {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:100'],
            ]);

            $model = new GenerateQrCode();
            $flash_message = 'QR Code has been created successfully.';
        } else {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:100'],
            ]);
            $model = GenerateQrCode::findOrFail($input['id']);
            $flash_message = 'QR Code has been updated successfully.';
        }

//        Save Qr Code Image
        $imageUrl = null;
//        New Image Save
        if ($request->image_id && $input['action'] == 'Add') {
            $target_path = 'admin-qr-codes/' . $request->image_id;
            Storage::disk('public')->move('temp/' . $request->image_id, $target_path);
        } else {
            //        Update Image
            if ($request->image_id) {
                Storage::disk('public')->delete('admin-qr-codes/' . $model->image);
                $target_path = 'admin-qr-codes/' . $request->image_id;
                Storage::disk('public')->move('temp/' . $request->image_id, $target_path);
            }
        }

        $qrCodeData = $this->qrCodeTypeData($userData);

        $data = [
            'name' => $request->name,
            'type' => 'url',
            'status' => $request->status,
            'code_type' => 2,
            'image' => $input['action'] == 'Add' ? $request->image_id : ($request->image_id ? $request->image_id : $model->image),
            'fields' => json_encode($inputWithExceptData),
            'data' => $qrCodeData,
            'config' => json_encode($configData['config']),
            'size' => $configData['size'],
            'file' => $configData['file'],
            'download' => $configData['download'],
        ];

        $model->fill($data);
        $model->save();

        Session::flash('flash_success', $flash_message);
        return response()->json([
            'status' => 1,
            'url' => route('admin.qr-code-templates.index'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!have_right(25))
            access_denied();

        $id = Hashids::decode($id)[0];
        $data['model'] = GenerateQrCode::findOrFail($id);
        $data['logos'] = Logo::where('status', 1)->get();
        $data['shapes'] = Shape::where('type', 1)->where('status', 1)->get();
        $data['eyeFrames'] = Shape::where('type', 2)->where('status', 1)->get();
        $data['eyeBallShapes'] = Shape::where('type', 3)->where('status', 1)->get();
        $data['action'] = "Edit";

        return view('admin.qr-code-templates.form')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!have_right(26))
            access_denied();

        $id = Hashids::decode($id)[0];
        GenerateQrCode::destroy($id);

        Session::flash('flash_success', 'Qr Code has been deleted successfully.');
        return redirect('admin/qr-code-templates');
    }
}
