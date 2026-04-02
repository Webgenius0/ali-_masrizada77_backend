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
                        <li class="breadcrumb-item active" aria-current="page">Details</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card post-sales-main">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0"><i class="fe fe-eye me-2"></i>{{ ucfirst($part) }} Details</h3>
                            <div class="card-options">
                                <a href="javascript:window.history.back()" class="btn btn-sm btn-primary me-2"><i class="fe fe-arrow-left"></i> Back</a>
                                <button class="btn btn-sm btn-info" onclick="goToEdit(`{{ $blog->id }}`)"><i class="fe fe-edit"></i> Edit</button>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="row mb-5 align-items-center">
                                <div class="col-md-4 text-center">
                                    <div class="p-2 border br-7 bg-light">
                                        @if($blog->image && file_exists(public_path($blog->image)))
                                            <img src="{{ asset($blog->image) }}" alt="Image" class="img-fluid br-7 shadow-sm" style="max-height: 200px; width: 100%; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('backend/images/default.png') }}" alt="Default" class="img-fluid br-7 shadow-sm" style="max-height: 200px; width: 100%; object-fit: cover;">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <table class="table table-bordered mb-0">
                                        <tr>
                                            <th class="bg-light" style="width: 30%;">Status</th>
                                            <td>
                                                <span class="badge {{ $blog->status == 'active' ? 'bg-success' : 'bg-danger' }} px-3">
                                                    {{ ucfirst($blog->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Created Date</th>
                                            <td>{{ $blog->created_at ? $blog->created_at->format('d M, Y h:i A') : 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <hr>

                            <div class="row mt-4">
                                <div class="col-xl-6 border-end">
                                    <h5 class="fw-bold text-primary mb-3"><i class="fe fe-flag me-2"></i>English (Primary)</h5>
                                    <div class="p-4 border br-7 bg-white">
                                        <label class="small text-muted fw-bold">TITLE</label>
                                        <p class="fs-16 fw-semibold text-dark">{{ $blog->title ?? 'N/A' }}</p>

                                        <label class="small text-muted fw-bold mt-3">SUBTITLE</label>
                                        <p>{{ $blog->subtitle ?? '---' }}</p>

                                        <label class="small text-muted fw-bold mt-3 d-block">DESCRIPTION</label>
                                        <div class="description-view border p-3 br-7 bg-light" style="max-height: 400px; overflow-y: auto;">
                                            {!! $blog->description ?? '<span class="text-muted">No content available</span>' !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <h5 class="fw-bold text-info mb-3"><i class="fe fe-globe me-2"></i>German (DE)</h5>
                                    <div class="p-4 border br-7 bg-white">
                                        <label class="small text-muted fw-bold">TITLE (DE)</label>
                                        <p class="fs-16 fw-semibold text-dark">{{ $blog->title_de ?? 'N/A' }}</p>

                                        <label class="small text-muted fw-bold mt-3">SUBTITLE (DE)</label>
                                        <p>{{ $blog->subtitle_de ?? '---' }}</p>

                                        <label class="small text-muted fw-bold mt-3 d-block">DESCRIPTION (DE)</label>
                                        <div class="description-view border p-3 br-7 bg-light" style="max-height: 400px; overflow-y: auto;">
                                            {!! $blog->description_de ?? '<span class="text-muted">No German translation available</span>' !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 text-center">
                                <button class="btn btn-danger px-6" onclick="showDeleteConfirm(`{{ $blog->id }}`)">
                                    <i class="fe fe-trash me-2"></i> Delete This Record
                                </button>
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
    // Delete Confirm
    function showDeleteConfirm(id) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
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
                    headers: { 'X-CSRF-TOKEN': csrfToken },
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

    // Edit Redirect
    function goToEdit(id) {
        let url = "{{ route($route . '.edit', ':id') }}";
        window.location.href = url.replace(':id', id);
    }
</script>

<style>
    .br-7 { border-radius: 7px; }
    .description-view img { max-width: 100%; height: auto !important; }
    .px-6 { padding-left: 2rem; padding-right: 2rem; }
    .bg-light { background-color: #f9fbfd !important; }
</style>
@endpush
