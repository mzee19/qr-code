<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Subscriber;
use Session;
use Hashids;
use Auth;
use DataTables;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!have_right(104))
            access_denied();

        $data = [];

        if($request->ajax())
        {
            $db_record = Subscriber::whereNotNull('id');

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.subscribers.index',$data);
    }
}
