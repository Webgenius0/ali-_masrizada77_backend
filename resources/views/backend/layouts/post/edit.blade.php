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
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h3 class="card-title">Edit Post</h3>
                            <div class="card-options">
                                <a href="{{ route('admin.post.index') }}" class="btn btn-sm btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.post.update', $post->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('POST')

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>
                                        @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Team</label>
                                        <input type="text" name="team" class="form-control" value="{{ old('team', $post->team) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Location</label>
                                        <input type="text" name="location" class="form-control" value="{{ old('location', $post->location) }}">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Content <span class="text-danger">*</span></label>
                                        <textarea name="content" class="summernote form-control @error('content') is-invalid @enderror" rows="10">{{ old('content', $post->content) }}</textarea>
                                        @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">LinkedIn Link</label>
                                        <input type="url" name="linkedin_link" class="form-control" value="{{ old('linkedin_link', $post->linkedin_link) }}">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Thumbnail</label>
                                        <input type="file" name="thumbnail" class="dropify form-control">
                                        @if($post->thumbnail)
                                            <div class="mt-2">
                                                <img src="{{ asset($post->thumbnail) }}" width="150" class="rounded border">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Picture</label>
                                        <input type="file" name="picture" class="dropify form-control">
                                        @if($post->picture)
                                            <div class="mt-2">
                                                <img src="{{ asset($post->picture) }}" width="150" class="rounded border">
                                            </div>
                                        @endif
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Type</label>
                                        <select name="type" class="form-control">
                                            <option value="en" {{ old('type', $post->type) == 'en' ? 'selected' : '' }}>EN</option>
                                            <option value="de" {{ old('type', $post->type) == 'de' ? 'selected' : '' }}>DE</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary">Update Post</button>
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
