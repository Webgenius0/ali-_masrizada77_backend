@extends('backend.app', ['title' => 'Posts'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">

        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">All Posts</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url("admin/dashboard") }}"><i class="fe fe-home me-2 fs-14"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Posts</li>
                    </ol>
                    <a href="{{ route('admin.post.create') }}" class="btn btn-primary btn-sm">
                        <i class="fe fe-plus"></i> Add New Post
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card post-sales-main">
                        <div class="card-header border-bottom">
                            <h3 class="card-title mb-0">Posts List</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="postTable" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Thumbnail</th>
                                            <th>Title (EN / DE)</th> {{-- কলামের নাম পরিবর্তন করা হয়েছে --}}
                                            <th>Team</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                </table>
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
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {

    $('#postTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.post.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'thumbnail', name: 'thumbnail', orderable: false },
            { data: 'title_combined', name: 'title' }, {{-- কন্ট্রোলার থেকে পাঠানো টাইটেল কলাম --}}
            { data: 'team', name: 'team' },
            { data: 'location', name: 'location' },
            { data: 'status', name: 'status', orderable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // ==================== Status Change with SweetAlert ====================
    $(document).on('click', '.status-btn', function() {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Change Status?',
            text: "Do you want to toggle the status?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Change it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.get("{{ url('admin/post/status') }}/" + id, function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message, {{-- response.success এর বদলে response.message --}}
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $('#postTable').DataTable().ajax.reload(null, false);
                }).fail(function() {
                    Swal.fire('Error!', 'Could not change status.', 'error');
                });
            }
        });
    });

    // ==================== Delete with SweetAlert ====================
    $(document).on('click', '.delete-btn', function() {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/post/delete') }}/" + id,
                    type: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $('#postTable').DataTable().ajax.reload(null, false);
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    }
                });
            }
        });
    });

});
</script>
@endpush
