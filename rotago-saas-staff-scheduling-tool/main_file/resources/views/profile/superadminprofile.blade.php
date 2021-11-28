@extends('layouts.main')
@section('page-title')
    {{ __('Profile') }}
@endsection
@section('content')
    <!-- Page content -->
    <div class="page-content">
        <!-- Page title -->
        <div class="page-title">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-6 d-flex align-items-center justify-content-between justify-content-md-start mb-3 mb-md-0">
                    <!-- Page title + Go Back button -->
                    <div class="d-inline-block">
                        <h5 class="h4 d-inline-block font-weight-400 mb-0 text-white">{{ __('Account settings') }}</h5>
                    </div>
                </div>                
            </div>
        </div>
        <!-- Listing -->
        <div class="row">
            <div class="col-lg-4 order-lg-2">
                <div class="card">
                    <div class="list-group list-group-flush profile_menu11" id="tabs">                        
                        @if(Auth::user()->type == 'super admin')
                        <div data-href="#tabs-1" class="list-group-item text-primary">
                            <div class="media">
                                <i class="far fa-building"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Company Details') }}</a>
                                    <p class="mb-0 text-sm">{{ __('Details about your Company information') }}</p>
                                </div>
                            </div>
                        </div>
                        <div data-href="#tabs-2" class="list-group-item">
                            <div class="media">
                                <i class="fas fa-user-lock"></i>
                                <div class="media-body ml-3">
                                    <a href="#" class="stretched-link h6 mb-1">{{ __('Password') }}</a>
                                    <p class="mb-0 text-sm">{{ __('Change Password') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif                       
                    </div>
                </div>
            </div>
            <div class="col-lg-8 order-lg-1">
                <div class="card bg-gradient-warning hover-shadow-lg border-0">
                    <div class="card-body py-3">
                        <div class="row row-grid align-items-center">
                            <div class="col-lg-8">
                                <div class="media align-items-center">
                                    <a href="#" class="avatar avatar-lg rounded-circle mr-3">
                                        <img class="avatar avatar-lg" id="blah" src="{{ asset(Storage::url($profile->DefaultProfilePic())) }}">
                                    </a>
                                    <div class="media-body">
                                        <h5 class="text-white mb-0">
                                            {{ $profile->getUserName->first_name }}
                                            {{ $profile->getUserName->last_name }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="tabs-1" class="tabs-card">
                    <div class="card">
                        @if(Auth::user()->acount_type == 1 || Auth::user()->acount_type == 2)
                        <ul class="nav nav-tabs nav-overflow profile-tab-list" role="tablist">
                            <li class="nav-item ml-4">
                                <a href="#personal" id="orders-tab" class="nav-link active" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                                    <i class="fas fa-user mr-2"></i>{{ __('Personal Detail') }}
                                </a>
                            </li>
                            <li class="nav-item ml-4">
                                <a href="#employee" id="plans-tab" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                                    <i class="fas fa-briefcase mr-2"></i>{{ __('Employee Detail') }}
                                </a>
                            </li>
                        </ul>
                        @endif
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="orders-tab">                                
                                <div class="card-body">
                                    {{ Form::model($profile, ['route' => ['profile.update', $profile->id], 'method' => 'PUT', 'class'=>"personal_information", 'enctype' => 'multipart/form-data' ]) }}
                                        <div class="row">
                                            {{ Form::hidden('employee_id', $profile->user_id) }}
                                            {{ Form::hidden('form_type', 'superadmininfo') }}
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Name'), ['class' => 'form-control-label']) }}
                                                    {{ Form::text('company_name', $profile->getUserName->company_name, ['class' => 'form-control'] ) }}
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    {{ Form::label('', __('Email'), ['class' => 'form-control-label']) }}
                                                    {{ Form::text('email', $profile->getUserName->email, ['class' => 'form-control'] ) }}
                                                </div>
                                            </div>                                                
                                        </div>                                            
                                        <hr>
                                        <div class="text-right">
                                            <input name="from" type="hidden" value="password">
                                            <button type="submit" class="btn btn-sm btn-primary rounded-pill">{{__('Save')}}</button>
                                        </div>

                                    {{ Form::close() }}
                                </div>                                
                            </div>                            
                        </div>
                    </div>
                </div>
                
                <div id="tabs-2" class="tabs-card d-none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Change Password') }}</h5>
                        </div>
                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('update.password') }}" role="form">
                                {{ Form::hidden('form_type', 'set_own_password' ) }}                                                    
                                @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('', __('Current Password'), ['class' => 'form-control-label']) }}                                                    
                                                {{ Form::password('current_password', ['class' => 'form-control','placeholder'=>"Enter Current Password", "id"=>"current_password"] ) }}                                                    
                                                
                                            </div>
                                            @error('current_password')
                                            <span class="invalid-current_password" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('', __('New Password'), ['class' => 'form-control-label']) }}                                                    
                                                {{ Form::password('new_password', ['class' => 'form-control','placeholder'=>"Enter New Password", "id"=>"new_password"] ) }}                                                       
                                            </div>
                                            @error('new_password')
                                            <span class="invalid-new_password" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('', __('Re-type New Password'), ['class' => 'form-control-label']) }}                                                    
                                                {{ Form::password('confirm_password', ['class' => 'form-control','placeholder'=>"Enter Re-type New Password", "id"=>"confirm_password"] ) }}                                                   
                                            </div>
                                            @error('confirm_password')
                                            <span class="invalid-confirm_password" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                                                         
                                    
                                    <div class="card-footer text-right">
                                        <input class="btn btn-sm btn-primary rounded-pill" type="submit" value="Update">
                                    </div>
                                    </form>                                

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

