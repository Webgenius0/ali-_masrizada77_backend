@extends('backend.app', ['title' => ucfirst($part)])

@push('styles')
    <link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
    <style>
        .wp-15 { width: 15%; }
        .table img { border-radius: 5px; border: 1px solid #eee; }
    </style>
@endpush

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">{{ ucfirst($part) }} Management</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url("admin/dashboard") }}"><i class="fe fe-home me-2 fs-14"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0);">{{ ucfirst($part) }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">List</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card product-sales-main">
                        <div class="card-header border-bottom">
                            <h3 class="card-title mb-0">{{ ucfirst($part) }} List</h3>
                            <div class="card-options ms-auto">
                                <a href="{{ route($route . '.create') }}" class="btn btn-primary btn-sm shadow-sm">
                                    <i class="fe fe-plus me-1"></i> Add New {{ ucfirst($part) }}
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="datatable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-bottom-0" style="width: 50px;">SL</th>
                                            <th class="border-bottom-0">Title (EN)</th>
                                            <th class="border-bottom-0 text-center">Image</th>
                                            <th class="border-bottom-0 text-center">Status</th>
                                            <th class="border-bottom-0 text-center" style="width: 100px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        </tbody>
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
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(function () {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route($route . '.index') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'title', name: 'title'},
                    {data: 'image', name: 'image', orderable: false, searchable: false, class: 'text-center'},
                    {data: 'status', name: 'status', orderable: false, searchable: false, class: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'},
                ],
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                }
            });
        });

        // Status Change Alert
        function showStatusChangeAlert(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to change this status!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route($route . '.status', '') }}/" + id,
                        type: 'GET',
                        success: function (res) {
                            $('#datatable').DataTable().ajax.reload();
                            toastr.success(res.message);
                        }
                    });
                } else {
                    // Reset checkbox if cancelled
                    $('#datatable').DataTable().ajax.reload();
                }
            })
        }

        // Delete Confirm
        function showDeleteConfirm(id) {
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
                    let url = "{{ route($route . '.destroy', ':id') }}";
                    url = url.replace(':id', id);
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: { _token: "{{ csrf_token() }}" },
                        success: function (res) {
                            $('#datatable').DataTable().ajax.reload();
                            toastr.success(res.message);
                        }
                    });
                }
            })
        }
    </script>
@endpush
