<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GenerateQrCode;
use App\Models\Scan;
use Illuminate\Http\Request;
use Hashids;
use DataTables;

class QrCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$id)
    {
        $data['id'] = $id;

        if ($request->ajax())
        {
            $id = Hashids::decode($id)[0];
            $db_record = GenerateQrCode::where(['user_id'=>$id,'template'=>0]);

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->editColumn('code_type', function ($row) {
                $code_type = '<span class="label label-danger">Static</span>';
                if ($row->code_type == 1) {
                    $code_type = '<span class="label label-success">Dynamic</span>';
                }
                return $code_type;
            });
            $datatable = $datatable->editColumn('image', function($row) use($id)
            {
                $image = '<div style="width: 100px; height: auto"><img src="'.checkImage(asset('storage/users/' . $id.'/qr-codes/'.$row->image),'placeholder.png',$row->image).'" class="img-responsive" alt="" id="image" style="max-width: 50%;"></div>';
                return $image;
            });
            $datatable = $datatable->editColumn('type', function($row)
            {
                $type = ucwords($row->type);
                return $type;
            });

            $datatable = $datatable->addColumn('action', function($row)
            {
                $actions = '<span class="actions">';

                if(have_right(105))
                {
                    $actions .= '&nbsp;<a title="View" class="btn btn-primary" href="'.route("admin.users.qr.code.show" , Hashids::encode($row->id)).'"><i class="fa fa-eye"></i></a>';
                }
                if(have_right(106) && $row->code_type == 1)
                {
                    $actions .= '&nbsp;<a title="Statistic" class="btn btn-primary" href="'.route("admin.users.qr.code.statistic" , Hashids::encode($row->id)).'"><i class="fa fa-bar-chart"></i></a>';
                }

                $actions .= '</span>';
                return $actions;
            });

            $datatable = $datatable->rawColumns(['code_type','image','type','action']);
            $datatable = $datatable->make(true);
            return $datatable;
        }

        return view('admin.users.qr-codes.index',$data);
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
        $id = Hashids::decode($id)[0];
        $data['qrCode'] = GenerateQrCode::findOrFail($id);
        $data['action'] = 'View';

        return view('admin.users.qr-codes.view',$data);
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
        //
    }

    public function statistic($id){
        $id = Hashids::decode($id)[0];
        $data['qrCode'] = GenerateQrCode::find($id);
        $data['action'] = 'Statistics';

        $from = date('Y-m-d', strtotime("-1 months")) . ' 00:00:00';
        $to = date('Y-m-d') . ' 23:59:59';

        $period = new \DatePeriod(
            new \DateTime(date('Y-m-d', strtotime("-1 months"))),
            new \DateInterval('P1D'),
            new \DateTime(date('Y-m-d', strtotime("+1 days")))
        );

        $data['scansList'] = Scan::where('qr_code_id', $id)->whereBetween('created_at', [$from, $to])->orderBy('created_at', 'desc')->get();
        $data['firstScan'] = Scan::where('qr_code_id', $id)->whereBetween('created_at', [$from, $to])->first();
        $data['scansDataPoints'] = [];

        $data['scanLabels'] = array();
        $data['scanValues'] = json_encode(array());

        $data['uniqueLabels'] = array();
        $data['uniqueValues'] = json_encode(array());

        if (!empty($data['firstScan'])) {
            $data['countryCount'] = Scan::where('qr_code_id', $id)->whereBetween('created_at', [$from, $to])->where('country', $data['firstScan']->country)->count();
            $data['deviceCount'] = Scan::where('qr_code_id', $id)->whereBetween('created_at', [$from, $to])->where('device', $data['firstScan']->device)->count();

            /*
            ** Countries
            ** Cities
            ** Languages
            */

            $data['countries'] = $this->getScansCountGroupBy($id, 'country');
            $data['cities'] = $this->getScansCountGroupBy($id, 'city');
            $data['languages'] = $this->getScansCountGroupBy($id, 'language');

            /*
            ** Devices
            ** Platforms
            ** Browsers
            */

            $data['devices'] = $this->getScansCountGroupBy($id, 'device');
            $data['platforms'] = $this->getScansCountGroupBy($id, 'platform');
            $data['browsers'] = $this->getScansCountGroupBy($id, 'browser');

            /*
            ** Scans Graph
            */

            $scans = Scan::where('qr_code_id' , $id)->whereBetween('created_at', [$from, $to])->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as scans'))->groupBy('date')->orderBy('created_at','asc')->get()->toArray();

            /*
            ** Start ChartJs
            */

            $scanLabels = array();
            $scanValues = array();

            foreach ($period as $key => $value) {
                $scanLabels[] = $value->format('M d, Y');

                $index = array_search($value->format('Y-m-d'), array_column($scans, 'date'));

                if($index !== false)
                {
                    $scanValues[] = $scans[$index]['scans'];
                }
                else
                {
                    $scanValues[] = 0;
                }
            }

            $data['scanLabels'] = $scanLabels;
            $data['scanValues'] = json_encode($scanValues);

            /*
            ** End ChartJs
            */

            /*
            ** Start CanvasJs
            */

            $scansDataPoints = array();

            foreach ($period as $key => $value) {
                $date = $value->format('Y-m-d');
                $index = array_search($value->format('Y-m-d'), array_column($scans, 'date'));

                if($index !== false)
                {
                    $scansDataPoints[$date] = $scans[$index]['scans'];
                }
                else
                {
                    $scansDataPoints[$date] = 0;
                }
            }

            $data['scansDataPoints'] = $scansDataPoints;

            /*
            ** End CanvasJs
            */

            /*
            ** Unique Users Graph
            */

            $unique_users = Scan::where('qr_code_id', $id)->whereBetween('created_at', [$from, $to])->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(DISTINCT ip) as unique_count'))->groupBy('date')->orderBy('created_at', 'asc')->get()->toArray();

            /*
            ** Start ChartJs
            */

            $uniqueLabels = array();
            $uniqueValues = array();

            foreach ($period as $key => $value) {
                $uniqueLabels[] = $value->format('M d, Y');

                $index = array_search($value->format('Y-m-d'), array_column($unique_users, 'date'));

                if($index !== false)
                {
                    $uniqueValues[] = $unique_users[$index]['unique_count'];
                }
                else
                {
                    $uniqueValues[] = 0;
                }
            }

            $data['uniqueLabels'] = $uniqueLabels;
            $data['uniqueValues'] = json_encode($uniqueValues);

            /*
            ** End ChartJs
            */
        }

        return view('admin.users.qr-codes.statistics', $data);
    }

    public function getScansCountGroupBy($id, $field)
    {
        return Scan::where('qr_code_id', $id)->select($field, \DB::raw('count(*) as scans'))->groupBy($field)->orderBy('created_at', 'asc')->get();
    }
}
