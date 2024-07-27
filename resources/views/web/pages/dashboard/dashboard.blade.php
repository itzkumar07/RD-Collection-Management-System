@extends('web.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('web.view.dashboard') }}">User</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('web.view.dashboard') }}">Dashboard</a></li>
        </ul>
        <h1 class="panel-title">Dashboard</h1>
    </div>
@endsection

@section('panel-body')
    <div class="grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-7">

        <div class="lg:col-span-3 md:col-span-2 sm:col-span-1 ">
            <figure class="panel-card bg-center"  style="background-image: linear-gradient(rgba(0,0,0,0.1),rgba(0,0,0,0.1)),url('/admin/images/auth-bg.png')">
                <div class="lg:p-10 md:p-10 sm:p-7 space-y-4">
                    
                    <div class="space-y-2">
                        <h1 class="font-semibold text-3xl text-white">
                            @php
                                if (date('H:i') >= '06:00' && date('H:i') < '12:00') {
                                    echo 'Good morning';
                                } elseif (date('H:i') >= '12:00' && date('H:i') < '18:00') {
                                    echo 'Good afternoon';
                                } else {
                                    echo 'Good evening';
                                }
                            @endphp, {{auth()->user()->name}}</h1>
                        <p class="text-sm text-gray-200">Welcome to your {{config('app.name')}} User Dashboard</p>
                    </div>

                </div>
            </figure>  
        </div>

        <div class="lg:col-span-3 md:col-span-3 sm:col-span-1">
            <figure class="panel-card">
                <div class="panel-card-header">
                    <div>
                        <h1 class="panel-card-title">Your Account Details</h1>
                        <p class="panel-card-description">All schedule slots for table</p>
                    </div>
                </div>
                <div class="panel-card-body">
                    <div>
                        <table class="font-medium text-sm">
                            <tr>
                                <td class="pr-5 text-gray-500 pb-3">Account Holder Name</td>
                                <td class="pb-3">{{auth()->user()->name}}</td>
                            </tr>
                            <tr>
                                <td class="pr-5 text-gray-500 pb-3">Account Number</td>
                                <td class="pb-3">{{auth()->user()->account_no}}</td>
                            </tr>
                            <tr>
                                <td class="pr-5 text-gray-500 pb-3">Email Address</td>
                                <td class="pb-3">{{auth()->user()->email}}</td>
                            </tr>
                            <tr>
                                <td class="pr-5 text-gray-500 pb-3">Phone</td>
                                <td class="pb-3">{{auth()->user()->phone}}</td>
                            </tr>
                            <tr>
                                <td class="pr-5 text-gray-500 pb-3">Date of Birth</td>
                                <td class="pb-3">{{date('d M Y', strtotime(auth()->user()->date_of_birth))}}</td>
                            </tr>
                            <tr>
                                <td class="pr-5 text-gray-500 pb-3">Aadhaar Card No.</td>
                                <td class="pb-3">{{auth()->user()->aadhaar_card_no}}</td>
                            </tr>
                            <tr>
                                <td class="pr-5 text-gray-500 pb-3">PAN</td>
                                <td class="pb-3">{{auth()->user()->pan_card_no}}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </figure>
        </div>
        


    </div>
@endsection

@section('panel-script')
    <script>
        document.getElementById('dashboard-tab').classList.add('active');
    </script>
@endsection
