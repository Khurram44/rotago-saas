@extends('layouts.main')
@section('page-title')
    {{__('Coupon Detail')}}
@endsection
@section('content')
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar border-0">
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h6 class="d-inline-block mb-0">{{$coupon->name}}</h6>
                </div>

            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center">
                <thead>
                <tr>
                    <th scope="col" class="sort" data-sort="name"> {{__('User')}}</th>
                    <th scope="col" class="sort" data-sort="name"> {{__('Date')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($userCoupons as $userCoupon)
                    <tr class="font-style">
                        <td>{{ !empty($userCoupon->userDetail)?$userCoupon->userDetail->name:'' }}</td>
                        <td>{{ $userCoupon->created_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

