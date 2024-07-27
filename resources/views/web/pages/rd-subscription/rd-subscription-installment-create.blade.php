@extends('web.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('web.view.dashboard') }}">User</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('web.view.rdsubscription.list') }}">RD Subscriptions</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('web.view.rdsubscription.preview',['id' => $rd_subscription->id]) }}">RD Subscription Details</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('web.view.rdinstallment.create',['rd_subscription_id' => $rd_subscription->id]) }}">Add Installment</a></li>
        </ul>
        <h1 class="panel-title">Add Installment</h1>
    </div>
@endsection

@section('panel-body')
    <form action="{{ route('web.handle.rdinstallment.create') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" value="{{$rd_subscription->id}}" name="rd_subscription_id" required>
        <figure class="panel-card">
            <div class="panel-card-header">
                <div>
                    <h1 class="panel-card-title">Add Information</h1>
                    <p class="panel-card-description">Please fill the required fields</p>
                </div>
            </div>
            <div class="panel-card-body">
                <div class="grid md:grid-cols-4 sm:grid-cols-1 md:gap-7 sm:gap-5">

                    {{-- Payment Method --}}
                    <div class="input-group">
                        <label for="method" class="input-label">Payment Method <em>*</em></label>
                        <select name="method" id="method" class="input-box-md @error('gender') input-invalid @enderror">
                            <option value="">Select Payment Method</option>
                            @foreach ($payment_methods::cases() as $method)
                            <option value="{{$method->value}}">{{$method->label()}}</option> 
                            @endforeach
                        </select>
                        @error('method')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Payment Date --}}
                    <div class="input-group">
                        <label for="date" class="input-label">Payment Date <em>*</em></label>
                        <input type="date" name="date" value="{{ old('date') }}"
                            class="input-box-md @error('date') input-invalid @enderror" required>
                        @error('date')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Instalment Account --}}
                    <div class="flex flex-col">
                        <label for="amount" class="input-label">Installment Account <span>(In {{config('app.currency.code')}})</span> <em>*</em></label>
                        <input type="number" name="amount" value="{{ old('amount') }}"
                            class="input-box-md @error('amount') input-invalid @enderror"
                            placeholder="Enter Instalment Account" minlength="1" maxlength="250">
                        @error('amount')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Remark --}}
                    <div class="input-group">
                        <label for="remark" class="input-label">Remark <span>(Optional)</span></label>
                        <input type="text" name="remark" value="{{ old('remark') }}"
                            class="input-box-md @error('remark') input-invalid @enderror" placeholder="Enter Remark"
                            minlength="1" maxlength="250">
                        @error('remark')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>  

                 
                </div>
            </div>
            <div class="panel-card-footer">
                <button type="submit" class="btn-primary-md md:w-fit sm:w-full">Add Installment</button>
            </div>
        </figure>
    </form>
@endsection

@section('panel-script')
    <script>
        document.getElementById('rdsubscription-tab').classList.add('active');
    </script>
@endsection