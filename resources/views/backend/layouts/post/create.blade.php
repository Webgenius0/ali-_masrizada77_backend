@extends('backend.app', ['title' => 'Create Post'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Create New Post</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url("admin/dashboard") }}"><i class="fe fe-home me-2 fs-14"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.post.index') }}">Posts</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-lg">
                        <div class="card-header border-bottom">
                            <h3 class="card-title">Create Post</h3>
                            <div class="card-options">
                                <a href="{{ route('admin.post.index') }}" class="btn btn-sm btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body p-4">

                            <form method="POST" action="{{ route('admin.post.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row">

                                    <!-- ==================== Beautiful Type Buttons ==================== -->
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label fw-bold mb-3">Type <span class="text-danger">*</span></label>

                                        <div class="row g-3">
                                            <!-- EN -->
                                            <div class="col-md-4">
                                                <input type="radio" name="type" id="type_en" value="en"
                                                       {{ old('type', 'en') == 'en' ? 'checked' : '' }} class="btn-check">
                                                <label for="type_en" class="btn btn-outline-primary w-100 py-4 text-center border-2 h-100">
                                                    <i class="fe fe-globe fs-3 mb-2 d-block"></i>
                                                    <span class="fs-5 fw-bold">🇬🇧 EN (English)</span>
                                                </label>
                                            </div>

                                            <!-- DE -->
                                            <div class="col-md-4">
                                                <input type="radio" name="type" id="type_de" value="de"
                                                       {{ old('type') == 'de' ? 'checked' : '' }} class="btn-check">
                                                <label for="type_de" class="btn btn-outline-warning w-100 py-4 text-center border-2 h-100">
                                                    <i class="fe fe-globe fs-3 mb-2 d-block"></i>
                                                    <span class="fs-5 fw-bold">🇩🇪 DE (German)</span>
                                                </label>
                                            </div>


                                        </div>

                                        @error('type')
                                            <span class="text-danger mt-2 d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- অন্যান্য ফিল্ডস -->
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Team</label>
                                        <input type="text" name="team" class="form-control @error('team') is-invalid @enderror" value="{{ old('team') }}">
                                        @error('team') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Location</label>
                                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}">
                                        @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Content <span class="text-danger">*</span></label>
                                        <textarea name="content" class="summernote form-control @error('content') is-invalid @enderror" rows="10">{{ old('content') }}</textarea>
                                        @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">LinkedIn Link</label>
                                        <input type="url" name="linkedin_link" class="form-control @error('linkedin_link') is-invalid @enderror" value="{{ old('linkedin_link') }}">
                                        @error('linkedin_link') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Thumbnail</label>
                                        <input type="file" name="thumbnail" class="dropify form-control @error('thumbnail') is-invalid @enderror">
                                        <small class="text-muted">jpeg, jpg, png (max 5MB)</small>
                                        @error('thumbnail') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Picture</label>
                                        <input type="file" name="picture" class="dropify form-control @error('picture') is-invalid @enderror">
                                        <small class="text-muted">jpeg, jpg, png (max 5MB)</small>
                                        @error('picture') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                </div>

                                <div class="mt-5">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fe fe-save me-2"></i> Submit Post
                                    </button>
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
