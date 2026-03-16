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
                                {{-- যদি আপনার কন্ট্রোলার পুট মেথড এক্সপেক্ট করে তবে @method('PUT') দিন, নতুবা নিচেরটি কাজ করবে --}}
                                @method('POST')

                                <div class="mb-4">
                                    <label for="question" class="form-label">Question</label>
                                    <input type="text"
                                           class="form-control @error('question') is-invalid @enderror"
                                           name="question"
                                           id="question"
                                           {{-- আপনার ডাটাবেস কলাম অনুযায়ী $faq->question বা $faq->title ব্যবহার করুন --}}
                                           value="{{ old('question', $faq->question ?? $faq->title) }}"
                                           placeholder="Enter question">
                                    @error('question')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="answer" class="form-label">Answer</label>
                                    <input type="text"
                                           class="form-control @error('answer') is-invalid @enderror"
                                           name="answer"
                                           id="answer"
                                           {{-- আপনার ডাটাবেস কলাম অনুযায়ী $faq->answer বা $faq->description ব্যবহার করুন --}}
                                           value="{{ old('answer', $faq->answer ?? $faq->description) }}"
                                           placeholder="Enter answer">
                                    @error('answer')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    Update FAQ
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
