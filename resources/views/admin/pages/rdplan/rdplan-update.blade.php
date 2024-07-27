@extends('admin.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{ route('admin.view.dashboard') }}">Admin</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('admin.view.rdplan.list') }}">RD Plans</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{ route('admin.view.rdplan.update', ['id' => $rdplan->id]) }}">Edit RD Plan</a></li>
        </ul>
        <h1 class="panel-title">Edit RD Plan</h1>
    </div>
@endsection

@section('panel-body')
    <form action="{{ route('admin.handle.rdplan.update', ['id' => $rdplan->id]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <figure class="panel-card">
            <div class="panel-card-header">
                <div>
                    <h1 class="panel-card-title">Edit Information</h1>
                    <p class="panel-card-description">Please fill the required fields</p>
                </div>
                <div>
                    <button type="button" class="btn-danger-sm flex items-center justify-center" onclick="handleDelete()">
                        <span class="lg:block md:block sm:hidden mr-2">Delete</span>
                        <i data-feather="trash"></i>
                    </button>
                </div>
            </div>
            <div class="panel-card-body">
                <div class="grid md:grid-cols-4 sm:grid-cols-1 md:gap-7 sm:gap-5">

                    {{-- Name --}}
                    <div class="flex flex-col">
                        <label for="name" class="input-label">Name <em>*</em></label>
                        <input type="text" name="name" value="{{ old('name', $rdplan->name) }}"
                            class="input-box-md @error('name') input-invalid @enderror" placeholder="Enter Name" required
                            minlength="1" maxlength="250">
                        @error('name')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                     {{-- Tenure --}}
                     <div class="flex flex-col">
                        <label for="tenure" class="input-label">Tenure <span>(In Months)</span> <em>*</em></label>
                        <input type="number" name="tenure" value="{{ old('tenure', $rdplan->tenure) }}"
                            class="input-box-md @error('tenure') input-invalid @enderror" placeholder="Enter Tenure"
                            min="1" max="500">
                        @error('tenure')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Rate of Interest --}}
                    <div class="flex flex-col">
                        <label for="rate_of_interest" class="input-label">Rate of Interest <span>(In %)</span> <em>*</em></label>
                        <input type="number" name="rate_of_interest" value="{{ old('rate_of_interest', $rdplan->rate_of_interest) }}"
                            class="input-box-md @error('rate_of_interest') input-invalid @enderror"
                            placeholder="Enter Rate of Interest" minlength="1" maxlength="250">
                        @error('rate_of_interest')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Summary --}}
                    <div class="input-group 2xl:col-span-5 lg:col-span-4 md:col-span-2">
                        <label for="summary" class="input-label">Summary <em>*</em></label>
                        <input type="text" name="summary" value="{{ old('summary', $rdplan->summary) }}"
                            class="input-box-md @error('summary') input-invalid @enderror" placeholder="Enter Summary"
                            required minlength="1" maxlength="500">
                        @error('summary')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                    
                    {{-- Description --}}
                    <div class="input-group 2xl:col-span-5 lg:col-span-4 md:col-span-2 sm:col-span-1">
                        <label for="description" class="input-label">Description <span>(Optional)</span></label>
                        <div class="space-y-2">
                            <div class="flex space-x-2">
                                <button class="btn-primary-sm" type="button" onclick="format('bold')"><b>B</b></button>
                                <button class="btn-primary-sm" type="button" onclick="format('italic')"><i>I</i></button>
                                <button class="btn-primary-sm" type="button" onclick="format('insertunorderedlist')"><i
                                        data-feather="list" class="h-3 w-3"></i></button>
                            </div>
                            <div onkeyup="handleConvertHTML()"
                                class="input-box-md @error('description') input-invalid @enderror" contenteditable="true"
                                id="html-editor">
                                {!! old('description', $rdplan->description) !!}
                            </div>
                            <input type="text" name="description" id="description" value="{{ old('description', $rdplan->description) }}"
                                hidden>
                        </div>
                        <script>
                            function format(command, value) {
                                document.execCommand(command, false, value);
                            }

                            function handleConvertHTML() {
                                document.getElementById('description').value = document.getElementById('html-editor').innerHTML;
                            }
                        </script>
                        @error('description')
                            <span class="input-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </div>
            <div class="panel-card-footer">
                <button type="submit" class="btn-primary-md md:w-fit sm:w-full">Save Changes</button>
            </div>
        </figure>
    </form>
@endsection

@section('panel-script')
    <script>
        document.getElementById('rdplan-tab').classList.add('active');
        
        const handleDelete = () => {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this product!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location =
                            "{{ route('admin.handle.rdplan.delete', ['id' => $rdplan->id]) }}";
                    }
                });
        }
    </script>
@endsection