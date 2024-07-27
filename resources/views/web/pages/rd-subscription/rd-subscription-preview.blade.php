@extends('web.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('web.view.dashboard') }}">User</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('web.view.rdsubscription.list') }}">RD Subscriptions</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('web.view.rdsubscription.preview', ['id' => $rd_subscription->id]) }}">RD Subscription
                    Details</a></li>
        </ul>
        <h1 class="panel-title">RD Subscription Details</h1>
    </div>
@endsection

@section('panel-body')
    <figure class="panel-card">
        <div class="panel-card-header">
            <div>
                <h1 class="panel-card-title">Preview Information</h1>
                <p class="panel-card-description">Please check all the information</p>
            </div>
        </div>
        <div class="panel-card-body">
            <div class="grid grid-cols-1 gap-5">


                <div class="space-y-5">
                    <div>
                        <h1 class="title">General Information</h1>
                    </div>
                    <div>
                        <table class="font-medium text-sm">
                            <tr>
                                <td class="pr-7 pb-3 text-gray-400">RD Ref. No.</td>
                                <td class="pr-7 pb-3">{{ $rd_subscription->ref_no }}</td>
                            </tr>
                            <tr>
                                <td class="pr-7 pb-3 text-gray-400">Name</td>
                                <td class="pr-7 pb-3">{{ $rd_subscription->name }}</td>
                            </tr>
                            <tr>
                                <td class="pr-7 pb-3 text-gray-400">Phone</td>
                                <td class="pr-7 pb-3">{{ $rd_subscription->phone }}</td>
                            </tr>
                            <tr>
                                <td class="pr-7 pb-3 text-gray-400">Email Address</td>
                                <td class="pr-7 pb-3">{{ $rd_subscription->email }}</td>
                            </tr>
                            <tr>
                                <td class="pr-7 pb-3 text-gray-400">Start Date</td>
                                <td class="pr-7 pb-3">{{ date('d-m-Y', strtotime($rd_subscription->start_date)) }}</td>
                            </tr>
                            <tr>
                                <td class="pr-7 pb-3 text-gray-400">End Date</td>
                                <td class="pr-7 pb-3">{{ date('d-m-Y', strtotime($rd_subscription->end_date)) }}</td>
                            </tr>
                            <tr>
                                <td class="pr-7 pb-3 text-gray-400">Duration</td>
                                <td class="pr-7 pb-3">{{ $rd_plan->tenure }} Months</td>
                            </tr>
                            <tr>
                                <td class="pr-7 pb-3 text-gray-400">Interest Rate</td>
                                <td class="pr-7 pb-3">{{ $rd_plan->rate_of_interest }}%</td>
                            </tr>
                            <tr>
                                <td class="pr-7 pb-3 text-gray-400">Installment Date</td>
                                <td class="pr-7 pb-3">{{ date('d', strtotime($rd_subscription->payment_date)) }}th Every
                                    Month</td>
                            </tr>
                            <tr>
                                <td class="pr-7 pb-3 text-gray-400">Monthly Installment Amount</td>
                                <td class="pr-7 pb-3">
                                    {{ config('app.currency.symbol') . number_format($rd_subscription->instalment_amount, 2) }}
                                </td>
                            </tr>
                            @php

                                $total_invested = collect($rd_installments)->sum('amount');

                                $return = ($total_invested * $rd_plan->rate_of_interest) / 100;

                                $total_return = $total_invested + $return;

                            @endphp
                            <tr>
                                <td class="pr-7 pb-3 text-gray-400">Total Invested</td>
                                <td class="pr-7 pb-3">
                                    {{ config('app.currency.symbol') . number_format($total_invested, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="pr-7 pb-3 text-gray-400">Total Return</td>
                                <td class="pr-7 pb-3">{{ config('app.currency.symbol') . number_format($total_return, 2) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <div class="panel-card-footer">
            <a href="{{ route('web.handle.rdsubscription.recipt.download', ['id' => $rd_subscription->id]) }}" class="btn-primary-sm w-fit">
                Download Recipt
            </a>
        </div>
    </figure>


    <figure class="panel-card">
        <div class="panel-card-header">
            <div>
                <h1 class="panel-card-title">Payment History</h1>
                <p class="panel-card-description">List of all payments for this RD subscriptions</p>
            </div>
            <div class="flex items-center gap-5">
                <a href="{{ route('web.view.rdinstallment.create', ['rd_subscription_id' => $rd_subscription->id]) }}"
                    class="btn-primary-sm flex">
                    <span class="lg:block md:block sm:hidden mr-2">Add Installment</span>
                </a>
                <div class="btn-primary-sm flex">
                    <form action="{{ route('razorpay.payment.store') }}" method="POST">
                        @csrf
                        <input type="hidden" value="{{$rd_subscription->id}}" name="rd_subscription_id">
                        <input type="hidden" value="{{$rd_subscription->instalment_amount}}" name="amount">
                        <script src="https://checkout.razorpay.com/v1/checkout.js" 
                            data-key="{{ env('RAZORPAY_KEY') }}" 
                            data-amount="{{$rd_subscription->instalment_amount * 100}}"
                            data-buttontext="Make Payment" 
                            data-name="RD Managemtn System" 
                            data-description="Make your installment payment"
                            data-theme.color="#4318FF">
                        </script>
                    </form>
                </div>
            </div>
        </div>
        <div class="panel-card-body">
            <div class="panel-card-table">
                <table class="data-table">
                    <thead>
                        <th>Sr. No.</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Remark</th>
                    </thead>
                    <tbody>
                        @foreach ($rd_installments as $key => $rd_installment)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $rd_installment->method }}</td>
                                <td>{{ $rd_installment->payment_status }}</td>
                                <td>{{ config('app.currency.symbol') . $rd_installment->amount }}</td>
                                <td>{{ date('d M Y', strtotime($rd_installment->date)) }}</td>
                                <td>{{ $rd_installment->remark }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </figure>
@endsection

@section('panel-script')
    <script>
        document.getElementById('rdsubscription-tab').classList.add('active');
    </script>
@endsection
