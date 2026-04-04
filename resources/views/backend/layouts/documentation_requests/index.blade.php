@extends('backend.app', ['title' => 'Documentation Requests'])

@push('styles')
<link href="{{ asset('default/datatable.css') }}" rel="stylesheet" />
<style>
    .dt-center { text-align: center; }
    .badge-pending { background-color: #ffc107; color: #000; }
    .badge-reviewed { background-color: #17a2b8; color: #fff; }
    .badge-responded { background-color: #28a745; color: #fff; }
</style>
@endpush

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Documentation Requests</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url("admin/dashboard") }}">Home</a></li>
                        <li class="breadcrumb-item active">Documentation Requests</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">All Requests</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-nowrap border-bottom" id="datatable">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Company</th>
                                            <th>Document Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requests as $key => $request)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $request->full_name }}</td>
                                            <td>{{ $request->email }}</td>
                                            <td>{{ $request->company_name }}</td>
                                            <td>{{ $request->document_type }}</td>
                                            <td class="dt-center">
                                                <span class="badge badge-{{ $request->status }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td class="dt-center">
                                                <a href="{{ route('admin.documentation.request.show', $request->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fe fe-eye"></i> View
                                                </a>
                                                <button class="btn btn-sm btn-danger" onclick="deleteRequest({{ $request->id }})">
                                                    <i class="fe fe-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
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
<script src="{{ asset('default/datatable.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    });

    function deleteRequest(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                let url = "{{ route('admin.documentation.request.destroy', ':id') }}";
                url = url.replace(':id', id);
                
                let form = document.createElement('form');
                form.action = url;
                form.method = 'POST';
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush
