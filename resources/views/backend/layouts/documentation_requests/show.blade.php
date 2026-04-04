@extends('backend.app', ['title' => 'Request Details'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Documentation Request Details</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url("admin/dashboard") }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.documentation.request.index') }}">Documentation Requests</a></li>
                        <li class="breadcrumb-item active">View Details</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h3 class="card-title mb-0">Request Details - #{{ $request->id }}</h3>
                            <div class="card-options">
                                <a href="{{ route('admin.documentation.request.index') }}" class="btn btn-sm btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5 class="mb-3 border-bottom pb-2">Client Information</h5>
                                    <p><strong>Full Name:</strong> {{ $request->full_name }}</p>
                                    <p><strong>Email:</strong> <a href="mailto:{{ $request->email }}">{{ $request->email }}</a></p>
                                    <p><strong>Company Name:</strong> {{ $request->company_name }}</p>
                                    <p><strong>Requested At:</strong> {{ $request->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="mb-3 border-bottom pb-2">Request Information</h5>
                                    <p><strong>Document Type:</strong> {{ $request->document_type }}</p>
                                    <p><strong>Status:</strong>
                                        <span class="badge bg-{{ $request->status == 'pending' ? 'warning' : ($request->status == 'reviewed' ? 'info' : 'success') }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3 border-bottom pb-2">Message</h5>
                                    <div class="p-4 bg-light border ">
                                        {{ $request->message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-12">
                                    <form action="{{ route('admin.documentation.request.destroy', $request->id) }}" method="POST" id="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger" onclick="deleteRequest()">
                                            <i class="fe fe-trash"></i> Delete Request
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function deleteRequest() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form').submit();
            }
        });
    }
</script>
@endpush
