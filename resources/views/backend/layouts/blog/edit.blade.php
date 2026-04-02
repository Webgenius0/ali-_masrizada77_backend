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
                            <h3 class="card-title mb-0">Update {{ ucfirst($part) }} (Multi-Language)</h3>
                            <div class="card-options">
                                <a href="javascript:window.history.back()" class="btn btn-sm btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body border-0">
                            <form class="form form-horizontal" method="POST" action="{{ route($route . '.update', $blog->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">

                                    <div class="col-xl-6 border-end">
                                        <div class="d-flex align-items-center mb-4">
                                            <span class="avatar avatar-md brround bg-primary-transparent text-primary me-2"><i class="fe fe-edit-3"></i></span>
                                            <h5 class="mb-0 fw-bold text-primary">English Content (Primary)</h5>
                                        </div>

                                        <div class="form-group">
                                            <label for="title" class="form-label fw-semibold">Title (EN): <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" placeholder="Enter english title" value="{{ old('title', $blog->title) }}">
                                            @error('title')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="subtitle" class="form-label fw-semibold">Subtitle (EN):</label>
                                            <input type="text" class="form-control" name="subtitle" placeholder="Enter english subtitle" value="{{ old('subtitle', $blog->subtitle) }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="description" class="form-label fw-semibold">Description (EN): <span class="text-danger">*</span></label>
                                            <textarea class="summernote form-control @error('description') is-invalid @enderror" name="description">{{ old('description', $blog->description) }}</textarea>
                                            @error('description')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-xl-6 bg-light-50">
                                        <div class="d-flex align-items-center mb-4">
                                            <span class="avatar avatar-md brround bg-info-transparent text-info me-2"><i class="fe fe-globe"></i></span>
                                            <h5 class="mb-0 fw-bold text-info">German (DE) Content</h5>
                                        </div>

                                        <div class="form-group">
                                            <label for="title_de" class="form-label fw-semibold">Title (DE):</label>
                                            <input type="text" class="form-control" name="title_de" placeholder="German title" value="{{ old('title_de', $blog->title_de) }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="subtitle_de" class="form-label fw-semibold">Subtitle (DE):</label>
                                            <input type="text" class="form-control" name="subtitle_de" placeholder="German subtitle" value="{{ old('subtitle_de', $blog->subtitle_de) }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="description_de" class="form-label fw-semibold">Description (DE):</label>
                                            <textarea class="summernote form-control" name="description_de">{{ old('description_de', $blog->description_de) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-5 pt-4 border-top">
                                        <div class="row justify-content-center">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="image" class="form-label fw-bold">Feature Image:</label>
                                                    <div class="d-flex align-items-center p-3 shadow-sm border bg-white br-7">
                                                        <div class="flex-grow-1">
                                                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" onchange="previewImage(this)">
                                                            <small class="text-muted d-block mt-2"><i class="fe fe-info me-1"></i>Accepted: JPG, PNG, WebP (Max: 2MB)</small>
                                                        </div>
                                                        <div class="ms-4 text-center">
                                                            <img id="image-preview"
                                                                 src="{{ $blog->image ? asset($blog->image) : asset('backend/images/default.png') }}"
                                                                 alt="Preview"
                                                                 style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px; border: 3px solid #f1f1f1; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                                            <p class="small text-muted mt-1 mb-0">Current Image</p>
                                                        </div>
                                                    </div>
                                                    @error('image')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group mt-5 text-center">
                                                    <button class="btn btn-primary btn-lg px-8 shadow-primary" type="submit">
                                                        <i class="fe fe-check-circle me-2"></i> Update Blog Content
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
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
            placeholder: 'Update content here...',
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

<style>
    .bg-light-50 {
        background-color: #f8f9fa;
        border-radius: 0 10px 10px 0;
    }
    .shadow-primary {
        box-shadow: 0 4px 14px 0 rgba(13, 110, 253, 0.39) !important;
    }
    .br-7 {
        border-radius: 7px;
    }
    .px-8 {
        padding-left: 3rem !important;
        padding-right: 3rem !important;
    }
</style>
@endpush
