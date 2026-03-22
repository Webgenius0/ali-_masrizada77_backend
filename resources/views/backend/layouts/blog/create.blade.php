@extends('backend.app', ['title' => 'Create '.$part])

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
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </div>
            </div>

            <div class="row" id="user-profile">
                <div class="col-lg-12">
                    <div class="card post-sales-main">
                        <div class="card-header border-bottom">
                            <h3 class="card-title mb-0">Create New {{ ucfirst($part) }}</h3>
                            <div class="card-options">
                                <a href="javascript:window.history.back()" class="btn btn-sm btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body border-0">
                            <form class="form form-horizontal" method="POST" action="{{ route($route . '.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">

<div class="col-md-12">
    <div class="form-group">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label mb-0 fw-bold">Blog Type / Language: <span class="text-danger">*</span></label>

            <div class="btn-group" role="group" aria-label="Language selection">
                <input type="radio" class="btn-check" name="type" id="lang_en" value="english"
                    {{ (old('type') == 'english' || !old('type')) ? 'checked' : '' }} autocomplete="off">
                <label class="btn btn-sm btn-outline-primary px-3 shadow-sm" for="lang_en">
                    <i class="fas fa-check-circle me-1"></i> English
                </label>

                <input type="radio" class="btn-check" name="type" id="lang_de" value="de"
                    {{ old('type') == 'de' ? 'checked' : '' }} autocomplete="off">
                <label class="btn btn-sm btn-outline-info px-3 shadow-sm" for="lang_de">
                    <i class="fas fa-globe-europe me-1"></i> German (DE)
                </label>
            </div>
        </div>

        @error('type')
            <div class="text-end">
                <span class="text-danger small mt-1">{{ $message }}</span>
            </div>
        @enderror
    </div>
</div>



                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title" class="form-label">Title: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Enter title" id="title" value="{{ old('title') }}">
                                            @error('title')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="subtitle" class="form-label">Subtitle:</label>
                                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror" name="subtitle" placeholder="Enter subtitle" id="subtitle" value="{{ old('subtitle') }}">
                                            @error('subtitle')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="image" class="form-label">Feature Image:</label>
                                            <div class="d-flex align-items-start shadow-sm border  bg-light">
                                                <div class="flex-grow-1">
                                                    <x-form.file
                                                        name="image"
                                                        id="image"
                                                        class="form-control @error('image') is-invalid @enderror"
                                                        onchange="previewImage(this)"
                                                    />
                                                    <small class="text-muted d-block mt-1">Accepted: JPG, PNG, WebP (Max: 2MB)</small>
                                                </div>
                                                <div id="image-preview-container" class="ms-3" style="display:none;">
                                                    <img id="image-preview" src="#" alt="Preview"
                                                         style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #fff; box-shadow: 0 0 5px rgba(0,0,0,0.1);">
                                                </div>
                                            </div>
                                            @error('image')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Description: <span class="text-danger">*</span></label>
                                            <textarea class="summernote form-control @error('description') is-invalid @enderror" name="description" id="description">{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mt-3">
                                        <button class="submit btn btn-primary px-5" type="submit">Save Blog</button>
                                    </div>

                                </div>
                            </form>
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
        // Summernote Initialize
        $('.summernote').summernote({
            placeholder: 'Write your description here...',
            tabsize: 2,
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });

    // Image Preview Function
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result);
                $('#image-preview-container').fadeIn();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
    /* সিলেক্ট করা থাকলে ব্যাকগ্রাউন্ড কালার সলিড হবে */
    .btn-check:checked + .btn-outline-primary {
        background-color: #0d6efd !important;
        color: #fff !important;
    }
    .btn-check:checked + .btn-outline-info {
        background-color: #0dcaf0 !important;
        color: #fff !important;
    }
    .btn-group .btn {
        font-size: 13px; /* বাটন একটু ছোট দেখাবে */
        border-radius: 5px;
        margin-left: 5px; /* দুই বাটনের মাঝে গ্যাপ */
    }
</style>
@endpush
