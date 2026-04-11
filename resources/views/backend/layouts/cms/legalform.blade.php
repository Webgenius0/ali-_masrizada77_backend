@extends('backend.app', ['title' => 'Edit '.ucfirst(str_replace('_', ' ', $section))])

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .editor-bg { background-color: #f0f2f5; padding: 40px 0; min-height: 100vh; }
        .word-page {
            background: #ffffff; max-width: 1200px; margin: 0 auto; padding: 60px 80px;
            box-shadow: 0 4px 15px rgba(0,0,0,.1); border: 1px solid #ddd;
        }
        .ck-editor__editable_inline { min-height: 500px !important; font-family: 'Times New Roman'; font-size: 18px; }
    </style>
@endpush

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header d-flex justify-content-between align-items-center">
                <h1 class="page-title">CMS : Legal Document Editor</h1>
                <button type="button" id="saveBtn" class="btn btn-primary btn-lg">
                    <i class="fe fe-save me-2"></i> Save & Publish
                </button>
            </div>

            <div class="editor-bg">
                <form id="legalForm" method="POST" action="{{ route('admin.cms.legal.store') }}">
                    @csrf
                    <input type="hidden" name="page" value="{{ $page }}">
                    <input type="hidden" name="section" value="{{ $section }}">

                    <div class="word-page">
                        <ul class="nav nav-tabs mb-4" id="languageTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="english-tab" data-bs-toggle="tab" data-bs-target="#english" type="button" role="tab">English</button>
                            </li>
                            <li class="nav-item  ml-3" role="presentation">
                                <button class="nav-link" id="german-tab" data-bs-toggle="tab" data-bs-target="#german" type="button" role="tab">German (DE)</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="languageTabContent">
                            <div class="tab-pane fade show active" id="english" role="tabpanel">
                                <div class="mb-4">
                                    <label class="form-label">Title (English)</label>
                                    <input type="text" name="title_en" class="form-control" value="{{ $data_en->title ?? '' }}" placeholder="Enter English Title">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Sub Title (English)</label>
                                    <input type="text" name="sub_title_en" class="form-control" value="{{ $data_en->sub_title ?? '' }}" placeholder="Enter English Sub Title">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Body Content (English)</label>
                                    <textarea name="description_en" id="description-editor-en">{{ $data_en->description ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="german" role="tabpanel">
                                <div class="mb-4">
                                    <label class="form-label">Title (German)</label>
                                    <input type="text" name="title_de" class="form-control" value="{{ $data_de->title ?? '' }}" placeholder="Enter German Title">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Sub Title (German)</label>
                                    <input type="text" name="sub_title_de" class="form-control" value="{{ $data_de->sub_title ?? '' }}" placeholder="Enter German Sub Title">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Body Content (German)</label>
                                    <textarea name="description_de" id="description-editor-de">{{ $data_de->description ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    // সেশন মেসেজ (যদি পেজ রিফ্রেশ হয়)
    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif

    let editorEn, editorDe;

    // CKEditor Init English
    ClassicEditor.create(document.querySelector('#description-editor-en')).then(editor => {
        editorEn = editor;
    }).catch(error => { console.error(error); });

    // CKEditor Init German
    ClassicEditor.create(document.querySelector('#description-editor-de')).then(editor => {
        editorDe = editor;
    }).catch(error => { console.error(error); });

    // Save Button Click
    document.getElementById('saveBtn').addEventListener('click', function() {
        const descriptionEn = editorEn.getData();
        const descriptionDe = editorDe.getData();

        if (!descriptionEn || !descriptionDe) {
            toastr.error('Please fill both English and German fields');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "This document will be published in both languages!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Save it!'
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = new FormData(document.getElementById('legalForm'));
                formData.set('description_en', descriptionEn);
                formData.set('description_de', descriptionDe);
                formData.set('title_en', $('input[name="title_en"]').val());
                formData.set('sub_title_en', $('input[name="sub_title_en"]').val());
                formData.set('title_de', $('input[name="title_de"]').val());
                formData.set('sub_title_de', $('input[name="sub_title_de"]').val());

                // Loading handle করার জন্য
                Swal.fire({
                    title: 'Processing...',
                    didOpen: () => { Swal.showLoading(); }
                });

                $.ajax({
                    url: "{{ route('admin.cms.legal.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
                    success: function(resp) {
                        Swal.close(); // Loading close kora
                        if (resp.success) {
                            toastr.success(resp.message); // Toastr message
                            Swal.fire('Saved!', resp.message, 'success');
                        } else {
                            toastr.error('Failed to update');
                        }
                    },
                    error: function(err) {
                        Swal.close();
                        toastr.error('Something went wrong!');
                    }
                });
            }
        });
    });
</script>
<style>
/* Default tab color */
.nav-tabs .nav-link {
    color: #555;
    background-color: #f1f1f1;
    border: 1px solid #ddd;
}

/* Active tab color */
.nav-tabs .nav-link.active {
    color: #fff;
    background-color: #0781b9; /* blue */
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
