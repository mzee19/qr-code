@extends('admin.layouts.app')

@section('title', 'Settings')
@section('sub-title', 'Site Settings')

@section('content')
<div class="main-content">
  <div class="content-heading clearfix">

    <ul class="breadcrumb">
      <li><a href="{{url('admin/dashboard')}}"><i class="fa fa-home"></i> Home</a></li>
      <li>Settings</li>
    </ul>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">Settings</h3>
          </div>
          <div class="panel-body">
            @include('admin.messages')
            <form id="settings-form" class="form-horizontal label-left" action="{{url('admin/settings')}}"
              enctype="multipart/form-data" method="POST">
              {{ csrf_field() }}

              <div class="form-group">
                <label class="col-sm-3 control-label">Site Title</label>
                <div class="col-sm-9">
                  <input type="text" name="site_title" maxlength="200" class="form-control"
                    value="{{isset($settings['site_title']) ? $settings['site_title'] : ''}}" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Office Address</label>
                <div class="col-sm-9">
                  <textarea class="form-control" name="office_address" maxlength="1000" rows="5" style="resize: none"
                    required="">{{isset($settings['office_address']) ? $settings['office_address'] : ''}}</textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Contact Number</label>
                <div class="col-sm-9">
                  <input type="text" name="contact_number" maxlength="50" class="form-control"
                    value="{{isset($settings['contact_number']) ? $settings['contact_number'] : ''}}" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Contact Email</label>
                <div class="col-sm-9">
                  <input type="email" name="contact_email" maxlength="200" class="form-control"
                    value="{{isset($settings['contact_email']) ? $settings['contact_email'] : ''}}" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Company Name</label>
                <div class="col-sm-9">
                  <input type="text" name="company_name" maxlength="50" class="form-control"
                    value="{{isset($settings['company_name']) ? $settings['company_name'] : ''}}" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Company Registration Number</label>
                <div class="col-sm-9">
                  <input type="text" name="company_registration_number" maxlength="50" class="form-control"
                    value="{{isset($settings['company_registration_number']) ? $settings['company_registration_number'] : ''}}"
                    required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Company Website</label>
                <div class="col-sm-9">
                  <input type="url" name="website" maxlength="200" class="form-control"
                    value="{{isset($settings['website']) ? $settings['website'] : ''}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Commercial Register Address</label>
                <div class="col-sm-9">
                  <textarea class="form-control" name="commercial_register_address" maxlength="1000" rows="5"
                    style="resize: none"
                    required="">{{isset($settings['commercial_register_address']) ? $settings['commercial_register_address'] : ''}}</textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">VAT ID</label>
                <div class="col-sm-9">
                  <input type="text" name="vat_id" maxlength="50" class="form-control"
                    value="{{isset($settings['vat_id']) ? $settings['vat_id'] : ''}}" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">TAX ID</label>
                <div class="col-sm-9">
                  <input type="text" name="tax_id" maxlength="50" class="form-control"
                    value="{{isset($settings['tax_id']) ? $settings['tax_id'] : ''}}" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Operating Hours</label>
                <div class="col-sm-9">
                  <textarea class="form-control" name="operating_hours" maxlength="1000" rows="5" style="resize: none"
                    required="">{{isset($settings['operating_hours']) ? $settings['operating_hours'] : ''}}</textarea>
                </div>
              </div>

              <h4 class="heading">Company Address Information</h4>

              <div class="form-group">
                <label class="col-sm-3 control-label">Street</label>
                <div class="col-sm-9">
                  <input type="text" name="company_street" maxlength="50" class="form-control"
                    value="{{isset($settings['company_street']) ? $settings['company_street'] : ''}}" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Zip Code</label>
                <div class="col-sm-9">
                  <input type="text" name="company_zip_code" maxlength="50" class="form-control"
                    value="{{isset($settings['company_zip_code']) ? $settings['company_zip_code'] : ''}}" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">City</label>
                <div class="col-sm-9">
                  <input type="text" name="company_city" maxlength="50" class="form-control"
                    value="{{isset($settings['company_city']) ? $settings['company_city'] : ''}}" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Country</label>
                <div class="col-sm-9">
                  <input type="text" name="company_country" maxlength="50" class="form-control"
                    value="{{isset($settings['company_country']) ? $settings['company_country'] : ''}}" required>
                </div>
              </div>

              <h4 class="heading">Bank Information</h4>

              <div class="form-group">
                <label class="col-sm-3 control-label">Bank Name</label>
                <div class="col-sm-9">
                  <input type="text" name="bank_name" maxlength="50" class="form-control"
                    value="{{isset($settings['bank_name']) ? $settings['bank_name'] : ''}}" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">IBAN</label>
                <div class="col-sm-9">
                  <input type="text" name="iban" maxlength="50" class="form-control"
                    value="{{isset($settings['iban']) ? $settings['iban'] : ''}}" required>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Code</label>
                <div class="col-sm-9">
                  <input type="text" name="code" maxlength="50" class="form-control"
                    value="{{isset($settings['code']) ? $settings['code'] : ''}}" required>
                </div>
              </div>

              <h4 class="heading">Social Media Links</h4>

              <div class="form-group">
                <label class="col-sm-3 control-label">Pinterest</label>
                <div class="col-sm-9">
                  <input type="url" name="pinterest" maxlength="200" class="form-control"
                    value="{{isset($settings['pinterest']) ? $settings['pinterest'] : ''}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Facebook</label>
                <div class="col-sm-9">
                  <input type="url" name="facebook" maxlength="200" class="form-control"
                    value="{{isset($settings['facebook']) ? $settings['facebook'] : ''}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Twitter</label>
                <div class="col-sm-9">
                  <input type="url" name="twitter" maxlength="200" class="form-control"
                    value="{{isset($settings['twitter']) ? $settings['twitter'] : ''}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">LinkedIn</label>
                <div class="col-sm-9">
                  <input type="url" name="linkedin" maxlength="200" class="form-control"
                    value="{{isset($settings['linkedin']) ? $settings['linkedin'] : ''}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Youtube</label>
                <div class="col-sm-9">
                  <input type="url" name="youtube" maxlength="200" class="form-control"
                    value="{{isset($settings['youtube']) ? $settings['youtube'] : ''}}">
                </div>
              </div>

              <h4 class="heading">Trial Settings</h4>

              <div class="form-group">
                <label class="col-sm-3 control-label">Number Of Days</label>
                <div class="col-sm-9">
                  <input type="number" name="number_of_days" min="0" max="100" class="form-control"
                    value="{{isset($settings['number_of_days']) ? $settings['number_of_days'] : ''}}">
                </div>
              </div>

              <h4 class="heading">VAT Settings</h4>

              <div class="form-group">
                <label class="col-sm-3 control-label">VAT (%)</label>
                <div class="col-sm-9">
                  <input type="number" name="vat" min="0" max="100" class="form-control"
                    value="{{isset($settings['vat']) ? $settings['vat'] : ''}}">
                </div>
              </div>

              <h4 class="heading">User Deletion Settings</h4>

              <div class="form-group">
                <label class="col-sm-3 control-label">Delete user after number of days</label>
                <div class="col-sm-9">
                  <input type="number" name="user_deletion_days" min="0" max="100" class="form-control"
                    value="{{isset($settings['user_deletion_days']) ? $settings['user_deletion_days'] : ''}}">
                </div>
              </div>

              <h4 class="heading">Subscription Expiry Follow-ups</h4>

              <div class="form-group">
                <label class="col-sm-3 control-label">First Notification(In Days)</label>
                <div class="col-sm-9">
                  <input type="number" name="subscription_expiry_first_notification" min="1" max="100"
                    class="form-control"
                    value="{{isset($settings['subscription_expiry_first_notification']) ? $settings['subscription_expiry_first_notification'] : ''}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Second Notification(In Days)</label>
                <div class="col-sm-9">
                  <input type="number" name="subscription_expiry_second_notification" min="1" max="100"
                    class="form-control"
                    value="{{isset($settings['subscription_expiry_second_notification']) ? $settings['subscription_expiry_second_notification'] : ''}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Third Notification(In Days)</label>
                <div class="col-sm-9">
                  <input type="number" name="subscription_expiry_third_notification" min="1" max="100"
                    class="form-control"
                    value="{{isset($settings['subscription_expiry_third_notification']) ? $settings['subscription_expiry_third_notification'] : ''}}">
                </div>
              </div>

              <h4 class="heading">Account Inactivity <sub><span style="font-weight:normal">(The recommended days for
                    disabling accounts are
                    30)</span></sub>
              </h4>

              <div class="form-group">
                <label class="col-sm-3 control-label">Account Inactive Time Limit(In Days)</label>
                <div class="col-sm-9">
                  <input type="number" name="account_inactivity_time_limit" min="0" max="200" class="form-control"
                    value="{{isset($settings['account_inactivity_time_limit']) ? $settings['account_inactivity_time_limit'] : ''}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Account Soft Delete Time Limit(In Days)</label>
                <div class="col-sm-9">
                  <input type="number" name="account_soft_delete_time_limit" min="0" max="200" class="form-control"
                    value="{{isset($settings['account_soft_delete_time_limit']) ? $settings['account_soft_delete_time_limit'] : ''}}">
                </div>
              </div>

              <h4 class="heading">Account Inactivity Follow-ups</h4>

              <div class="form-group">
                <label class="col-sm-3 control-label">First Notification(In Days)</label>
                <div class="col-sm-9">
                  <input type="number" name="account_inactivity_first_notification" min="1" max="100"
                    class="form-control"
                    value="{{isset($settings['account_inactivity_first_notification']) ? $settings['account_inactivity_first_notification'] : ''}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Second Notification(In Days)</label>
                <div class="col-sm-9">
                  <input type="number" name="account_inactivity_second_notification" min="1" max="100"
                    class="form-control"
                    value="{{isset($settings['account_inactivity_second_notification']) ? $settings['account_inactivity_second_notification'] : ''}}">
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">Third Notification(In Days)</label>
                <div class="col-sm-9">
                  <input type="number" name="account_inactivity_third_notification" min="1" max="100"
                    class="form-control"
                    value="{{isset($settings['account_inactivity_third_notification']) ? $settings['account_inactivity_third_notification'] : ''}}">
                </div>
              </div>

              <div class="text-right">
                <a href="{{url('admin')}}">
                  <button type="button" class="btn cancel btn-fullrounded">
                    <span>Cancel</span>
                  </button>
                </a>

                <button type="submit" class="btn btn-primary btn-fullrounded">
                  <span>Save</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
  $(function(){
        $('#settings-form').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: true,
            
            highlight: function (e) {
              $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },
            success: function (e) {
              $(e).closest('.form-group').removeClass('has-error');
              $(e).remove();
            },
            errorPlacement: function (error, element) {
              if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if (controls.find(':checkbox,:radio').length > 1)
                        controls.append(error);
                else
                      error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
              } 
              else if (element.is('.select2')) {
                error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
              } 
              else if (element.is('.chosen-select')) {
                error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
              } 
              else
                    error.insertAfter(element);
            },
            invalidHandler: function (form,validator) {
              $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top - scrollTopDifference
            }, 500);
            },
            submitHandler: function (form,validator) {
              if($(validator.errorList).length == 0)
              {
                document.getElementById("page-overlay").style.display = "block";
                return true;
              }
            }
        });
    });

</script>
@endsection