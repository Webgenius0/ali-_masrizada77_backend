@extends('backend.app', ['title' => 'Create Footer Link'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Footer Management</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url("admin/dashboard") }}"><i class="fe fe-home me-2 fs-14"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.footer.index') }}">Footer Index</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h3 class="card-title mb-0">Create Footer Link</h3>
                            <div class="card-options">
                                <a href="{{ route('admin.footer.index') }}" class="btn btn-sm btn-primary">Back</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.footer.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="type" class="form-label">Language Type:</label>
                                        <select name="type" id="type" class="form-control @error('type') is-invalid @enderror">
                                            <option value="english" {{ old('type') == 'english' ? 'selected' : '' }}>English</option>
                                            <option value="de" {{ old('type') == 'de' ? 'selected' : '' }}>German (DE)</option>
                                            {{-- <option value="others" {{ old('type') == 'others' ? 'selected' : '' }}>Others</option> --}}
                                        </select>
                                        @error('type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="category" class="form-label">Category (Heading):</label>
                                        <input type="text" name="category" id="category" class="form-control @error('category') is-invalid @enderror" placeholder="e.g. TECHNOLOGY, VERTICALS" value="{{ old('category') }}">
                                        @error('category')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="title" class="form-label">Title (Link Text):</label>
                                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Platform Overview" value="{{ old('title') }}" required>
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="url" class="form-label">URL:</label>
                                        <input type="text" name="url" id="url" class="form-control @error('url') is-invalid @enderror" placeholder="e.g. /platform-overview" value="{{ old('url') }}">
                                        @error('url')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- <div class="form-group col-md-6">
                                        <label for="order" class="form-label">Display Order:</label>
                                        <input type="number" name="order" id="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 0) }}">
                                        @error('order')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                    <div class="form-group col-12 mt-4">
                                        <button type="submit" class="btn btn-primary">Create Link</button>
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
