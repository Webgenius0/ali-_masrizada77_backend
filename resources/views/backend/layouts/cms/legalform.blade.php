@extends('backend.app', ['title' => 'Edit '.ucfirst(str_replace('_', ' ', $section))])

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        .editor-bg { background-color: #f0f2f5; padding: 40px 0; min-height: 100vh; }
        .word-page { 
            background: #fff; max-width: 1200px; margin: 0 auto; padding: 60px 80px; 
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
                        {{-- <div class="mb-4">
                            <label class="form-label">Document Heading</label>
                            <input type="text" name="title" class="form-control" value="{{ $data->title ?? '' }}">
                        </div> --}}
                        <div class="mb-4 ">
                            <label class="form-label">Body Content</label>
                            <textarea name="description" id="description-editor">{{ $data->description ?? '' }}</textarea>
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

    let myEditor;

    // CKEditor Init
    ClassicEditor.create(document.querySelector('#description-editor')).then(editor => {
        myEditor = editor;
    }).catch(error => { console.error(error); });

    // Save Button Click
    document.getElementById('saveBtn').addEventListener('click', function() {
        // const title = document.querySelector('input[name="title"]').value;
        const description = myEditor.getData();

        if ( !description) {
            toastr.error('Please fill all fields'); // Toastr error use kora hoyeche
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "This document will be published!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Save it!'
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = new FormData(document.getElementById('legalForm'));
                formData.set('description', description);

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
                            // Success hole ekti SweetAlert o dekhaite paren
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
@endpush