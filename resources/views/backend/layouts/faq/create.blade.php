@extends('backend.app', ['title' => 'Create Faq'])

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
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/dashboard') }}">
                                <i class="fe fe-home me-2 fs-14"></i>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">Faq</li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card post-sales-main">
                        <div class="card-header border-bottom">
                            <h3 class="card-title mb-0">Create FAQ</h3>
                            <div class="card-options">
                                <a href="javascript:history.back()" class="btn btn-sm btn-primary">Back</a>
                            </div>
                        </div>

                        <div class="card-body border-0">
                            <form method="POST" action="{{ route('admin.faq.store') }}">
                                @csrf

                                <div class="form-group mb-4">
                                    <label class="form-label">Question</label>
                                    <input
                                        type="text"
                                        name="question"
                                        class="form-control @error('question') is-invalid @enderror"
                                        placeholder="Enter question"
                                        value="{{ old('question') }}"
                                    >
                                    @error('question')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label class="form-label">Answer</label>
                                    <input
                                        type="text"
                                        name="answer"
                                        class="form-control @error('answer') is-invalid @enderror"
                                        placeholder="Enter answer"
                                        value="{{ old('answer') }}"
                                    >
                                    @error('answer')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>

                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
