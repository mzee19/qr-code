<?php

namespace App\Http\Controllers\Frontend\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GenerateQrCode;
use Hashids;
use Session;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
    	  $limit = $request->has('limit') ? $request->limit : 15;
        $sort = $request->has('sort') ? $request->sort : 'created_at-desc';
        $text = $request->has('text') ? $request->text : '';

        $sortArr = explode('-', $sort);
        $db_record = GenerateQrCode::where(['user_id' => auth()->user()->id, 'archive' => 1])->orderBy($sortArr[0],$sortArr[1]);

        if($request->has('text') && !empty($request->text))
        {
            $db_record = $db_record->where(function ($query) use ($request) {
                $query->where('name','like', '%'.$request->text.'%')
                      ->orWhere('type','like', '%'.$request->text.'%');
            });
        }

        $data['qrCodes'] = $db_record->paginate($limit);

        $data['limit'] = $limit;
        $data['sort'] = $sort;
        $data['text'] = $text;
        $data['limits'] = [15,25,50,75,100];

        return view('frontend.dashboard.archive.index', $data);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $id = Hashids::decode($id)[0];
        GenerateQrCode::where('id',$id)->update(['archive' => 0]);

        Session::flash('flash_success', __('QR Code has been restored successfully'));
        return redirect()->back();
    }

    public function restoreAll(){
        $qrCodes = GenerateQrCode::where(['user_id' => auth()->user()->id, 'archive' => 1])->get();
        foreach ($qrCodes as $qrCode) {
            $qrCode->archive = 0;
            $qrCode->save();
        }

        Session::flash('flash_success',  __('QR Code has been restored successfully'));
        return redirect()->back();
    }

    /**
     * Clear all the resources from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function clean()
    {
        $qrCodes = GenerateQrCode::where(['user_id' => auth()->user()->id, 'archive' => 1])->get();

        foreach ($qrCodes as $qrCode) {
            $qrCode->archive = 0;
            $qrCode->save();

            deleteQrCodeOnNedLink($qrCode->id);
            GenerateQrCode::destroy($qrCode->id);
        }

        Session::flash('flash_success', __('QR Code archive has been clean successfully'));
        return redirect()->back();
    }

}
