@extends('frontend.layouts.dashboard')

@section('title', __('Setting'))


@section('content')
    <div class="content-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="section-title text-center">
                    <h2 class="sub-title">Choose Your Plan</h2>
                </div>
            </div>
        </div>
        <div class="tab-section-subscription">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a  class="nav-link active" id="month-tab" >Monthly</a>                 
                </li>
                <li class="nav-item">
                    <a  class="nav-link" id="year-tab">Yearly</a>                  
                </li>
            </ul>
            <div class="edit--compaigns tabs-content subscription-tabs" id="myTabContent">
                <div class="edit--compaigns tabs-content subscription-tabs head" id="myTabContent">
                     <div class="tab-pane fade show active" id="dynamic" role="tabpanel" aria-labelledby="dynamic-tab">
                         <div class="container pricing-table package-sec">
                            <div class="table-responsive mt-3 head">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="col-2-width"></th>
                                            <th class="col-width">
                                                <div class="pricing-col package ">
                                                     <div class="info">
                                                        <h2 class="title">Free</h2>  
                                                        <h4 class="monthly-price">€0 / month</h4>
                                                        <h4 class="yearly-price"></h4>
                                                        <p>
                                                            <small>excl. Vat</small>
                                                        </p>
                                                        <p class="monthly-price">Monthly Payment</p>
                                                        <!-- <p class="yearly-price">Yearly Payment</p> -->
                                                     </div>
                                                     <div class="subscribe">
                                                        <button class="btn btn-primary" type="button">
                                                            Free
                                                        </button>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-width">
                                                <div class=" pricing-col package ">
                                                     <div class="info">
                                                        <h2 class="title">Free</h2>  
                                                        <h4 class="monthly-price">€0 / month</h4>
                                                        <h4 class="yearly-price"></h4>
                                                        <p>
                                                            <small>excl. Vat</small>
                                                        </p>
                                                        <p class="monthly-price">Monthly Payment</p>
                                                        <!-- <p class="yearly-price">Yearly Payment</p> -->
                                                     </div>
                                                     <div class="subscribe">
                                                        <button class="btn btn-primary" type="button">
                                                            Free
                                                        </button>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-width">
                                                <div class="pricing-col package ">
                                                     <div class="info">
                                                        <h2 class="title">Free</h2>  
                                                        <h4 class="monthly-price">€0 / month</h4>
                                                        <h4 class="yearly-price"></h4>
                                                        <p>
                                                            <small>excl. Vat</small>
                                                        </p>
                                                        <p class="monthly-price">Monthly Payment</p>
                                                        <!-- <p class="yearly-price">Yearly Payment</p> -->
                                                     </div>
                                                     <div class="subscribe">
                                                        <button class="btn btn-primary" type="button">
                                                            Free
                                                        </button>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-width">
                                                <div class="pricing-col package ">
                                                     <div class="info">
                                                        <h2 class="title">Free</h2>  
                                                        <h4 class="monthly-price">€0 / month</h4>
                                                        <h4 class="yearly-price"></h4>
                                                        <p>
                                                            <small>excl. Vat</small>
                                                        </p>
                                                        <p class="monthly-price">Monthly Payment</p>
                                                        <!-- <p class="yearly-price">Yearly Payment</p> -->
                                                     </div>
                                                     <div class="subscribe">
                                                        <button class="btn btn-primary" type="button">
                                                            Free
                                                        </button>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-width">
                                                <div class="pricing-col package ">
                                                     <div class="info">
                                                        <h2 class="title">Free</h2>  
                                                        <h4 class="monthly-price">€0 / month</h4>
                                                        <h4 class="yearly-price"></h4>
                                                        <p>
                                                            <small>excl. Vat</small>
                                                        </p>
                                                        <p class="monthly-price">Monthly Payment</p>
                                                        <!-- <p class="yearly-price">Yearly Payment</p> -->
                                                     </div>
                                                     <div class="subscribe">
                                                        <button class="btn btn-primary" type="button">
                                                            Free
                                                        </button>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-width">
                                                <div class="pricing-col package ">
                                                     <div class="info">
                                                        <h2 class="title">Free</h2>  
                                                        <h4 class="monthly-price">€0 / month</h4>
                                                        <h4 class="yearly-price"></h4>
                                                        <p>
                                                            <small>excl. Vat</small>
                                                        </p>
                                                        <p class="monthly-price">Monthly Payment</p>
                                                        <!-- <p class="yearly-price">Yearly Payment</p> -->
                                                     </div>
                                                     <div class="subscribe">
                                                        <button class="btn btn-primary" type="button">
                                                            Free
                                                        </button>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <!-- <div class="feature"> -->
                                        <tbody> 
                                            <tr class="feature">
                                                <td class="col-2-width pricing-col feature-name">
                                                    <h4><strong>Features</strong></h4>  
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                     <h4>Free</h4>
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    <h4>Starter</h4>
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    <h4>Regular</h4>
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                     <h4>title</h4>
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    <h4>title</h4>
                                                <td class="col-width pricing-col feature-info">
                                                    <h4>title</h4>
                                                </td>
                                            </tr>
                                             <tr class="feature">
                                                <td class="col-2-width pricing-col feature-name">
                                                    <h6><strong>Dynamic QR Codes</strong></h6>  
                                                    <p>Content of dynamic QR codes can be edited anytime and track scans.</p>
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                     3
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    100
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    300
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                   500
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    600
                                                <td class="col-width pricing-col feature-info">
                                                    700
                                                </td>
                                            </tr>   
                                            <tr class="feature">
                                                <td class="col-2-width pricing-col feature-name">
                                                    <h6><strong>Dynamic QR Codes</strong></h6>  
                                                    <p>Content of dynamic QR codes can be edited anytime and track scans.</p>
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                     3
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    100
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    300
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                   500
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    600
                                                <td class="col-width pricing-col feature-info">
                                                    700
                                                </td>
                                            </tr> 
                                            <tr class="feature">
                                                <td class="col-2-width pricing-col feature-name">
                                                    <h6><strong>Dynamic QR Codes</strong></h6>  
                                                    <p>Content of dynamic QR codes can be edited anytime and track scans.</p>
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                     3
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    100
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    300
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                   500
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    600
                                                <td class="col-width pricing-col feature-info">
                                                    700
                                                </td>
                                            </tr> 
                                            <tr class="feature">
                                                <td class="col-2-width pricing-col feature-name">
                                                    <h6><strong>Dynamic QR Codes</strong></h6>  
                                                    <p>Content of dynamic QR codes can be edited anytime and track scans.</p>
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                     3
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    100
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    300
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                   500
                                                </td>
                                                <td class="col-width pricing-col feature-info">
                                                    600
                                                <td class="col-width pricing-col feature-info">
                                                    700
                                                </td>
                                            </tr>                          

                                        </tbody>
                                    <!-- </div>    -->
                                </table>
                            </div> 
                        </div>   
                     </div>   
                </div>    
            </div>    
        </div>
    </div> 

@endsection
