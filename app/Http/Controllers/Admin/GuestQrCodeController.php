<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestQrCode;
use Illuminate\Http\Request;
use DataTables;
use Hashids;
use Session;

class GuestQrCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!have_right(107))
            access_denied();

        $data = [];
        if($request->ajax())
        {
            $db_record = GuestQrCode::latest();
            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
//            $datatable = $datatable->editColumn('status', function($row)
//            {
//                $status = '<span class="label label-danger">Disable</span>';
//                if ($row->status == 1)
//                {
//                    $status = '<span class="label label-success">Active</span>';
//                }
//                return $status;
//            });


            $datatable = $datatable->addColumn('action', function($row)
            {
                $actions = '<span class="actions">';
                if(have_right(108) && (auth()->user()->role_id != $row->id || auth()->user()->id == 1))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/guest-qr-code/" . Hashids::encode($row->id)).'" title="Edit"><i class="fa fa-eye"></i></a>';
                }

                if(have_right(109) && (auth()->user()->role_id != $row->id || auth()->user()->id == 1))
                {
                    $actions .= '&nbsp;<form method="POST" action="'.url("admin/guest-qr-code/" . Hashids::encode($row->id)).'" accept-charset="UTF-8" style="display:inline">';
                    $actions .= '<input type="hidden" name="_method" value="DELETE">';
                    $actions .= '<input name="_token" type="hidden" value="'.csrf_token().'">';
                    $actions .= '<button class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this record?\');" title="Delete">';
                    $actions .= '<i class="fa fa-trash"></i>';
                    $actions .= '</button>';
                    $actions .= '</form>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }
        return view('admin.guest-qr-code.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!have_right(108))
            access_denied();

        if(!isset(Hashids::decode($id)[0]))
            abort(404);

        $id = Hashids::decode($id)[0];
        $data['action'] = 'Show';
        $data['guestQrCode'] = GuestQrCode::findOrFail($id);
        return view('admin.guest-qr-code.form',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        if(!have_right(109))
            access_denied();

        if(!isset(Hashids::decode($id)[0]))
            abort(404);

        $id = Hashids::decode($id)[0];
        $guestQrCode = GuestQrCode::destroy($id);

        Session::flash('flash_success', 'Guest QR COde has been deleted successfully.');
        return redirect('admin/guest-qr-code');
    }
}
