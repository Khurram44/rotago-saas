@extends('layouts.main')
@section('page-title')
    {{__('Order')}}
@endsection
@section('content')
    <!-- Page content -->
    <div class="page-content">
        <!-- Page title -->
        <div class="page-title">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-6 d-flex align-items-center justify-content-between justify-content-md-start mb-3 mb-md-0">
                    <div class="d-inline-block">
                        <h5 class="h4 d-inline-block font-weight-400 mb-0 text-white">{{ __('Order') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">

            <!-- Table -->
            <div class="table-responsive">
                <table class="table align-items-center" id="myTable">
                    <thead>
                    <tr>
                        <th scope="col" class="sort" data-sort="name"> {{__('Order Id')}}</th>
                        <th scope="col" class="sort" data-sort="budget">{{__('Date')}}</th>
                        <th scope="col" class="sort" data-sort="status">{{__('Name')}}</th>
                        <th scope="col">{{__('Plan Name')}}</th>
                        <th scope="col" class="sort" data-sort="completion"> {{__('Price')}}</th>
                        <th scope="col" class="sort" data-sort="completion"> {{__('Payment Type')}}</th>
                        <th scope="col" class="sort" data-sort="completion"> {{__('Status')}}</th>
                        <th scope="col" class="sort" data-sort="completion"> {{__('Coupon')}}</th>
                        <th scope="col" class="sort" data-sort="completion"> {{__('Invoice')}}</th>
    
                    </tr>
                    </thead>
                    <tbody>                        
                    @if(!empty($orders) && count($orders) != 0)
                        @foreach($orders as $order)
                            <tr>
                                <td>{{$order->order_id}}</td>
                                <td>{{$order->created_at->format('d M Y')}}</td>
                                <td>{{$order->user_name}}</td>
                                <td>{{$order->plan_name}}</td>
                                <td>{{env('CURRENCY_SYMBOL').$order->price}}</td>
                                <td>{{$order->payment_type}}</td>
                                <td>
                                    @if($order->payment_status == 'succeeded')
                                        <i class="mdi mdi-circle text-success"></i> {{ucfirst($order->payment_status)}}
                                    @else
                                        <i class="mdi mdi-circle text-danger"></i> {{ucfirst($order->payment_status)}}
                                    @endif
                                </td>
        
                                <td>{{!empty($order->total_coupon_used)? !empty($order->total_coupon_used->coupon_detail)?$order->total_coupon_used->coupon_detail->code:'-':'-'}}</td>
        
                                <td class="text-center">
                                    @if($order->receipt != 'free coupon' && $order->payment_type == 'STRIPE')
                                        <a href="{{$order->receipt}}" title="Invoice" target="_blank" class=""><i class="fas fa-file-invoice"></i> </a>
                                    @elseif($order->receipt =='free coupon')
                                        <p>{{__('Used 100 % discount coupon code.')}}</p>
                                    @elseif($order->payment_type == 'Manually')
                                        <p>{{__('Manually plan upgraded by super admin')}}</p>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">
                                <div class="text-center">
                                    <i class="fas fa-file-alt text-primary fs-40"></i>
                                    <h2>{{ __('Opps...') }}</h2>
                                    <h6> {!! __('No data available.') !!} </h6>
                                </div>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    
    </div>



@endsection

