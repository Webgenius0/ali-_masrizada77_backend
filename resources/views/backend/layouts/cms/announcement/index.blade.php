@extends('backend.app', ['title' => 'Award Announcement'])

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title">CMS : Award Announcement</h1>
            </div>

            <div class="card">
                <div class="card-body">
                    <form id="announcementForm" method="POST" action="{{ route('admin.cms.home.announcement.update') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs mb-4" id="languageTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="english-tab" data-bs-toggle="tab" data-bs-target="#english" type="button" role="tab">English</button>
                                    </li>
                                    <li class="nav-item ml-3" role="presentation">
                                        <button class="nav-link" id="german-tab" data-bs-toggle="tab" data-bs-target="#german" type="button" role="tab">German (DE)</button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="languageTabContent">
                                    {{-- English Tab --}}
                                    <div class="tab-pane fade show active" id="english" role="tabpanel">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Announcement Text (English)</label>
                                            <input type="text" name="title_en" class="form-control" value="{{ $data_en->title ?? '' }}" placeholder="Enter announcement text">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Link Text (English)</label>
                                            <input type="text" name="btn_text_en" class="form-control" value="{{ $data_en->btn_text ?? '' }}" placeholder="Enter link text">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Link URL (English)</label>
                                            <input type="text" name="btn_link_en" class="form-control" value="{{ $data_en->btn_link ?? '' }}" placeholder="Enter link URL">
                                        </div>
                                    </div>

                                    {{-- German Tab --}}
                                    <div class="tab-pane fade" id="german" role="tabpanel">
                                        <div class="form-group mb-3">
                                            <label class="form-label">Announcement Text (German)</label>
                                            <input type="text" name="title_de" class="form-control" value="{{ $data_de->title ?? '' }}" placeholder="Enter announcement text in German">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Link Text (German)</label>
                                            <input type="text" name="btn_text_de" class="form-control" value="{{ $data_de->btn_text ?? '' }}" placeholder="Enter link text in German">
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label">Link URL (German)</label>
                                            <input type="text" name="btn_link_de" class="form-control" value="{{ $data_de->btn_link ?? '' }}" placeholder="Enter link URL in German">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fe fe-save me-2"></i> Save & Publish
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
/* Default tab color */
.nav-tabs .nav-link {
    color: #555;
    background-color: #f1f1f1;
    border: 1px solid #ddd;
}

/* Active tab color */
.nav-tabs .nav-link.active {
    color: #fff !important;
    background-color: #0781b9 !important; /* blue */
    border-color: #5597df;
}

/* Hover effect */
.nav-tabs .nav-link:hover {
    color: #fff;
    background-color: #0056b3;
}
.nav-item + .nav-item {
    margin-left: 12px;
}
</style>
@endpush
