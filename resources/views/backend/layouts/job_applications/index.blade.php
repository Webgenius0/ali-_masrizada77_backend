@extends('backend.app', ['title' => 'Job Applications'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Job Applications</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"><i class="fe fe-home me-2 fs-14"></i>Home</a></li>
                        <li class="breadcrumb-item active">Job Applications</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom d-flex justify-content-between">
                            <h3 class="card-title mb-0">Applicant List</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom yajra-datatable w-100">
                                    <thead>
                                        <tr>
                                            <th class="wd-15p border-bottom-0">#</th>
                                            <th class="wd-15p border-bottom-0">Name</th>
                                            <th class="wd-20p border-bottom-0">Email</th>
                                            <th class="wd-15p border-bottom-0">Phone</th>
                                            <th class="wd-15p border-bottom-0">Position</th>
                                            <th class="wd-10p border-bottom-0">Date</th>
                                            <th class="wd-15p border-bottom-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Data will be loaded by Yajra --}}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(function () {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.home.getalljob.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'}, // কন্ট্রোলারের addColumn('name') থেকে আসছে
                {data: 'email', name: 'email'},
                {data: 'phone_number', name: 'phone_number'},
                {data: 'position', name: 'position'},
                {data: 'date', name: 'date'}, // কন্ট্রোলারের addColumn('date') থেকে সরাসরি তারিখ আসছে
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });

    function deleteApplication(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>

@if(session('t-success'))
<script>
    Swal.fire({
        position: 'top-end',
        icon: 'success',
        title: "{{ session('t-success') }}",
        showConfirmButton: false,
        timer: 1500
    });
</script>
@endif

@if(session('t-error'))
<script>
    Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: "{{ session('t-error') }}",
        showConfirmButton: false,
        timer: 1500
    });
</script>
@endif
@endpush
