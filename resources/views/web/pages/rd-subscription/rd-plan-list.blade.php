@extends('web.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('web.view.dashboard') }}">User</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('web.view.rdplan.list') }}">RD Plans</a></li>
        </ul>
        <h1 class="panel-title">RD Plans</h1>
    </div>
@endsection

@section('panel-body')
    <div class="grid md:grid-cols-3 sm:grid-cols-1 md:gap-7 sm:gap-5">

        @foreach ($rd_plans as $rd_plan)
            <figure class="panel-card">
                <div class="panel-card-body space-y-1">
                    <h1 class="title">{{ $rd_plan->name }}</h1>
                    <h1 class="description">{{ $rd_plan->summary }}</h1>
                    <h1 class="text-sm font-medium">Duration {{ $rd_plan->tenure }} Months</h1>
                    <h1 class="text-sm font-medium">Rate of Interest {{ $rd_plan->rate_of_interest }}%</h1>
                    <div>
                        <div class="pt-2">
                            <a href="{{ route('web.view.rdsubscription.create', ['rd_plan_id' => $rd_plan->id]) }}"
                                class="btn-primary-sm w-full flex items-center justify-center space-x-2"><span>Continue with this Plan</span><i data-feather="arrow-right"></i></a>
                        </div>
                    </div>
                    
                </div>
            </figure>
        @endforeach

    </div>
@endsection

@section('panel-script')
    <script>
        document.getElementById('rdplan-tab').classList.add('active');
    </script>
@endsection