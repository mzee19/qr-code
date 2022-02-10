<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplateLabel;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use Hashids;
use Auth;
use DataTables;

class EmailTemplateLabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!have_right(88))
            access_denied();

        $data = [];
        $data['email_templates'] = EmailTemplate::where('status',1)->get();

        if($request->ajax())
        {
            $db_record = EmailTemplateLabel::where('status',1);

            if($request->has('email_template_id') && !empty($request->email_template_id))
            {
                $db_record = $db_record->where('email_template_id',$request->email_template_id);
            }

            $datatable = Datatables::of($db_record);
            $datatable = $datatable->addIndexColumn();

            $datatable = $datatable->addColumn('email_template', function($row)
            {
                return $row->emailTemplate->subject;
            });

            $datatable = $datatable->addColumn('action', function($row)
            {
                $actions = '<span class="actions">';

                if(have_right(52))
                {
                    $actions .= '&nbsp;<a class="btn btn-primary" href="'.url("admin/email-template-labels/" . Hashids::encode($row->id).'/edit').'" title="Edit"><i class="fa fa-pencil-square-o"></i></a>';
                }
                if(have_right(53))
                {
                    $actions .= '&nbsp;<form method="POST" action="'.url("admin/email-template-labels/" . Hashids::encode($row->id)).'" accept-charset="UTF-8" style="display:inline">';
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

        return view('admin.email-template-labels.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!have_right(51))
            access_denied();

        $data['model'] = new EmailTemplateLabel();
        $data['action'] = "Add";
        $data['email_templates'] = EmailTemplate::where('status',1)->get();
        return view('admin.email-template-labels.form')->with($data);
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
        $input['status'] = 1;

        $validator = Validator::make($request->all(), [
            'email_template_id' => ['required'],
            'label' => ['required'],
            'value' => ['required'],
        ]);

        if ($validator->fails())
        {
            Session::flash('flash_danger', $validator->messages());
            return redirect()->back()->withInput();
        }

        if($input['action'] == 'Add')
        {
            $values = $input['value'];
            foreach($input['label'] as $key => $label)
            {
                $value = $values[$key];

                if($label != NULL && $value != NULL)
                {

                    $input['label']  = $label;
                    $input['value']  = $value;

                    $model = new EmailTemplateLabel();
                    $model->fill($input);
                    $model->save();
                }
            }

            $flash_message = 'Email Template Labels have been created successfully.';
        }
        else
        {
            $input['label']  = $input['label'][0];
            $input['value']  = $input['value'][0];

            $model = EmailTemplateLabel::findOrFail($input['id']);
            $model->fill($input);
            $model->save();

            $flash_message = 'Email Template Label has been updated successfully.';
        }

        $request->session()->flash('flash_success', $flash_message);
        return redirect('admin/email-template-labels');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        if(!have_right(52))
            access_denied();

        if(!isset(Hashids::decode($id)[0]))
            abort(404);

        $id = Hashids::decode($id)[0];
        $data['action'] = "Edit";
        $data['email_templates'] = EmailTemplate::where('status',1)->get();
        $data['model'] = EmailTemplateLabel::findOrFail($id);
        return view('admin.email-template-labels.form')->with($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!have_right(53))
            access_denied();

        $id = Hashids::decode($id)[0];
        EmailTemplateLabel::destroy($id);
        Session::flash('flash_success', 'Email Template Label has been deleted successfully.');
        return redirect('admin/email-template-labels');
    }
}
