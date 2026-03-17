@extends('backend.app', ['title' => 'Update '.$part])

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
                        <li class="breadcrumb-item active" aria-current="page">Update</li>
                    </ol>
                </div>
            </div>

            <div class="row" id="user-profile">
                <div class="col-lg-12">
                    <div class="card post-sales-main">
                        <div class="card-header border-bottom">
                            <h3 class="card-title mb-0">Update {{ ucfirst($part) }}</h3>
                            <div class="card-options">
                                <a href="javascript:window.history.back()" class="btn btn-sm btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body border-0">
                            <form class="form form-horizontal" method="POST" action="{{ route($route . '.update', $blog->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mb-4">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="type" class="form-label">Blog Type / Language: <span class="text-danger">*</span></label>
                                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                                                <option value="english" {{ old('type', $blog->type) == 'english' ? 'selected' : '' }}>English</option>
                                                <option value="de" {{ old('type', $blog->type) == 'de' ? 'selected' : '' }}>German (DE)</option>
                                                <option value="other" {{ old('type', $blog->type) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('type')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title" class="form-label">Title: <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Enter title" id="title" value="{{ old('title', $blog->title) }}">
                                            @error('title')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="subtitle" class="form-label">Subtitle:</label>
                                            <input type="text" class="form-control @error('subtitle') is-invalid @enderror" name="subtitle" placeholder="Enter subtitle" id="subtitle" value="{{ old('subtitle', $blog->subtitle) }}">
                                            @error('subtitle')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="image" class="form-label">Feature Image:</label>
                                            <div class="row align-items-center shadow-sm  border  mx-0 bg-light">
                                                <div class="col-md-8">
                                                    <x-form.file
                                                        name="image"
                                                        id="image"
                                                        class="form-control @error('image') is-invalid @enderror"
                                                        onchange="previewImage(this)"
                                                    />
                                                    <small class="text-muted d-block mt-1">Accepted: JPG, PNG, WebP (Max: 2MB)</small>
                                                </div>
                                                <div class="col-md-4 text-center mt-3 mt-md-0">
                                                    <img id="image-preview"
                                                         src="{{ $blog->image ? asset($blog->image) : asset('backend/images/default.png') }}"
                                                         alt="Preview"
                                                         style="width: 120px; height: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #fff; box-shadow: 0 0 5px rgba(0,0,0,0.1);">
                                                    <p class="small text-muted mb-0">Current Image</p>
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
                                            <textarea class="summernote form-control @error('description') is-invalid @enderror" name="description" id="description">{{ old('description', $blog->description) }}</textarea>
                                            @error('description')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mt-3">
                                        <button class="submit btn btn-primary px-5" type="submit">Update Blog</button>
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
            placeholder: 'Update your description...',
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
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
