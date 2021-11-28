@extends('layouts.main')
@php
$dir = asset(Storage::url('uploads/plan'));
$dir_payment = asset(Storage::url('uploads/payments'));
@endphp

@section('page-title')
    {{ __('Order Summary') }}
@endsection
@section('content')
    <div class="page-content">
        <div class="page-title">
            <div class="row justify-content-between align-items-center">
                <div class="col-auto mb-md-0">
                    <div class="d-inline-block">
                        <h5 class="h4 d-inline-block font-weight-400 mb-0 text-white">{{ __('Order Summary') }}</h5>
                    </div>
                </div>
                <div class="col"></div>

            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="card ">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $plan->name }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center plan-box">
                        <a href="#" class="avatar rounded-circle avatar-lg hover-translate-y-n3">
                            <img alt="Image placeholder" src="{{ $dir . '/' . $plan->image }}" class="">
                        </a>

                        <h5 class="h6 my-4 "><span class="final-price">{{ env('CURRENCY_SYMBOL') . $plan->price }}</span>
                            {{ ' / ' . $plan->duration }}</h5>

                        @if (\Auth::user()->type == 'company' && \Auth::user()->plan == $plan->id)
                            <h5 class="h6 my-4">
                                {{ __('Expired : ') }}
                                {{ \Auth::user()->plan_expire_date ? \Auth::user()->dateFormat(\Auth::user()->plan_expire_date) : __('Unlimited') }}
                            </h5>
                        @endif
                        <h5 class="h6 my-3">{{ $plan->description }}</h5>
                        <div class="col-12 text-center">{{ __('Employee') }} : {{ $plan->max_employee }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="row">
                    <div class="col-lg-4 order-lg-2">
                        <div class="card plan-stripe-box">
                            <div class="list-group list-group-flush" id="tabs">
                                @if ($admin_payment_setting['is_stripe_enabled'] == 'on' && !empty($admin_payment_setting['stripe_key']) && !empty($admin_payment_setting['stripe_secret']))
                                    <div data-href="#stripe-payment"
                                        class="custom-list-group-item list-group-item text-primary">
                                        <div class="media">
                                            <i class="fas fa-cog pt-1"></i>
                                            <div class="media-body ml-3">
                                                <a href="#" class="stretched-link h6 mb-1">{{ __('Stripe') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($admin_payment_setting['is_paypal_enabled'] == 'on' && !empty($admin_payment_setting['paypal_client_id']) && !empty($admin_payment_setting['paypal_secret_key']))
                                    <div data-href="#paypal-payment" class="custom-list-group-item list-group-item">
                                        <div class="media">
                                            <i class="fas fa-cog pt-1"></i>
                                            <div class="media-body ml-3">
                                                <a href="#" class="stretched-link h6 mb-1">{{ __('Paypal') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($admin_payment_setting['is_paystack_enabled'] == 'on' && !empty($admin_payment_setting['paystack_public_key']) && !empty($admin_payment_setting['paystack_secret_key']))
                                    <div data-href="#paystack_payment" class="custom-list-group-item list-group-item">
                                        <div class="media">
                                            <i class="fas fa-cog pt-1"></i>
                                            <div class="media-body ml-3">
                                                <a href="#" class="stretched-link h6 mb-1">{{ __('Paystack') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on')
                                <div data-href="#flutterwave_payment" class="custom-list-group-item list-group-item">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{ __('Flutterwave') }}</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on')
                                <div data-href="#razorpay_payment" class="custom-list-group-item list-group-item">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{ __('Razorpay') }}</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on')
                                <div data-href="#mercado_pago_payment" class="custom-list-group-item list-group-item">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{ __('Mercado Pago') }}</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on')
                                <div data-href="#paytm_payment" class="custom-list-group-item list-group-item">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{ __('Paytm') }}</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on')
                                <div data-href="#mollie_payment" class="custom-list-group-item list-group-item">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{ __('Mollie') }}</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on')
                                <div data-href="#skrill_payment" class="custom-list-group-item list-group-item">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{ __('Skrill') }}</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if(isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on')
                                <div data-href="#coingate_payment" class="custom-list-group-item list-group-item">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{ __('Coingate') }}</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 order-lg-1">
                        @if ($admin_payment_setting['is_stripe_enabled'] == 'on' && !empty($admin_payment_setting['stripe_key']) && !empty($admin_payment_setting['stripe_secret']))
                            <div id="stripe-payment" class="tabs-card">
                                <div class="card">
                                    <form role="form" action="{{ route('stripe.post') }}" method="post"
                                        class="require-validation" id="payment-form">
                                        @csrf
                                        <div class="border p-3 mb-3 rounded stripe-payment-div">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="custom-radio">
                                                        <label
                                                            class="font-16 font-weight-bold">{{ __('Credit / Debit Card') }}</label>
                                                    </div>
                                                    <p class="mb-0 pt-1 text-sm">
                                                        {{ __('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.') }}
                                                    </p>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="card-name-on">{{ __('Name on card') }}</label>
                                                        <input type="text" name="name" id="card-name-on"
                                                            class="form-control required"
                                                            placeholder="{{ \Auth::user()->name }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div id="card-element"></div>
                                                    <div id="card-errors" role="alert"></div>
                                                </div>
                                                <div class="col-md-10">
                                                    <br>
                                                    <div class="form-group">
                                                        <label for="stripe_coupon">{{ __('Coupon') }}</label>
                                                        <input type="text" id="stripe_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 coupon-apply-btn">
                                                    <div class="form-group apply-stripe-btn-coupon">
                                                        <a href="#"
                                                            class="btn btn-primary coupon-apply-btn apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="text-sm-right mr-2">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary btn-sm" type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i>
                                                            {{ __('Pay Now') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if ($admin_payment_setting['is_paypal_enabled'] == 'on' && !empty($admin_payment_setting['paypal_client_id']) && !empty($admin_payment_setting['paypal_secret_key']))
                            <div id="paypal-payment" class="tabs-card d-none">
                                <div class="card ">
                                    <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form"
                                        action="{{ route('plan.pay.with.paypal') }}">
                                        @csrf
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label for="paypal_coupon">{{ __('Coupon') }}</label>
                                                        <input type="text" id="paypal_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 coupon-apply-btn">
                                                    <div class="form-group apply-paypal-btn-coupon">
                                                        <a href="#"
                                                            class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3 mr-3">
                                            <div class="text-sm-right">
                                                <button class="btn btn-primary btn-sm" type="submit">
                                                    <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if ($admin_payment_setting['is_paystack_enabled'] == 'on' && !empty($admin_payment_setting['paystack_public_key']) && !empty($admin_payment_setting['paystack_secret_key']))
                            <div id="paystack_payment" class="tabs-card d-none">
                                <div class="card ">
                                    <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                        id="paystack-payment-form" action="{{ route('plan.pay.with.paystack') }}">
                                        @csrf
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label for="paypal_coupon">{{ __('Coupon') }}</label>
                                                        <input type="text" id="paypal_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 coupon-apply-btn">
                                                    <div class="form-group apply-paypal-btn-coupon">
                                                        <a href="#"
                                                            class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3 mr-3">
                                            <div class="text-sm-right">
                                                <input type="button" id="pay_with_paystack" value="{{ __('Pay Now') }}"
                                                    class="btn-create badge-blue btn btn-primary btn-sm">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if (isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on')
                            <div id="flutterwave_payment" class="tabs-card d-none">
                                <div class="card ">
                                    <form role="form" action="{{ route('plan.pay.with.flaterwave') }}" method="post"
                                        class="require-validation" id="flaterwave-payment-form">
                                        @csrf
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label for="paypal_coupon">{{ __('Coupon') }}</label>
                                                        <input type="text" id="paypal_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 coupon-apply-btn">
                                                    <div class="form-group apply-paypal-btn-coupon">
                                                        <a href="#"
                                                            class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3 mr-3">
                                            <div class="text-sm-right">
                                                <input type="button" id="pay_with_flaterwave" value="{{ __('Pay Now') }}"
                                                    class="btn-create badge-blue btn btn-primary btn-sm">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if (isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on')
                            <div id="razorpay_payment" class="tabs-card d-none">
                                <div class="card ">
                                    <form role="form" action="{{ route('plan.pay.with.razorpay') }}" method="post"
                                        class="require-validation" id="razorpay-payment-form">
                                        @csrf
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label for="paypal_coupon">{{ __('Coupon') }}</label>
                                                        <input type="text" id="paypal_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 coupon-apply-btn">
                                                    <div class="form-group apply-paypal-btn-coupon">
                                                        <a href="#"
                                                            class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3 mr-3">
                                            <div class="text-sm-right">
                                                <input type="button" id="pay_with_razorpay" value="{{ __('Pay Now') }}"
                                                    class="btn-create badge-blue btn btn-primary btn-sm">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if (isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on')
                            <div id="mercado_pago_payment" class="tabs-card d-none">
                                <div class="card ">
                                    <form role="form" action="{{ route('plan.pay.with.mercado') }}" method="post"
                                        class="require-validation" id="mercado-payment-form">
                                        @csrf
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label for="paypal_coupon">{{ __('Coupon') }}</label>
                                                        <input type="text" id="paypal_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 coupon-apply-btn">
                                                    <div class="form-group apply-paypal-btn-coupon">
                                                        <a href="#"
                                                            class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3 mr-3">
                                            <div class="text-sm-right">
                                                <input type="submit" id="pay_with_mercado" value="{{ __('Pay Now') }}"
                                                    class="btn-create badge-blue btn btn-primary btn-sm">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if (isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on')
                            <div id="paytm_payment" class="tabs-card d-none">
                                <div class="card ">
                                    <form role="form" action="{{ route('plan.pay.with.paytm') }}" method="post"
                                        class="require-validation" id="paytm-payment-form">
                                        @csrf
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label class="form-control-label"
                                                            for="paytm_coupon">{{ __('Coupon') }}</label>
                                                        <input type="text" id="paytm_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 coupon-apply-btn">
                                                    <div class="form-group apply-paypal-btn-coupon">
                                                        <a href="#"
                                                            class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                    </div>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label class="form-control-label"
                                                            for="paytm_m_no">{{ __('Mobile Number') }}</label>
                                                        <input type="text" id="mobile" name="mobile"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Mobile Number') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3 mr-3">
                                            <div class="text-sm-right">
                                                <input type="submit" id="pay_with_mercado" value="{{ __('Pay Now') }}"
                                                    class="btn-create badge-blue btn btn-primary btn-sm">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if(isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on')
                            <div id="mollie_payment" class="tabs-card d-none">
                                <div class="card ">
                                    <form role="form" action="{{ route('plan.pay.with.mollie') }}" method="post" class="require-validation" id="mollie-payment-form">
                                        @csrf
                                        <input type="hidden" name="plan_id"
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                        <div class="border p-3 mb-3 rounded payment-box">
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label for="paypal_coupon">{{ __('Coupon') }}</label>
                                                        <input type="text" id="paypal_coupon" name="coupon"
                                                            class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 coupon-apply-btn">
                                                    <div class="form-group apply-paypal-btn-coupon">
                                                        <a href="#"
                                                            class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-3 mr-3">
                                            <div class="text-sm-right">
                                                <input type="submit" id="pay_with_mercado" value="{{ __('Pay Now') }}"
                                                    class="btn-create badge-blue btn btn-primary btn-sm">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if(isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on')
                        <div id="skrill_payment" class="tabs-card d-none">
                            <div class="card ">
                                <form role="form" action="{{ route('plan.pay.with.skrill') }}" method="post" class="require-validation" id="skrill-payment-form">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group apply-paypal-btn-coupon">
                                                    <a href="#"
                                                        class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 mr-3">
                                        <div class="text-sm-right">
                                            <input type="submit" id="pay_with_mercado" value="{{ __('Pay Now') }}"
                                                class="btn-create badge-blue btn btn-primary btn-sm">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                        @if(isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on')
                        <div id="coingate_payment" class="tabs-card d-none">
                            <div class="card ">
                                <form role="form" action="{{ route('plan.pay.with.coingate') }}" method="post" class="require-validation" id="coingate-payment-form">
                                    @csrf
                                    <input type="hidden" name="plan_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group apply-paypal-btn-coupon">
                                                    <a href="#"
                                                        class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 mr-3">
                                        <div class="text-sm-right">
                                            <input type="submit" id="pay_with_mercado" value="{{ __('Pay Now') }}"
                                                class="btn-create badge-blue btn btn-primary btn-sm">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('pagescript')

    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script type="text/javascript">
        @if ($plan->price > 0.0 && $admin_payment_setting['is_stripe_enabled'] == 'on' && !empty($admin_payment_setting['stripe_key']) && !empty($admin_payment_setting['stripe_secret']))
            var stripe = Stripe('{{ $admin_payment_setting['stripe_key'] }}');
            var elements = stripe.elements();
        
            // Custom styling can be passed to options when creating an Element.
            var style = {
            base: {
            // Add your base input styles here. For example:
            fontSize: '14px',
            color: '#32325d',
            },
            };
        
            // Create an instance of the card Element.
            var card = elements.create('card', {style: style});
        
            // Add an instance of the card Element into the `card-element` <div>.
                card.mount('#card-element');
        
                // Create a token or display an error when the form is submitted.
                var form = document.getElementById('payment-form');
                form.addEventListener('submit', function (event) {
                event.preventDefault();
        
                stripe.createToken(card).then(function (result) {
                if (result.error) {
                $("#card-errors").html(result.error.message);
                toastrs('Error', result.error.message, 'error');
                } else {
                // Send the token to your server.
                stripeTokenHandler(result.token);
                }
                });
                });
        
                function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);
        
                // Submit the form
                form.submit();
                }
        
        @endif

        $(document).ready(function() {
            $(document).on('click', '.apply-coupon', function() {

                var ele = $(this);

                var coupon = ele.closest('.row').find('.coupon').val();

                $.ajax({
                    url: '{{ route('apply.coupon') }}',
                    datType: 'json',
                    data: {
                        plan_id: '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}',
                        coupon: coupon
                    },
                    success: function(data) {

                        if (data != '') {
                            $('.final-price').text(data.final_price);
                            $('#stripe_coupon, #paypal_coupon').val(coupon);

                            if (data.is_success) {
                                show_toastr('Success', data.message, 'success');
                            } else {
                                show_toastr('Error', data.message, 'error');
                            }
                        } else {
                            show_toastr('Error', "{{ __('Coupon code required.') }}",
                                'error');
                        }
                    }
                })
            });
        });

        $(document).on("click", "#pay_with_paystack", function() {
            $('#paystack-payment-form').ajaxForm(function(res) {
                if (res.flag == 1) {
                    var paystack_callback = "{{ url('/plan/paystack') }}";
                    var order_id = '{{ time() }}';
                    var coupon_id = res.coupon;
                    var handler = PaystackPop.setup({
                        key: '{{ $admin_payment_setting['paystack_public_key'] }}',
                        email: res.email,
                        amount: res.total_price * 100,
                        currency: res.currency,
                        ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                            1
                        ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                        metadata: {
                            custom_fields: [{
                                display_name: "Email",
                                variable_name: "email",
                                value: res.email,
                            }]
                        },

                        callback: function(response) {
                            console.log(response.reference, order_id);
                            window.location.href = paystack_callback + '/' + response
                                .reference + '/' + '{{ encrypt($plan->id) }}' +
                                '?coupon_id=' +
                                coupon_id
                        },
                        onClose: function() {
                            alert('window closed');
                        }
                    });
                    handler.openIframe();
                } else if (res.flag == 2) {

                } else {
                    show_toastr('Error', data.message, 'msg');
                }

            }).submit();
        });

        //    Flaterwave Payment
        $(document).on("click", "#pay_with_flaterwave", function() {
            $('#flaterwave-payment-form').ajaxForm(function(res) {
                if (res.flag == 1) {
                    var coupon_id = res.coupon;
                    var API_publicKey = '{{ $admin_payment_setting['flutterwave_public_key'] }}';
                    var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                    var flutter_callback = "{{ url('/plan/flaterwave') }}";
                    var x = getpaidSetup({
                        PBFPubKey: API_publicKey,
                        customer_email: '{{ Auth::user()->email }}',
                        amount: res.total_price,
                        currency: '{{ env('CURRENCY') }}',
                        txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) +
                            'fluttpay_online-' + {{ date('Y-m-d') }},
                        meta: [{
                            metaname: "payment_id",
                            metavalue: "id"
                        }],
                        onclose: function() {},
                        callback: function(response) {
                            var txref = response.tx.txRef;
                            if (
                                response.tx.chargeResponseCode == "00" ||
                                response.tx.chargeResponseCode == "0"
                            ) {
                                window.location.href = flutter_callback + '/' + txref + '/' +
                                    '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}?coupon_id=' +
                                    coupon_id;
                            } else {
                                // redirect to a failure page.
                            }
                            x.close(); // use this to close the modal immediately after payment.
                        }
                    });
                } else if (res.flag == 2) {

                } else {
                    show_toastr('Error', data.message, 'msg');
                }

            }).submit();
        });

        // Razorpay Payment
        $(document).on("click", "#pay_with_razorpay", function() {
            $('#razorpay-payment-form').ajaxForm(function(res) {
                if (res.flag == 1) {

                    var razorPay_callback = '{{ url('/plan/razorpay') }}';
                    var totalAmount = res.total_price * 100;
                    var coupon_id = res.coupon;
                    var options = {
                        "key": "{{ $admin_payment_setting['razorpay_public_key'] }}", // your Razorpay Key Id
                        "amount": totalAmount,
                        "name": 'Plan',
                        "currency": '{{ env('CURRENCY') }}',
                        "description": "",
                        "handler": function(response) {
                            window.location.href = razorPay_callback + '/' + response
                                .razorpay_payment_id + '/' +
                                '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}?coupon_id=' +
                                coupon_id;
                        },
                        "theme": {
                            "color": "#528FF0"
                        }
                    };
                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                } else if (res.flag == 2) {

                } else {
                    show_toastr('Error', data.message, 'msg');
                }

            }).submit();
        });

    </script>


    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
        @if ($plan->price > 0.0 && env('ENABLE_STRIPE') == 'on' && !empty(env('STRIPE_KEY')) && !empty(env('STRIPE_SECRET')))
            var stripe = Stripe('{{ env('STRIPE_KEY') }}');
            var elements = stripe.elements();
        
            // Custom styling can be passed to options when creating an Element.
            var style = {
            base: {
            // Add your base input styles here. For example:
            fontSize: '14px',
            color: '#32325d',
            },
            };
        
            // Create an instance of the card Element.
            var card = elements.create('card', {style: style});
        
            // Add an instance of the card Element into the `card-element` <div>.
                card.mount('#card-element');
        
                // Create a token or display an error when the form is submitted.
                var form = document.getElementById('payment-form');
                form.addEventListener('submit', function (event) {
                event.preventDefault();
        
                stripe.createToken(card).then(function (result) {
                if (result.error) {
                $("#card-errors").html(result.error.message);
                toastrs('Error', result.error.message, 'error');
                } else {
                // Send the token to your server.
                stripeTokenHandler(result.token);
                }
                });
                });
        
                function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);
        
                // Submit the form
                form.submit();
                }
        
        @endif

        $(document).ready(function() {
            $(document).on('click', '.apply-coupon', function() {

                var ele = $(this);
                var coupon = ele.closest('.row').find('.coupon').val();
                $.ajax({
                    url: '{{ route('apply.coupon') }}',
                    datType: 'json',
                    data: {
                        plan_id: '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}',
                        coupon: coupon
                    },
                    success: function(data) {
                        $('.final-price').text(data.final_price);
                        $('#stripe_coupon, #paypal_coupon').val(coupon);
                        if (data.is_success) {
                            show_toastr('Success', data.message, 'success');
                        } else {
                            show_toastr('Error', 'Coupon code is required', 'error');
                        }
                    }
                })
            });
        });

    </script>
@endpush
