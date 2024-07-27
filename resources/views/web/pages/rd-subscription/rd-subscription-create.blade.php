@extends('web.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('web.view.dashboard') }}">Admin</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('web.view.rdsubscription.list') }}">RD Subscription</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('web.view.rdsubscription.create') }}">Start RD Subscription</a></li>
        </ul>
        <h1 class="panel-title">Start RD Subscription</h1>
    </div>
@endsection

@section('panel-body')
    <form action="{{ route('web.handle.rdsubscription.create') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <figure class="panel-card">
            <div class="panel-card-header">
                <div>
                    <h1 class="panel-card-title">Add Information</h1>
                    <p class="panel-card-description">Please fill the required fields</p>
                </div>
            </div>
            <div class="panel-card-body">
                <div class="grid md:grid-cols-4 sm:grid-cols-1 md:gap-7 sm:gap-5">

                    {{-- Name --}}
                    <div class="input-group">
                        <label for="name" class="input-label">Name <em>*</em></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="input-box-md @error('name') input-invalid @enderror" placeholder="Enter Name" required
                            minlength="1" maxlength="250">
                        @error('name')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>                    
        
                    {{-- Email --}}
                    <div class="input-group">
                        <label for="email" class="input-label">Email Address <em>*</em></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input-box-md @error('email') input-invalid @enderror" placeholder="Enter Email Address" required minlength="1" maxlength="250">
                        @error('email')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
        
                    {{-- Phone --}}
                    <div class="input-group">
                        <label for="phone" class="input-label">Phone <em>*</em></label>
                        <input type="tel" pattern="[0-9]{10}" name="phone" value="{{ old('phone') }}" class="input-box-md @error('phone') input-invalid @enderror" placeholder="Enter Phone (10 Digits)" required minlength="10" maxlength="10">
                        @error('phone')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                     {{-- RD Plan --}}
                     <div class="flex flex-col">
                        <label for="rd_plan_id" class="input-label">RD Plan <em>*</em></label>
                        <select name="rd_plan_id" class="input-box-md @error('rd_plan_id') input-invalid @enderror" required>
                            <option value="">Select RD Plan</option>
                            @foreach ($rd_plans as $item)
                                <option @selected(old('rd_plan_id', request('rd_plan_id')) == $item->id) value="{{ $item->id }}">{{$item->name}}</option>
                            @endforeach
                        </select>
                        @error('rd_plan_id')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>
        
                    {{-- Start Date --}}
                    <div class="input-group">
                        <label for="start_date" class="input-label">Start Date <em>*</em></label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                            class="input-box-md @error('start_date') input-invalid @enderror" required>
                        @error('start_date')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Payment Date --}}
                    <div class="input-group">
                        <label for="payment_date" class="input-label">Payment Date <em>*</em></label>
                        <input type="date" name="payment_date" value="{{ old('payment_date') }}"
                            class="input-box-md @error('payment_date') input-invalid @enderror" required>
                        @error('payment_date')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Instalment Account --}}
                    <div class="flex flex-col">
                        <label for="instalment_amount" class="input-label">Instalment Account <span>(In {{config('app.currency.code')}})</span> <em>*</em></label>
                        <input type="number" name="instalment_amount" value="{{ old('instalment_amount') }}"
                            class="input-box-md @error('instalment_amount') input-invalid @enderror"
                            placeholder="Enter Instalment Account" minlength="1" maxlength="250">
                        @error('instalment_amount')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                 
                </div>
            </div>
            <div class="panel-card-footer">
                <button type="submit" class="btn-primary-md md:w-fit sm:w-full">Add RD Subscription</button>
            </div>
        </figure>
    </form>
@endsection

@section('panel-script')
    <script>
        document.getElementById('rdsubscription-tab').classList.add('active');
    </script>
@endsection