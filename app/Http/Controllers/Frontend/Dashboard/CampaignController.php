<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Exports\CampaignExport;
use App\Exports\ScansExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use http\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Campaign;
use App\Models\GenerateQrCode;
use App\Models\Scan;
use Maatwebsite\Excel\Excel;
use Session;
use Hashids;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->has('limit') ? $request->limit : 15;
        $sort = $request->has('sort') ? $request->sort : 'created_at-desc';
        $text = $request->has('text') ? $request->text : '';

        $sortArr = explode('-', $sort);

        if ($sortArr[0] == 'scans') {
            $db_record = Campaign::whereHas('qrCodes', function ($query) use ($sortArr) {
                $query->select(\DB::raw('SUM(generate_qr_codes.scans) as total_scans'))->orderBy('total_scans', $sortArr[1]);
            })
                ->where('user_id', auth()->user()->id);
        } else {
            $db_record = Campaign::where('user_id', auth()->user()->id)->orderBy($sortArr[0], $sortArr[1]);
        }

        if ($request->has('text') && !empty($request->text)) {
            $db_record = $db_record->where('name', 'like', '%' . $request->text . '%');
        }

        $data['campaigns'] = $db_record->paginate($limit);

        $data['limit'] = $limit;
        $data['sort'] = $sort;
        $data['text'] = $text;
        $data['limits'] = [15, 25, 50, 75, 100];
        return view('frontend.dashboard.campaigns.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['campaign'] = new Campaign();
        $data['action'] = "Add";
        $data['tabTitle'] = __('Add');
        return view('frontend.dashboard.campaigns.form')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'name.required' => __('The name field is required.'),
            'name.max' => __('The name may not be greater than 100 characters.'),
          ];

        $input = $request->all();

        if ($input['action'] == 'Add') {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:100']
            ],$messages);

            $model = new Campaign();
            $flash_message = __('Campaign has been created successfully');
        } else {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:100'],
            ],$messages);

            $model = Campaign::findOrFail($input['id']);
            $flash_message = __('Campaign has been updated successfully');
        }

        if ($validator->fails()) {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        $input['user_id'] = auth()->user()->id;
        $input['status'] = 1;
        $model->fill($input);
        $model->save();


        if ($request->ajax()) {
            return response()->json([
                'status' => 1,
                'message' => __('Campaign has been created successfully'),
                'data' => $model
            ]);
        }
        $request->session()->flash('flash_success', $flash_message);
        return redirect()->route('frontend.user.campaigns.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $id = Hashids::decode($id)[0];
        $data['action'] = "Edit";
        $data['tabTitle'] = __('Edit');
        $data['campaign'] = Campaign::findOrFail($id);

        /*
        ** QR Codes Listing
        */

        $limit = $request->has('limit') ? $request->limit : 15;
        $sort = $request->has('sort') ? $request->sort : 'created_at-desc';
        $text = $request->has('text') ? $request->text : '';

        $sortArr = explode('-', $sort);
        $db_record = GenerateQrCode::where(['campaign_id' => $id, 'template' => 0, 'archive' => 0])->orderBy($sortArr[0], $sortArr[1]);

        if ($request->has('text') && !empty($request->text)) {
            $db_record = $db_record->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->text . '%')
                    ->orWhere('type', 'like', '%' . $request->text . '%');
            });
        }

        $data['qrCodes'] = $db_record->paginate($limit);

        $data['limit'] = $limit;
        $data['sort'] = $sort;
        $data['text'] = $text;
        $data['limits'] = [15, 25, 50, 75, 100];

        /*
        ** Statistics
        */

        $qrCodeIds = GenerateQrCode::where(['campaign_id' => $id, 'template' => 0, 'archive' => 0])->pluck('id')->toArray();

        if ($request->has('from') && $request->has('to')) {
            $from = $request->from . ' 00:00:00';
            $to = $request->to . ' 23:59:59';

            $data['from'] = $request->from;
            $data['to'] = $request->to;
        } else {
            $from = date('Y-m-d', strtotime("-7 days")) . ' 00:00:00';
            $to = date('Y-m-d') . ' 23:59:59';

            $data['from'] = date('Y-m-d', strtotime("-7 days"));
            $data['to'] = date('Y-m-d');
        }

        $checkStatisticsStatus = $this->getPackageStatisticsValueCheck();

        $data['scansList'] = Scan::whereIn('qr_code_id', $qrCodeIds)->whereBetween('created_at', [$from, $to])->orderBy('created_at', 'desc')->get();
        $data['firstScan'] = Scan::whereIn('qr_code_id', $qrCodeIds)->whereBetween('created_at', [$from, $to])->first();

        $data['scanLabels'] = array();
        $data['scanValues'] = json_encode(array());

        $data['uniqueLabels'] = array();
        $data['uniqueValues'] = json_encode(array());

        $data['scansDataPoints'] = [];
        $data['uniqueUsersDataPoints'] = [];

        if (!empty($data['firstScan'])) {
            $data['countryCount'] = Scan::whereIn('qr_code_id', $qrCodeIds)->whereIn('statistics_status',$checkStatisticsStatus)->whereBetween('created_at', [$from, $to])->where('country', $data['firstScan']->country)->count();
            $data['deviceCount'] = Scan::whereIn('qr_code_id', $qrCodeIds)->whereIn('statistics_status',$checkStatisticsStatus)->whereBetween('created_at', [$from, $to])->where('device', $data['firstScan']->device)->count();

            /*
            ** Countries
            ** Cities
            ** Languages
            */

            $data['countries'] = $this->getScansCountGroupBy($qrCodeIds, 'country', $from, $to);
            $data['cities'] = $this->getScansCountGroupBy($qrCodeIds, 'city', $from, $to);
            $data['languages'] = $this->getScansCountGroupBy($qrCodeIds, 'language', $from, $to);

            /*
            ** Devices
            ** Platforms
            ** Browsers
            */

            $data['devices'] = $this->getScansCountGroupBy($qrCodeIds, 'device', $from, $to);
            $data['platforms'] = $this->getScansCountGroupBy($qrCodeIds, 'platform', $from, $to);
            $data['browsers'] = $this->getScansCountGroupBy($qrCodeIds, 'browser', $from, $to);

            $period = new \DatePeriod(
                new \DateTime($from),
                new \DateInterval('P1D'),
                new \DateTime($to)
            );

            /*
            ** Scans Graph
            */

            $scans = Scan::whereIn('qr_code_id', $qrCodeIds)->whereIn('statistics_status',$checkStatisticsStatus)->whereBetween('created_at', [$from, $to])->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as scans'))->groupBy('date')->orderBy('created_at', 'asc')->get()->toArray();

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

                if ($index !== false) {
                    $scansDataPoints[$date] = $scans[$index]['scans'];
                } else {
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

            $unique_users = Scan::whereIn('qr_code_id', $qrCodeIds)->whereIn('statistics_status',$checkStatisticsStatus)->whereBetween('created_at', [$from, $to])->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(DISTINCT ip) as unique_count'))->groupBy('date')->orderBy('created_at', 'asc')->get()->toArray();

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

            /*
            ** Start CanvasJs
            */

            $uniqueUsersDataPoints = array();

            foreach ($period as $key => $value) {
                $date = $value->format('Y-m-d');
                $index = array_search($value->format('Y-m-d'), array_column($unique_users, 'date'));

                if ($index !== false) {
                    $uniqueUsersDataPoints[$date] = $unique_users[$index]['unique_count'];
                } else {
                    $uniqueUsersDataPoints[$date] = 0;
                }
            }

            $data['uniqueUsersDataPoints'] = $uniqueUsersDataPoints;

            /*
            ** End CanvasJs
            */
        }
        if ($request->has('export')) {
            $data['from'] = Carbon::parse($data['from'])->format('m/d/Y');
            $data['to'] = Carbon::parse($data['to'])->format('m/d/Y');
            $data['scansPerDay'] = [];
            foreach ($data['uniqueUsersDataPoints'] as $date => $unique_user) {
                $arr['date'] = Carbon::parse($date)->format('m/d/Y');
                $arr['unique_user'] = $unique_user;
                $arr['scan'] = $data['scansDataPoints'][$date];
                $data['scansPerDay'][] = $arr;
            }

            $data['per_code_scans'] = [];
            foreach ($data['scansList']->groupBy('qr_code_id') as $key => $val) {
                $arr['id'] = $val[0]->qr_code_id;
                $object = $data['qrCodes']->where('id',$val[0]->qr_code_id)->first();
                $arr['name'] = $object ?  $object->name : null;
                $arr['scans_per_day'] = $val->count();
                $arr['unique'] = $data['scansList']->groupBy('ip')->where('qr_code_id',$val[0]->qr_code_id)->count();
                $data['per_code_scans'][] = $arr;
            }
            $from = Carbon::parse($data['from'])->toDate()->format('Ymd');
            $to = Carbon::parse($data['to'])->toDate()->format('Ymd');
            $sheet_name = $from . '-' . $to . '_campaign_statistics_' . time() . '.xlsx';
            return \Maatwebsite\Excel\Facades\Excel::download(new ScansExport($data, '', '', '1'), $sheet_name);
        } else {
            $statistics = 0;
            if ($request->has('from') || $request->has('to')){
                $statistics = 1;
            }
            return view('frontend.dashboard.campaigns.edit',compact('statistics'))->with($data);
        }
    }

    public function getScansCountGroupBy($ids, $field,$fromDate,$toDate)
    {
        $checkStatisticsStatus = $this->getPackageStatisticsValueCheck();

        return Scan::whereIn('qr_code_id', $ids)->whereIn('statistics_status',$checkStatisticsStatus)->whereBetween('created_at', [$fromDate, $toDate])->select($field, \DB::raw('count(*) as scans, created_at'))->groupBy($field)->orderBy('created_at', 'asc')->get();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = Hashids::decode($id)[0];
        GenerateQrCode::where('campaign_id', $id)->update(['archive' => 1, 'campaign_id' => null]);

        Campaign::destroy($id);
        Session::flash('flash_success', __('Campaign has been deleted successfully'));
        return redirect()->route('frontend.user.campaigns.index');
    }

    public function getPackageStatisticsValueCheck(){
        $packageStatisticStatusValue = getSubscriptionFeatureCount(9);

        switch ($packageStatisticStatusValue){
            case 'basic':
                $checkStatisticsStatus = [0];
                break;
            case 'advanced':
                $checkStatisticsStatus = [0,1,2];
                break;
            default:
                $checkStatisticsStatus = [0];
        }

        return $checkStatisticsStatus;
    }

}
