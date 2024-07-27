@extends('web.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('web.view.dashboard') }}">Admin</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('web.view.rdsubscription.list') }}">RD Subscriptions</a></li>
        </ul>
        <h1 class="panel-title">RD Subscriptions</h1>
    </div>
@endsection

@section('panel-body')
    <figure class="panel-card">
        <div class="panel-card-header">
            <div>
                <h1 class="panel-card-title">RD Subscriptions</h1>
                <p class="panel-card-description">List of all your RD Subscriptions in the system</p>
            </div>
            <div>
                <a href="{{ route('web.view.rdsubscription.create') }}" class="btn-primary-sm flex">
                    <span class="lg:block md:block sm:hidden mr-2">Start New RD</span>
                    <i data-feather="plus"></i>
                </a>
            </div>
        </div>
        <div class="panel-card-body">
            <div class="panel-card-table">
                <table class="data-table">
                    <thead>
                        <th>Sr. No.</th>
                        <th>Ref. No.</th>
                        <th>Name</th>
                        <th>Installment</th>
                        <th>Duration</th>
                        <th>Invested</th>
                        <th>Return</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($rd_subscriptions as $key => $rd_subscription)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $rd_subscription->ref_no }}</td>
                                <td>{{ $rd_subscription->name }}</td>
                                <td>{{ config('app.currency.symbol') . $rd_subscription->instalment_amount }}</td>
                                <td>{{ $rd_subscription->rd_plan->tenure }} Months</td>
                                @php
                                
                                    $total_invested = collect($rd_subscription->rd_installments)->sum('amount');

                                    $return = ($total_invested * $rd_subscription->rd_plan->rate_of_interest) / 100;

                                    $total_return = $total_invested + $return;

                                @endphp
                                <td>{{ config('app.currency.symbol') . number_format($total_invested, 2) }}</td>
                                <td>{{ config('app.currency.symbol') . number_format($total_return, 2) }}</td>
                                <td>
                                    <div class="table-dropdown">
                                        <button>Options<i data-feather="chevron-down"
                                                class="ml-1 toggler-icon"></i></button>
                                        <div class="dropdown-menu">
                                            <ul>
                                                <li><a href="{{ route('web.view.rdsubscription.preview', ['id' => $rd_subscription->id]) }}"
                                                        class="dropdown-link-primary"><i data-feather="external-link"
                                                            class="mr-1"></i> Preview Details</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
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
