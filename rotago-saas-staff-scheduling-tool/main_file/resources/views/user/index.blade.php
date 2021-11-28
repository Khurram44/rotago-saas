@extends('layouts.main')

@section('page-title')
    {{ __('Dashbord') }}
@endsection

@section('content')
<div class="page-content">
    <div class="page-title">
<div class="row justify-content-between align-items-center">
<div class="col-auto mb-md-0">
        <div class="d-inline-block">
<h5 class="h4 d-inline-block font-weight-400 mb-0 text-white">User</h5>
</div>
</div>
<div class="col">
    </div>
        <div class="col-auto">
            <a href="#" data-url="{{route('user.create')}}" data-size="md" data-ajax-popup="true" data-title="Create New User" class="btn btn-sm btn-white btn-icon-only rounded-circle ml-4" data-toggle="tooltip" data-original-title="" title="">
                <span class="btn-inner--icon"><i class="fas fa-plus"></i></span>
            </a>
        </div>
    </div>
</div>
<div class="row">

    @if(!empty($users))
        @foreach ($users as $user)        
        <div class="col-lg-3 col-sm-6">
            <div class="card hover-shadow-lg">
                <div class="card-body text-center">
                    <div class="avatar-parent-child">                                                
                        <img alt="" class="avatar  rounded-circle avatar-lg" src="{{ asset(Storage::url("uploads/profile_pic")).'/'}}{{ !empty(Auth::user()->getUserInfo->profile_pic)?Auth::user()->getUserInfo->profile_pic:'avatar.png' }}">
                    </div>
                    <h5 class="h6 mt-4 mb-0"> {{ $user->company_name }}</h5>
                    <div class="d-block text-sm text-muted mb-3"> {{ $user->email }}</div>
                    <div class="actions d-flex justify-content-between px-4 pl-6">
                        <a href="#" class="action-item" data-ajax-popup="true"  data-size="md"
                        data-url="{{route('user.edit', $user->id)}}" data-title="Edit User" data-toggle="tooltip" data-original-title="Edit">
                            <i class="fas fa-user-edit"></i>
                        </a>
                        <a href="#" class="action-item" data-size="lg" data-url="{{ route('plan.upgrade',$user->id) }}" data-ajax-popup="true" data-toggle="tooltip" data-title="{{__('Upgrade Plan')}}">
                            <i class="fas fa-trophy"></i>
                        </a>
                        
                        <a href="#" class="action-item " data-toggle="tooltip" data-original-title="{{__('Delete')}}"
                            data-confirm="{{ __('Are You Sure?') }}|{{ __('This action can not be undone. Do you want to continue?') }}"
                            data-confirm-yes="document.getElementById('delete-form-{{$user->id}}').submit();">
                            <i class="fas fa-trash"></i>
                        </a>
                        {!! Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id],'id' => 'delete-form-'.$user->id]) !!}
                        {!! Form::close() !!}
    
                    </div>
                </div>

                <div class="card-footer">                    
                    <div class="float-left">
                        <div class="actions d-flex justify-content-between">
                            <span class="d-block text-sm text-muted"> {{__('Plan') }}   :  {{!empty($user->currentPlan)?$user->currentPlan->name:__('Free')}}</span>
                        </div>
                        <div class="actions d-flex justify-content-between mt-1">
                            <span class="d-block text-sm text-muted">{{__('Plan Expired') }} : {{!empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date):'Unlimited'}}</span>
                        </div>
                    </div>
                    <div class="float-right">
                        <div class="actions d-flex justify-content-between">
                            <span class="d-block text-sm text-muted">{{ __('Employees') }} : {{$user->countEmployees($user->id)}}</span>
                        </div>
                    </div>
                    <span class="clearfix"></span>
                </div>
            </div>
        </div>
        @endforeach
    @endif    
    </div>
</div>
@endsection