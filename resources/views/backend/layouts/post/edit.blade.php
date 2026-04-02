@extends('backend.app', ['title' => 'Edit Post'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Edit Post</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url("admin/dashboard") }}"><i class="fe fe-home me-2 fs-14"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.post.index') }}">Posts</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-lg">
                        <div class="card-header border-bottom">
                            <h3 class="card-title">Update Post Information</h3>
                            <div class="card-options">
                                <a href="{{ route('admin.post.index') }}" class="btn btn-sm btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('admin.post.update', $post->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST') {{-- রিসোর্স কন্ট্রোলারের জন্য PUT/PATCH মেথড ব্যবহার করা ভালো --}}

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">English Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>
                                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold text-info">German Title (DE)</label>
                                        <input type="text" name="title_de" class="form-control @error('title_de') is-invalid @enderror" value="{{ old('title_de', $post->title_de) }}">
                                        @error('title_de') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Team</label>
                                        <input type="text" name="team" class="form-control" value="{{ old('team', $post->team) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Location</label>
                                        <input type="text" name="location" class="form-control" value="{{ old('location', $post->location) }}">
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <label class="form-label fw-bold">English Content <span class="text-danger">*</span></label>
                                        <textarea name="content" class="summernote form-control @error('content') is-invalid @enderror" rows="10">{{ old('content', $post->content) }}</textarea>
                                        @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-12 mb-4">
                                        <label class="form-label fw-bold text-info">German Content (DE)</label>
                                        <textarea name="content_de" class="summernote form-control @error('content_de') is-invalid @enderror" rows="10">{{ old('content_de', $post->content_de) }}</textarea>
                                        @error('content_de') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">LinkedIn Link</label>
                                        <input type="url" name="linkedin_link" class="form-control" value="{{ old('linkedin_link', $post->linkedin_link) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Thumbnail</label>
                                        <input type="file" name="thumbnail" class="dropify form-control" data-default-file="{{ $post->thumbnail ? asset($post->thumbnail) : '' }}">
                                        <small class="text-muted">Leave empty to keep existing thumbnail</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Main Picture</label>
                                        <input type="file" name="picture" class="dropify form-control" data-default-file="{{ $post->picture ? asset($post->picture) : '' }}">
                                        <small class="text-muted">Leave empty to keep existing picture</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control select2">
                                            <option value="active" {{ old('status', $post->status) == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ old('status', $post->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-5 text-center">
                                    <button type="submit" class="btn btn-primary btn-lg px-6">
                                        <i class="fe fe-refresh-cw me-2"></i> Update Post
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
