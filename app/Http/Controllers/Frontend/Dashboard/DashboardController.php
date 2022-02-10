<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use App\Models\GenerateQrCode;
use App\Models\Scan;

class DashboardController extends Controller
{
    public function index()
    {
        $data['qrCodes'] = GenerateQrCode::where(['user_id' => auth()->user()->id, 'template' => 0, 'archive' => 0])->orderBy('created_at', 'desc')->limit(6)->get();
        $data['scansList'] = Scan::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->limit(10)->get();

        $data['labels'] = array();
        $data['values'] = json_encode(array());

        $data['dataPoints'] = [];

        if (!empty($data['scansList'])) {
            $period = new \DatePeriod(
                new \DateTime(date('Y-m-d', strtotime("-1 months"))),
                new \DateInterval('P1D'),
                new \DateTime(date('Y-m-d', strtotime("+1 days")))
            );
            $packageStatisticStatusValue = getSubscriptionFeatureCount(9);

            switch ($packageStatisticStatusValue){
                case 'basic':
                    $checkStatisticsStatus = [0,2];
                    break;
                case 'advanced':
                    $checkStatisticsStatus = [0,1,2];
                    break;
                default:
                    $checkStatisticsStatus = [0];
            }

            $scans = Scan::where('user_id', auth()->user()->id)->whereIn('statistics_status',$checkStatisticsStatus)->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as scans'))->groupBy('date')->orderBy('created_at', 'asc')->get()->toArray();

            /*
            ** Start ChartJs
            */

            $labels = array();
            $values = array();

            foreach ($period as $key => $value) {
                $labels[] = $value->format('M d, Y');

                $index = array_search($value->format('Y-m-d'), array_column($scans, 'date'));

                if ($index !== false) {
                    $values[] = $scans[$index]['scans'];
                } else {
                    $values[] = 0;
                }
            }

            $data['labels'] = $labels;
            $data['values'] = json_encode($values);

            /*
            ** End ChartJs
            */

            /*
            ** Start CanvasJs
            */

            $dataPoints = array();

            foreach ($period as $key => $value) {
                $date = $value->format('Y-m-d');
                $index = array_search($value->format('Y-m-d'), array_column($scans, 'date'));

                if ($index !== false) {
                    $dataPoints[$date] = $scans[$index]['scans'];
                } else {
                    $dataPoints[$date] = 0;
                }
            }

            $data['dataPoints'] = $dataPoints;

            /*
            ** End CanvasJs
            */
        }

        return view('frontend.dashboard.index', $data);
    }

    public function support()
    {
        $data['faqs'] = Faq::where('status', 1)->orderBy('order_by', 'ASC')->get();

        return view('frontend.dashboard.support', $data);
    }

    public function updatedUserPackageDetail()
    {
        if (auth()->check()) {
            $user['package'] = auth()->user()->package->title;
            $user['dynamic_qr_codes'] = '0/'.(!empty(getSubscriptionFeatureCount(1)) ? getSubscriptionFeatureCount(1) : '0');

            if (auth()->user()->on_trial == 1) {
                $date = \Carbon\Carbon::parse(auth()->user()->subscription->end_date)->diffInDays(\Carbon\Carbon::now()) . __('Day');
                $user['planExpiry'] = isset(auth()->user()->subscription) ? $date : '0 ' . __('Day') . __('Left');
            } elseif (auth()->user()->package_id == 2) {
                $user['planExpiry'] = __('Lifetime');
            } else {
                $user['planExpiry'] = \Carbon\Carbon::createFromTimeStamp(auth()->user()->subscription->end_date, "UTC")->format('d M, Y');
            }

            return $user;
        }
    }
}
