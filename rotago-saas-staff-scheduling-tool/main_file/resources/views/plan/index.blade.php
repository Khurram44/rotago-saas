@extends('layouts.main')
@php
    $dir= asset(Storage::url('uploads/plan'));
@endphp
@section('page-title')
    {{ __('Plan') }}
@endsection

@section('content')
<div class="page-content">
    <div class="page-title">
    <div class="row justify-content-between align-items-center">
        <div class="col-auto mb-md-0">
            <div class="d-inline-block">
                <h5 class="h4 d-inline-block font-weight-400 mb-0 text-white" >Plan</h5>
            </div>
        </div>
            <div class="col"></div>
            @if( \Auth::user()->type == 'super admin')
            <div class="col-auto">
                <a href="#" data-url="{{ route('plan.create') }}" data-size="lg" data-ajax-popup="true" data-title="Create New Plan" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-4" data-toggle="tooltip" data-original-title="" title="">
                    <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
                </a>
            </div>
            @endif
        </div>
    </div>
    <div class="row">
    @if(!empty($plans))
        @foreach ($plans as $plan)
        <div class="col-md-3">
            <div class="card card-fluid">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">{{$plan->name}}</h6>
                        </div>
                        <div class="text-right">
                            <div class="actions">
                                @if( \Auth::user()->type == 'super admin')
                                    <a title="Edit Plan" data-size="lg" href="#" class="action-item" data-url="{{ route('plan.edit',$plan->id) }}" data-ajax-popup="true" data-title="{{__('Edit Plan')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-edit"></i></a>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body text-center {{!empty(\Auth::user()->type != 'super admin')?'plan-box':''}}">
                    <a href="#" class="avatar rounded-circle avatar-lg hover-translate-y-n3">
                        <img alt="Image placeholder" src="{{$dir.'/'.$plan->image}}" class="">
                    </a>

                    <h5 class="h6 my-4"> {{env('CURRENCY_SYMBOL').$plan->price.' / '.$plan->duration}}</h5>

                    @if(\Auth::user()->type=='company' && \Auth::user()->plan == $plan->id)
                        <h5 class="h6 my-4">
                            {{__('Expired : ')}} {{\Auth::user()->plan_expire_date ? \Auth::user()->dateFormat(\Auth::user()->plan_expire_date):__('Unlimited')}}
                        </h5>
                    @endif

                    <h5 class="h6 my-4">{{$plan->description}}</h5>

                    @if(\Auth::user()->type == 'company' && \Auth::user()->plan == $plan->id)
                        <span class="clearfix"></span>
                        <div class="my-3">
                            <span class="badge badge-pill badge-success">{{__('Active')}}</span>
                        </div>
                    @endif                    
                    @if(($plan->id != \Auth::user()->plan) && \Auth::user()->type!='super admin' )
                        @if($plan->price > 0)
                            <div class="my-3">
                                <a class="badge badge-pill badge-primary" href="{{route('stripe',\Illuminate\Support\Facades\Crypt::encrypt($plan->id))}}" data-toggle="tooltip" data-original-title="{{__('Buy Plan')}}">
                                    <i class="fas fa-cart-plus"></i>
                                </a>
                            </div>
                        @endif
                    @endif                    
                    <span class=" mb-0 text-center">{{__('Employee')}} : {{$plan->max_employee}}</span>                
                </div>            
            </div>
        </div>   
        @endforeach
    @endif    
</div>
</div>
@endsection

