@extends('backend.app', ['title' => 'Show '.$part])

@section('content')

<div class="app-content main-content mt-0">
    <div class="side-app">

        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">{{ $crud ? ucwords(str_replace('_', ' ', $crud)) : 'N/A' }}</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url("admin/dashboard") }}"><i class="fe fe-home me-2 fs-14"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">{{ $part }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Show</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card post-sales-main">
                        <div class="card-header border-bottom">
                            <h3 class="card-title mb-0">Blog Details</h3>
                            <div class="card-options">
                                <a href="javascript:window.history.back()" class="btn btn-sm btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th style="width: 200px;">Feature Image</th>
                                        <td>
                                            @if($blog->image && file_exists(public_path($blog->image)))
                                                <img src="{{ asset($blog->image) }}" alt="Image" style="width: 200px; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                                            @else
                                                <img src="{{ asset('backend/images/default.png') }}" alt="Default" style="width: 200px; height: 120px; object-fit: cover; border-radius: 8px;">
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Blog Type / Language</th>
                                        <td>
                                            @php
                                                $badge = ['english' => 'bg-info', 'de' => 'bg-warning', 'other' => 'bg-secondary'];
                                                $class = $badge[$blog->type] ?? 'bg-primary';
                                            @endphp
                                            <span class="badge {{ $class }} text-dark">{{ ucfirst($blog->type) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Title</th>
                                        <td><strong>{{ $blog->title ?? 'N/A' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th>Subtitle</th>
                                        <td>{{ $blog->subtitle ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge {{ $blog->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($blog->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>
                                            <div class="p-3 border rounded bg-light " style="height: 800px">
                                                {!! $blog->description ?? 'N/A' !!}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Action</th>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="goToEdit(`{{ $blog->id }}`)">
                                                <i class="fe fe-edit"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="showDeleteConfirm(`{{ $blog->id }}`)">
                                                <i class="fe fe-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div></div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // delete Confirm
    function showDeleteConfirm(id) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure you want to delete this record?',
            text: 'If you delete this, it will be gone forever.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                NProgress.start();
                let url = "{{ route($route . '.destroy', ':id') }}";
                let csrfToken = '{{ csrf_token() }}';
                $.ajax({
                    type: "DELETE",
                    url: url.replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(resp) {
                        NProgress.done();
                        toastr.success(resp.message);
                        window.location.href = "{{ route($route . '.index') }}";
                    },
                    error: function(error) {
                        NProgress.done();
                        toastr.error("Something went wrong!");
                    }
                });
            }
        });
    }

    //edit
    function goToEdit(id) {
        let url = "{{ route($route . '.edit', ':id') }}";
        window.location.href = url.replace(':id', id);
    }
</script>
@endpush
