@extends('backend.app', ['title' => 'Blog Heading CMS'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <h1 class="page-title">CMS: Blog Heading</h1>
                <div class="ms-auto">
                    <div class="btn-list">
                        <a href="?type=english" class="btn {{ (request('type') == 'english' || !request('type')) ? 'btn-primary' : 'btn-outline-primary' }} btn-pill">
                            <span class="me-1">🇺🇸</span> English
                        </a>
                        <a href="?type=de" class="btn {{ request('type') == 'de' ? 'btn-info' : 'btn-outline-info' }} btn-pill">
                            <span class="me-1">🇩🇪</span> German (DE)
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <form action="{{ route('admin.blog_heading.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ request('type') ?? 'english' }}">
                    <input type="hidden" name="page" value="blog">
                    <input type="hidden" name="section" value="heading">

                    <div class="card-header border-bottom">
                        <h3 class="card-title">Editing Content for: <b class="text-primary">{{ strtoupper(request('type') ?? 'english') }}</b></h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="form-label">Blog Main Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $data->title ?? '' }}">
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label">Blog Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ $data->description ?? '' }}</textarea>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label">Heading Background Image (image1)</label>
                                <input type="file" name="image1" id="image1Input" class="form-control" accept="image/*">

                                {{-- ইমেজ শো করার মেইন কন্টেইনার --}}
                                <div id="image-preview-wrapper" class="mt-4">
                                    @if(isset($data->image1) && file_exists(public_path($data->image1)))
                                        <div class="preview-box p-2 border  shadow-sm bg-light" style="width: fit-content;">
                                            <label class="d-block text-muted mb-2 small fw-bold">Current Image:</label>
                                            <img src="{{ asset($data->image1) }}" id="main-preview-img" class=" border" style="max-width: 300px; height: auto; display: block;">
                                        </div>
                                    @else
                                        <div id="no-image-placeholder" class="mt-2 p-4 border border-dashed rounded text-center bg-light text-muted">
                                            <i class="fe fe-image fs-30 d-block mb-1"></i>
                                            <span>No image selected or uploaded</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Update {{ strtoupper(request('type') ?? 'english') }} Content</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ইমেজ সিলেক্ট করলেই লাইভ প্রিভিউ দেখানোর ফাংশন
    document.getElementById('image1Input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const previewWrapper = document.getElementById('image-preview-wrapper');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // প্রিভিউ কন্টেইনারের ভেতরের কন্টেন্ট আপডেট করা
                previewWrapper.innerHTML = `
                    <div class="preview-box p-2 border  shadow-sm bg-white" style="width: fit-content; border-color: #3085d6 !important;">
                        <label class="d-block text-primary mb-2 small fw-bold">New Selected Image Preview:</label>
                        <img src="${e.target.result}" class=" border" style="max-width: 300px; height: auto; display: block;">
                        <p class="mt-2 mb-0 small text-muted text-center">${file.name}</p>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
    });
</script>

@if(session('t-success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Updated!',
            text: "{{ session('t-success') }}",
            timer: 2000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    </script>
@endif
@endpush
