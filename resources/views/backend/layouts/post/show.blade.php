@extends('backend.app', ['title' => 'View Post'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">View Post</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url("admin/dashboard") }}"><i class="fe fe-home me-2 fs-14"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.post.index') }}">Posts</a></li>
                        <li class="breadcrumb-item active" aria-current="page">View</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h3 class="card-title">{{ $post->title }}</h3>
                        </div>
                        <div class="card-body">

                            @if($post->thumbnail)
                                <div class="text-center mb-4">
                                    <img src="{{ asset($post->thumbnail) }}" class="img-fluid rounded" style="max-height: 350px;">
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="140">Team</th>
                                            <td>{{ $post->team ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Location</th>
                                            <td>{{ $post->location ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>LinkedIn</th>
                                            <td>
                                                @if($post->linkedin_link)
                                                    <a href="{{ $post->linkedin_link }}" target="_blank">{{ $post->linkedin_link }}</a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="140">Status</th>
                                            <td>
                                                <span class="badge {{ $post->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($post->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Type</th>
                                            <td><span class="badge bg-info">{{ strtoupper($post->type) }}</span></td>
                                        </tr>
                                        <tr>
                                            <th>Created At</th>
                                            <td>{{ $post->created_at->format('d M, Y h:i A') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($post->picture)
                                <div class="mt-4">
                                    <h5>Additional Picture:</h5>
                                    <img src="{{ asset($post->picture) }}" class="img-fluid rounded">
                                </div>
                            @endif

                            <div class="mt-5">
                                <h5>Content:</h5>
                                <div class="card bg-light p-4">
                                    {!! $post->content !!}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
