<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Title -->
    <title>Recipt</title>

    <!-- Internal CSS -->
    <style>
        * {
            padding: 0px;
            margin: 0px;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        section {
            border-radius: 5px;
            box-shadow: 0px 0px 7px #c4c6c7;
            background-color: #fff;
            padding: 25px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 0.5px solid #6c757d;
            text-align: left;
            vertical-align: top;
            padding: 10px;
            background-color: #f8f9fa;
            font-size: 14px;
            margin-bottom: 0px;
        }

        table p {
            font-size: 14px;
            margin-bottom: 0px;
        }

        p {
            font-size: 14px;
            margin-bottom: 7px;
        }

        hr {
            background-color: #6c757d;
        }
    </style>


</head>

<body>



    <!-- Main (Start) -->
    <main>

        <!-- Section (Start) -->
        <section class="card shadow-sm">
            <div class="card-body">
                <div style="text-align: left; margin-bottom: 0px; margin-top: 10px;">
                    <h2 style="margin-bottom: 5px;">RD Recipt</h2>
                    {{-- <p style="font-size: 0.8rem; margin-bottom: 5px;"></p> --}}
                </div>
                
                <br>
                <hr>
                <br>

                <div class="container">

                    <table style="table-layout: fixed;">
                        <tr>
                            <th colspan="5">RD Plan Details</th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p style="margin-bottom: 4px;">RD Ref. No.	</p>
                                <p style="margin-bottom: 4px;">Name</p>
                                <p style="margin-bottom: 4px;">Phone</p>
                                <p style="margin-bottom: 4px;">Email Address</p>
                                <p style="margin-bottom: 4px;">Start Date</p>
                                <p style="margin-bottom: 4px;">End Date</p>
                                <p style="margin-bottom: 4px;">Duration</p>
                                <p style="margin-bottom: 4px;">Interest Rate</p>
                                <p style="margin-bottom: 4px;">Installment Date</p>
                                <p style="margin-bottom: 4px;">Monthly Installment Amount</p>
                                <p style="margin-bottom: 4px;">Total Invested</p>
                                <p style="margin-bottom: 4px;">Total Return</p>
                            </td>
                            @php

                                $total_invested = collect($rd_installments)->sum('amount');

                                $return = ($total_invested * $rd_plan->rate_of_interest) / 100;

                                $total_return = $total_invested + $return;

                            @endphp
                            <td colspan="3">
                                <p style="margin-bottom: 4px;">{{ $rd_subscription->ref_no }}</p>
                                <p style="margin-bottom: 4px;">{{ $rd_subscription->name }}</p>
                                <p style="margin-bottom: 4px;">{{ $rd_subscription->phone }}</p>
                                <p style="margin-bottom: 4px;">{{ $rd_subscription->email }}</p>
                                <p style="margin-bottom: 4px;">{{ date('d-m-Y', strtotime($rd_subscription->start_date)) }}</p>
                                <p style="margin-bottom: 4px;">{{ date('d-m-Y', strtotime($rd_subscription->end_date)) }}</p>
                                <p style="margin-bottom: 4px;">{{ $rd_plan->tenure }} Months</p>
                                <p style="margin-bottom: 4px;">{{ $rd_plan->rate_of_interest }}%</p>
                                <p style="margin-bottom: 4px;">{{ date('d', strtotime($rd_subscription->payment_date)) }}th Every
                                    Month</p>
                                <p style="margin-bottom: 4px;">Rs. {{ number_format($rd_subscription->instalment_amount, 2) }}</p>
                                <p style="margin-bottom: 4px;">Rs. {{ number_format($total_invested, 2) }}</p>
                                <p style="margin-bottom: 4px;">Rs. {{ number_format($total_return, 2) }}</p>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="5">RD Installments</th>
                        </tr>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                        @foreach ($rd_installments as $key => $item)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>Rs. {{$item->amount}}</td>
                            <td>{{$item->method}}</td>
                            <td>{{$item->payment_status}}</td>
                            <td>{{date('d-m-y', strtotime($item->date))}}</td>
                        </tr>
                        @endforeach
                    </table>
                    

                </div>

                <br>
                <hr>
                <br>

                <div>
                    
                    
                </div>

            </div>
        </section>
        <!-- Section (End) -->


    </main>
    <!-- Main (End) -->


</body>

</html>