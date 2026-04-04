@extends('backend.app')
@section('content')
<style>
    .image-preview-wrapper {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 10px auto;
        border: 2px solid #3e4b5b;
        border-radius: 8px;
        overflow: hidden;
        background-color: #2c3e50;
        background-image: linear-gradient(45deg, #3a4a5a 25%, transparent 25%),
                          linear-gradient(-45deg, #3a4a5a 25%, transparent 25%),
                          linear-gradient(45deg, transparent 75%, #3a4a5a 75%),
                          linear-gradient(-45deg, transparent 75%, #3a4a5a 75%);
        background-size: 16px 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .image-preview-wrapper img {
        max-width: 85%;
        max-height: 85%;
        object-fit: contain;
        filter: drop-shadow(0px 0px 3px rgba(0,0,0,0.4));
    }
    .hero-image-preview {
        width: 200px;
        height: 120px;
    }
    .item-box {
        transition: all 0.3s ease;
        background: #f8f9fa;
    }
</style>

<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title">Trust Page CMS ({{ ucfirst($type) }})</h1>
                <div class="ms-auto">
                    <a href="?type=english" class="btn {{ $type == 'english' ? 'btn-primary' : 'btn-outline-primary' }}">English</a>
                    <a href="?type=de" class="btn {{ $type == 'de' ? 'btn-info' : 'btn-outline-info' }}">German</a>
                </div>
            </div>

            <form action="{{ route('admin.cms.home.trust.content') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                {{-- Hero Section --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">1. Hero Section</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="hero_title" class="form-control" value="{{ $data->metadata['hero_title'] ?? '' }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Description</label>
                                    <textarea name="hero_desc" class="form-control" rows="3">{{ $data->metadata['hero_desc'] ?? '' }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Button Text</label>
                                            <input type="text" name="hero_btn_text" class="form-control" value="{{ $data->metadata['hero_btn_text'] ?? '' }}">
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Button URL</label>
                                            <input type="text" name="hero_btn_url" class="form-control" value="{{ $data->metadata['hero_btn_url'] ?? '' }}">
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <label class="form-label">Hero Image</label>
                                <input type="file" name="hero_image" class="form-control mb-2" accept="image/*" onchange="previewImage(this, 'hero_prev')">
                                <div class="image-preview-wrapper hero-image-preview mx-auto">
                                    <img id="hero_prev" src="{{ !empty($data->image1) ? asset($data->image1) : asset('backend/images/no-image.png') }}" alt="Preview">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alignment Standards --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title">2. Our Alignment Standards</h3>
                        <button type="button" class="btn btn-sm btn-white" onclick="addStandard()">+ Add Standard Card</button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Section Title</label>
                                <input type="text" name="standards_title" class="form-control" value="{{ $data->metadata['standards_title'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Section Description</label>
                                <textarea name="standards_desc" class="form-control" rows="1">{{ $data->metadata['standards_desc'] ?? '' }}</textarea>
                            </div>
                        </div>
                        <div id="standards-wrapper">
                            @foreach ($data->metadata['standards_items'] ?? [] as $key => $item)
                                <div class="border p-3 mb-3 item-box position-relative">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 text-center">
                                            <label class="small fw-bold">Icon</label>
                                            <input type="file" name="standards_icon_{{ $key }}" class="form-control mb-2" accept="image/*" onchange="previewImage(this, 'std_icon_prev_{{ $key }}')">
                                            <div class="image-preview-wrapper mx-auto">
                                                <img id="std_icon_prev_{{ $key }}" src="{{ asset($item['icon'] ?? 'backend/images/no-image.png') }}" alt="Icon">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small fw-bold">Title</label>
                                            <input type="text" name="standards_items[{{ $key }}][title]" class="form-control mb-2" value="{{ $item['title'] ?? '' }}">

                                            <label class="small fw-bold">Badge (e.g. Public)</label>
                                            <input type="text" name="standards_items[{{ $key }}][badge]" class="form-control mb-2" value="{{ $item['badge'] ?? '' }}">

                                            <label class="small fw-bold">Footer Text (e.g. TIER 1 - MUST HAVE)</label>
                                            <input type="text" name="standards_items[{{ $key }}][footer_text]" class="form-control mb-2" value="{{ $item['footer_text'] ?? '' }}">

                                            <label class="small fw-bold">Certificate (PDF)</label>
                                            <input type="file" name="standards_pdf_{{ $key }}" class="form-control mb-1" accept="application/pdf">
                                            @if(!empty($item['pdf']))
                                                <a href="{{ asset($item['pdf']) }}" target="_blank" class="btn btn-sm btn-outline-info w-100 mt-1">View Current PDF</a>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Description</label>
                                            <textarea name="standards_items[{{ $key }}][desc]" class="form-control" rows="3">{{ $item['desc'] ?? '' }}</textarea>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Data Protection --}}
                {{-- <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title">3. How We Protect Your Data</h3>
                        <button type="button" class="btn btn-sm btn-white" onclick="addProtection()">+ Add Protection Card</button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Section Title</label>
                                <input type="text" name="protection_title" class="form-control" value="{{ $data->metadata['protection_title'] ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Section Description</label>
                                <textarea name="protection_desc" class="form-control" rows="1">{{ $data->metadata['protection_desc'] ?? '' }}</textarea>
                            </div>
                        </div>
                        <div id="protection-wrapper">
                            @foreach ($data->metadata['protection_items'] ?? [] as $key => $item)
                                <div class="border p-3 mb-3 item-box position-relative">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 text-center">
                                            <label class="small fw-bold">Icon</label>
                                            <input type="file" name="protection_icon_{{ $key }}" class="form-control mb-2" accept="image/*" onchange="previewImage(this, 'prot_icon_prev_{{ $key }}')">
                                            <div class="image-preview-wrapper mx-auto">
                                                <img id="prot_icon_prev_{{ $key }}" src="{{ asset($item['icon'] ?? 'backend/images/no-image.png') }}" alt="Icon">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small fw-bold">Title</label>
                                            <input type="text" name="protection_items[{{ $key }}][title]" class="form-control" value="{{ $item['title'] ?? '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="small fw-bold">Description</label>
                                            <textarea name="protection_items[{{ $key }}][desc]" class="form-control" rows="2">{{ $item['desc'] ?? '' }}</textarea>
                                        </div>
                                        <div class="col-md-1 text-center">
                                            <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div> --}}

                <div class="card-footer text-center py-4 bg-light shadow-sm">
                    <button type="submit" class="btn btn-success btn-lg px-5">SAVE TRUST PAGE CONTENT</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function addStandard() {
        const wrapper = document.getElementById('standards-wrapper');
        const index = Date.now();
        const html = `
        <div class="border p-3 mb-3 item-box position-relative">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <label class="small fw-bold">Icon</label>
                    <input type="file" name="standards_icon_${index}" class="form-control mb-2" accept="image/*" onchange="previewImage(this, 'std_icon_prev_${index}')">
                    <div class="image-preview-wrapper mx-auto">
                        <img id="std_icon_prev_${index}" src="{{ asset('backend/images/no-image.png') }}" alt="Icon">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Title</label>
                    <input type="text" name="standards_items[${index}][title]" class="form-control mb-2" placeholder="Title">
                    <label class="small fw-bold">Badge (e.g. Public)</label>
                    <input type="text" name="standards_items[${index}][badge]" class="form-control mb-2" placeholder="Badge">
                    <label class="small fw-bold">Footer Text (e.g. TIER 1 - MUST HAVE)</label>
                    <input type="text" name="standards_items[${index}][footer_text]" class="form-control mb-2" placeholder="Footer Text">
                    <label class="small fw-bold">Certificate (PDF)</label>
                    <input type="file" name="standards_pdf_${index}" class="form-control" accept="application/pdf">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold">Description</label>
                    <textarea name="standards_items[${index}][desc]" class="form-control" rows="3" placeholder="Description"></textarea>
                </div>
                <div class="col-md-1 text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                </div>
            </div>
        </div>`;
        wrapper.insertAdjacentHTML('beforeend', html);
    }

    function addProtection() {
        const wrapper = document.getElementById('protection-wrapper');
        const index = Date.now();
        const html = `
        <div class="border p-3 mb-3 item-box position-relative">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <label class="small fw-bold">Icon</label>
                    <input type="file" name="protection_icon_${index}" class="form-control mb-2" accept="image/*" onchange="previewImage(this, 'prot_icon_prev_${index}')">
                    <div class="image-preview-wrapper mx-auto">
                        <img id="prot_icon_prev_${index}" src="{{ asset('backend/images/no-image.png') }}" alt="Icon">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Title</label>
                    <input type="text" name="protection_items[${index}][title]" class="form-control" placeholder="Title">
                </div>
                <div class="col-md-6">
                    <label class="small fw-bold">Description</label>
                    <textarea name="protection_items[${index}][desc]" class="form-control" rows="2" placeholder="Description"></textarea>
                </div>
                <div class="col-md-1 text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                </div>
            </div>
        </div>`;
        wrapper.insertAdjacentHTML('beforeend', html);
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            const item = e.target.closest('.item-box');
            Swal.fire({
                title: 'Are you sure?',
                text: "Item will be removed from preview",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it'
            }).then((result) => {
                if (result.isConfirmed) item.remove();
            });
        }
    });
</script>
@endsection
