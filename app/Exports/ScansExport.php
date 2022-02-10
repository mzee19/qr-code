<?php

namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;


class ScansExport implements FromView, WithMultipleSheets,WithTitle
{
    public $from;
    public $to;
    public $view;
    public $data;
    public $title;
    public $date_range;
    public $scan_list;
    public $campaign;
    public function __construct($data, $view = '', $title='',$campaign='')
    {
        $this->from = $data['from'];
        $this->to = $data['to'];
        $this->view = $view;
        $this->data = $data;
        $this->title = $title;
        $this->campaign = $campaign;
    }

    public function view(): View
    {
        $data = $this->data;
        return view($this->view,compact('data'));
    }

    public function sheets(): array
    {
        $perday_scans =  new ScansExport($this->data, 'frontend.dashboard.export-statistics.campaign-scans-percode','scans per code');
if ($this->campaign!=null) {
    return [
        'summary' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-summary', 'summary'),
        'perday' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-scans-perday', 'scans per day'),
        $this->campaign ? $perday_scans : '',
        'countries' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-countries', 'countries'),
        'cities' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-cities', 'cities'),
        'languages' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-language', 'languages'),
        'devices' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-device', 'devices'),
        'platforms' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-platforms', 'platforms'),
        'browsers' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-browsers', 'browsers'),
    ];
}else{
    return [
        'summary' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-summary','summary'),
        'perday' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-scans-perday','scans per day'),
        'countries' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-countries','countries'),
        'cities' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-cities','cities'),
        'languages' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-language','languages'),
        'devices' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-device','devices'),
        'platforms' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-platforms','platforms'),
        'browsers' => new ScansExport($this->data, 'frontend.dashboard.export-statistics.qrcode-browsers','browsers'),
    ];
}
    }

    public function title(): string
    {
        return $this->title;
    }
}
