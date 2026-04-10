@extends('backend.app', ['title' => 'View Post'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Post Details</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url("admin/dashboard") }}"><i class="fe fe-home me-2 fs-14"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.post.index') }}">Posts</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                    <a href="{{ route('admin.post.edit', $post->id) }}" class="btn btn-warning btn-sm">
                        <i class="fe fe-edit"></i> Edit Post
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header border-bottom">
                            <h3 class="card-title">General Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-md-4 text-center">
                                    <label class="fw-bold d-block mb-2">Thumbnail</label>
                                    @if($post->thumbnail)
                                        <img src="{{ asset($post->thumbnail) }}" class="img-fluid  border p-1" style="max-height: 200px;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center  border" style="height: 150px;">No Image</div>
                                    @endif
                                </div>
                                <div class="col-md-8">
                                    <table class="table table-bordered mt-4">
                                        <tr>
                                            <th width="150" class="bg-light">Team</th>
                                            <td>{{ $post->team ?? 'N/A' }}</td>
                                            <th width="150" class="bg-light">Status</th>
                                            <td>
                                                <span class="badge {{ $post->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($post->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Location</th>
                                            <td>{{ $post->location ?? 'N/A' }}</td>
                                            <th class="bg-light">Created At</th>
                                            <td>{{ $post->created_at->format('d M, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">LinkedIn</th>
                                            <td colspan="3">
                                                @if($post->linkedin_link)
                                                    <a href="{{ $post->linkedin_link }}" target="_blank" class="text-primary">{{ $post->linkedin_link }}</a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <hr>

                            <div class="panel panel-primary mt-5">
                                <div class="tab-menu-heading">
                                    <div class="tabs-menu">
                                        <ul class="nav panel-tabs">
                                            <li><a href="#tab_en" class="active me-2 btn btn-outline-primary" data-bs-toggle="tab">🇬🇧 English Version</a></li>
                                            <li><a href="#tab_de" class="btn btn-outline-info" data-bs-toggle="tab">🇩🇪 German Version (DE)</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body p-0 mt-4">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_en">
                                            <h4 class="fw-bold mb-3">{{ $post->title }}</h4>
                                            <div class="p-4 border  bg-white overflow-auto" style="min-height: 200px;">
                                                {!! $post->content ?? '<p class="text-muted">No English content available.</p>' !!}
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="tab_de">
                                            <h4 class="fw-bold mb-3 text-info">{{ $post->title_de ?? 'No German Title' }}</h4>
                                            <div class="p-4 border  bg-white overflow-auto" style="min-height: 200px;">
                                                {!! $post->content_de ?? '<p class="text-muted">No German content available.</p>' !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($post->picture)
                                <div class="mt-5 border-top pt-4 text-center">
                                    <h5 class="fw-bold mb-3">Main Content Image:</h5>
                                    <img src="{{ asset($post->picture) }}" class="img-fluid  shadow-sm border" style="max-height: 500px;">
                                </div>
                            @endif

                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('admin.post.index') }}" class="btn btn-primary px-5">Close</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
