@extends('backend.app', ['title' => 'Update FAQ'])

@section('content')

<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">{{ isset($crud) ? ucwords(str_replace('_', ' ', $crud)) : 'Update FAQ' }}</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('admin/dashboard') }}">
                                <i class="fe fe-home me-2 fs-14"></i>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item">FAQ</li>
                        <li class="breadcrumb-item active">Update</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card post-sales-main">

                        <div class="card-header border-bottom d-flex justify-content-between">
                            <h3 class="card-title mb-0">Update FAQ</h3>
                            <a href="javascript:history.back()" class="btn btn-sm btn-primary">Back</a>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.faq.update', $faq->id) }}">
                                @csrf
                                @method('POST')

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="form-label">Select Type <span class="text-danger">*</span></label>
                                            <select name="type" class="form-control form-select @error('type') is-invalid @enderror">
                                                <option value="english" {{ old('type', $faq->type) == 'english' ? 'selected' : '' }}>English</option>
                                                <option value="de" {{ old('type', $faq->type) == 'de' ? 'selected' : '' }}>DE (German)</option>
                                                <option value="other" {{ old('type', $faq->type) == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="form-label">Title (Optional)</label>
                                            <input
                                                type="text"
                                                name="title"
                                                class="form-control @error('title') is-invalid @enderror"
                                                placeholder="Enter title"
                                                value="{{ old('title', $faq->title) }}"
                                            >
                                            @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-4">
                                            <label class="form-label">Description (Optional)</label>
                                            <input
                                                type="text"
                                                name="discription"
                                                class="form-control @error('discription') is-invalid @enderror"
                                                placeholder="Enter description"
                                                value="{{ old('discription', $faq->discription) }}"
                                            >
                                            @error('discription')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">

                                <div class="form-group mb-4">
                                    <label for="question" class="form-label">Question <span class="text-danger">*</span></label>
                                    <input type="text"
                                           class="form-control @error('question') is-invalid @enderror"
                                           name="question"
                                           id="question"
                                           value="{{ old('question', $faq->question) }}"
                                           placeholder="Enter question">
                                    @error('question')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-4">
                                    <label for="answer" class="form-label">Answer <span class="text-danger">*</span></label>
                                    <textarea
                                        name="answer"
                                        id="answer"
                                        class="form-control @error('answer') is-invalid @enderror"
                                        rows="4"
                                        placeholder="Enter answer">{{ old('answer', $faq->answer) }}</textarea>
                                    @error('answer')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-footer mt-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fe fe-save me-1"></i> Update FAQ
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
