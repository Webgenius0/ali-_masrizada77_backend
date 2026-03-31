@extends('backend.app', ['title' => 'Contact Messages'])

@push('styles')
<link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
<style>
    .dt-center { text-align: center; }
    .view-message { cursor: pointer; }
</style>
@endpush

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Contact Messages</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url("admin/dashboard") }}">Home</a></li>
                        <li class="breadcrumb-item active">Contact Messages</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Contact Info</th>
                                            <th>Company & Role</th>
                                            <th>Business Type</th>
                                            <th>Subject</th>
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

<div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>From:</strong> <span id="view_name"></span></p>
                <p><strong>Business Type:</strong> <span id="view_business_type"></span></p>
                <p><strong>Subject:</strong> <span id="view_subject"></span></p>
                <hr>
                <p><strong>Message:</strong></p>
                <div id="view_message" class="p-3 bg-light border"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let dTable = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.contact.index') }}",
            columns: [
                { data: 'DT_RowIndex',   name: 'DT_RowIndex',   orderable: false, searchable: false },
                { data: 'info',          name: 'name' },
                { data: 'company_info',  name: 'company' },
                { data: 'business_type', name: 'business_type', defaultContent: 'N/A' },
                { data: 'subject',       name: 'subject' },
                { data: 'status',        name: 'status',  className: 'dt-center' },
                { data: 'action',        name: 'action',  orderable: false, searchable: false, className: 'dt-center' },
            ]
        });

        $(document).on('click', '.view-message', function() {
            $('#view_name').text($(this).data('name'));
            $('#view_business_type').text($(this).data('business-type'));
            $('#view_subject').text($(this).data('subject'));
            $('#view_message').text($(this).data('message'));
            $('#viewModal').modal('show');
        });
    });

    function showStatusChangeAlert(id) {
        Swal.fire({
            title: 'Change Status?',
            text: 'Are you sure you want to update the status?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Update',
        }).then((result) => {
            if (result.isConfirmed) {
                $.get("{{ route('admin.contact.status', '') }}/" + id, function(resp) {
                    toastr.success(resp.message);
                    $('#datatable').DataTable().ajax.reload(null, false);
                });
            }
        });
    }

    function deleteContact(id) {
        Swal.fire({
            title: 'Delete Message?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ url('admin/contact/delete') }}/" + id,
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(resp) {
                        toastr.success(resp.message);
                        $('#datatable').DataTable().ajax.reload(null, false);
                    }
                });
            }
        });
    }
</script>
@endpush
