@extends('admin.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.view.dashboard') }}">Admin</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('admin.view.rdplan.list') }}">RD Plans</a></li>
        </ul>
        <h1 class="panel-title">RD Plans</h1>
    </div>
@endsection

@section('panel-body')
    <figure class="panel-card">
        <div class="panel-card-header">
            <div>
                <h1 class="panel-card-title">RD Plans</h1>
                <p class="panel-card-description">List of all RD Plans in the system</p>
            </div>
            <div>
                <a href="{{ route('admin.view.rdplan.create') }}" class="btn-primary-sm flex">
                    <span class="lg:block md:block sm:hidden mr-2">Add RD Plan</span>
                    <i data-feather="plus"></i>
                </a>
            </div>
        </div>
        <div class="panel-card-body">
            <div class="panel-card-table">
                <table class="data-table">
                    <thead>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Tenure</th>
                        <th>Rate of Interest</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach ($rdplans as $key => $rdplan)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $rdplan->name }}</td>
                                <td>{{ $rdplan->tenure }} Months</td>
                                <td>{{ $rdplan->rate_of_interest }}%</td>
                                <td>
                                    <div class="table-dropdown">
                                        <button>Options<i data-feather="chevron-down"
                                                class="ml-1 toggler-icon"></i></button>
                                        <div class="dropdown-menu">
                                            <ul>
                                                <li><a href="{{ route('admin.view.rdplan.update', ['id' => $rdplan->id]) }}"
                                                        class="dropdown-link-primary"><i data-feather="edit"
                                                            class="mr-1"></i> Edit Rd Plan</a></li>
                                                <li><a href="javascript:handleDelete('{{ $rdplan->id }}');"
                                                        class="dropdown-link-danger"><i data-feather="trash-2"
                                                            class="mr-1"></i> Delete Rd Plan</a></li>
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
        document.getElementById('rdplan-tab').classList.add('active');

            const handleDelete = (id) => {
                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this rdplan access!",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            window.location = `{{ url('admin/rdplan/delete') }}/${id}`;
                        }
                    });
            }
    </script>
@endsection
