@extends('backend.app', ['title' => 'FAQ'])

@push('styles')
<link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
@endpush

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
                        <li class="breadcrumb-item"><a href="javascript:void(0);">FAQ</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Index</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card product-sales-main">
                        <div class="card-header border-bottom">
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                <button type="button" class="btn btn-danger"><a href="#" class="text-white">Import</a></button>
                                <button type="button" class="btn btn-warning"><a href="#" class="text-white">Export</a></button>
                            </div>
                            <div class="card-options ms-auto">
                                <a href="{{ route('admin.faq.create') }}" class="btn btn-primary btn-sm">Add New FAQ</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="datatable">
                                    <thead>
                                        <tr>
                                            <th class="bg-transparent border-bottom-0 wp-5">SL</th>
                                            <th class="bg-transparent border-bottom-0">Type</th>
                                            <th class="bg-transparent border-bottom-0">Title</th>
                                            <th class="bg-transparent border-bottom-0">Description</th>
                                            <th class="bg-transparent border-bottom-0">Question</th>
                                            <th class="bg-transparent border-bottom-0">Answer</th>
                                            <th class="bg-transparent border-bottom-0">Status</th>
                                            <th class="bg-transparent border-bottom-0 text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
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
    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            }
        });

        if (!$.fn.DataTable.isDataTable('#datatable')) {
            let dTable = $('#datatable').DataTable({
                order: [],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                processing: true,
                responsive: true,
                serverSide: true,

                language: {
                    processing: `<div class="text-center">
                        <img src="{{ asset('default/loader.gif') }}" alt="Loader" style="width: 50px;">
                        </div>`
                },

                scroller: {
                    loadingIndicator: false
                },
                pagingType: "full_numbers",
                dom: "<'row justify-content-between table-topbar'<'col-md-4 col-sm-3'l><'col-md-5 col-sm-5 px-0'f>>tipr",
                ajax: {
                    url: "{{ route('admin.faq.index') }}",
                    type: "GET",
                },

                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'type',
                        name: 'type',
                        render: function(data) {
                            return `<span class="badge bg-info-transparent text-info px-3 py-2 text-uppercase">${data}</span>`;
                        }
                    },
                    { data: 'title', name: 'title' },
                    {
                        data: 'discription',
                        name: 'discription',
                        render: function(data) {
                            // ডিসক্রিপশন অনেক বড় হলে ভেঙে ছোট করে দেখাবে
                            return data ? (data.length > 30 ? data.substr(0, 30) + '...' : data) : 'N/A';
                        }
                    },
                    { data: 'question', name: 'question' },
                    { data: 'answer', name: 'answer' },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
            });
        }
    });

    // Status Change Confirm Alert
    function showStatusChangeAlert(id) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: 'You want to update the status?',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
                statusChange(id);
            }
        });
    }

    // Status Change
    function statusChange(id) {
        NProgress.start();
        let url = "{{ route('admin.faq.status', ':id') }}";
        $.ajax({
            type: "GET",
            url: url.replace(':id', id),
            success: function(resp) {
                NProgress.done();
                toastr.success(resp.message);
                $('#datatable').DataTable().ajax.reload();
            },
            error: function(error) {
                NProgress.done();
                toastr.error("Something went wrong!");
            }
        });
    }

    // Delete Confirm
    function showDeleteConfirm(id) {
        event.preventDefault();
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
                deleteItem(id);
            }
        });
    }

    // Delete Action
    function deleteItem(id) {
        NProgress.start();
        let url = "{{ route('admin.faq.destroy', ':id') }}";
        $.ajax({
            type: "DELETE",
            url: url.replace(':id', id),
            success: function(resp) {
                NProgress.done();
                toastr.success(resp.message);
                $('#datatable').DataTable().ajax.reload();
            },
            error: function(error) {
                NProgress.done();
                toastr.error("Failed to delete record.");
            }
        });
    }

    function goToEdit(id) {
        let url = "{{ route('admin.faq.edit', ':id') }}";
        window.location.href = url.replace(':id', id);
    }

    function goToOpen(id) {
        let url = "{{ route('admin.faq.show', ':id') }}";
        window.location.href = url.replace(':id', id);
    }
</script>
@endpush
