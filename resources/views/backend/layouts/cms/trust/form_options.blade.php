@extends('backend.app')
@section('content')
<style>
    .item-box {
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
</style>

<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title">Trust Form Options ({{ ucfirst($type) }})</h1>
                <div class="ms-auto">
                    <a href="?type=english" class="btn {{ $type == 'english' ? 'btn-primary' : 'btn-outline-primary' }}">English</a>
                    <a href="?type=de" class="btn {{ $type == 'de' ? 'btn-info' : 'btn-outline-info' }}">German</a>
                </div>
            </div>

            <form action="{{ route('admin.cms.home.trust_form.update') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title">What Document Do You Need? (Dropdown Options)</h3>
                        <button type="button" class="btn btn-sm btn-white" onclick="addOption()">+ Add New Option</button>
                    </div>
                    <div class="card-body">
                        <div id="options-wrapper">
                            @php
                                $options = $data->metadata['options'] ?? [];
                            @endphp
                            
                            @if(count($options) > 0)
                                @foreach ($options as $key => $option)
                                    <div class="border p-3 mb-3 item-box position-relative">
                                        <div class="row align-items-center">
                                            <div class="col-md-10">
                                                <input type="text" name="options[]" class="form-control" value="{{ $option }}" placeholder="Enter document name">
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                {{-- Initial empty row if no data --}}
                                <div class="border p-3 mb-3 item-box position-relative">
                                    <div class="row align-items-center">
                                        <div class="col-md-10">
                                            <input type="text" name="options[]" class="form-control" placeholder="Enter document name">
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer text-center py-4 bg-light shadow-sm">
                    <button type="submit" class="btn btn-success btn-lg px-5">SAVE FORM OPTIONS</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function addOption() {
        const wrapper = document.getElementById('options-wrapper');
        const html = `
        <div class="border p-3 mb-3 item-box position-relative">
            <div class="row align-items-center">
                <div class="col-md-10">
                    <input type="text" name="options[]" class="form-control" placeholder="Enter document name">
                </div>
                <div class="col-md-2 text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                </div>
            </div>
        </div>`;
        wrapper.insertAdjacentHTML('beforeend', html);
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const item = e.target.closest('.item-box');
            Swal.fire({
                title: 'Are you sure?',
                text: "Option will be removed",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it'
            }).then((result) => {
                if (result.isConfirmed) item.remove();
            });
        }
    });
</script>
@endsection
