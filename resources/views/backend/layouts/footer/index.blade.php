@extends('backend.app', ['title' => 'Footer Management'])

@push('styles')
<link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Footer Management</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url("admin/dashboard") }}"><i class="fe fe-home me-2 fs-14"></i>Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Footer Index</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card product-sales-main">
                        <div class="card-header border-bottom">
                            <h3 class="card-title">Footer Links</h3>
                            <div class="card-options ms-auto">
                                <a href="{{ route('admin.footer.create') }}" class="btn btn-primary btn-sm">Add New Link</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th>Category</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
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
<script>
    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#datatable')) {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.footer.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'type', name: 'type'},
                    {data: 'category', name: 'category'},
                    {data: 'title', name: 'title'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        }
    });

    function showStatusChangeAlert(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to update the status?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('admin.footer.status', ':id') }}".replace(':id', id),
                    success: function(resp) {
                        toastr.success(resp.message);
                        $('#datatable').DataTable().ajax.reload();
                    }
                });
            }
        });
    }

    function showDeleteConfirm(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'If you delete this, it will be gone forever.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('admin.footer.destroy', ':id') }}".replace(':id', id),
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function(resp) {
                        toastr.success(resp.message);
                        $('#datatable').DataTable().ajax.reload();
                    }
                });
            }
        });
    }
</script>
@endpush
