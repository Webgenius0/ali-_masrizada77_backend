@extends('backend.app')

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">

            <div class="page-header d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <h1 class="page-title">Conversational AI Overview ({{ ucfirst($selected_type ?? 'english') }})</h1>
                <div class="ms-auto">
                    <div class="btn-group p-1 bg-white border shadow-sm" role="group">
                        <a href="{{ route('admin.cms.home.conversational.index', ['type' => 'english']) }}"
                            class="btn {{ ($selected_type ?? 'english') == 'english' ? 'btn-primary' : 'btn-light' }}">
                            English
                        </a>
                        <a href="{{ route('admin.cms.home.conversational.index', ['type' => 'de']) }}"
                            class="btn {{ ($selected_type ?? '') == 'de' ? 'btn-danger' : 'btn-light' }}">
                            Other Language
                        </a>
                    </div>
                </div>
            </div>

            <div class="main-container container-fluid">
                <form action="{{ route('admin.cms.home.conversational.content') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="{{ $selected_type ?? 'english' }}">

                    {{-- SECTION 1: Hero Section --}}
                    <div class="card shadow mb-4 text-dark">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">1. Hero Section</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 border-end">
                                    <x-form.text name="sec1_title" label="Title" :value="$data->metadata['sec1_title'] ?? ''" />
                                    <x-form.text name="sec1_desc" label="Description" :value="$data->metadata['sec1_desc'] ?? ''" />
                                    <x-form.text name="sec1_url_title" label="URL Title" :value="$data->metadata['sec1_url_title'] ?? ''" />
                                    <x-form.text name="sec1_url_link" label="URL Link" :value="$data->metadata['sec1_url_link'] ?? ''" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.file name="video_file" label="Upload Hero Video (MP4)" />
                                    {{-- Video Preview Fix --}}
                                    @if (!empty($data->video))
                                        <div class="mt-3 p-2 border bg-light">
                                            <label class="d-block text-muted mb-2">Video Preview:</label>
                                            <video width="100%" height="200" controls class="shadow-sm ">
                                                {{-- asset এর ভেতর সরাসরি কলামের নাম দিন --}}
                                                <source src="{{ asset($data->video) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- SECTION 2: Core Capabilities --}}
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0">2. Core Capabilities</h5>
                            <button type="button" id="add-core-item" class="btn btn-sm btn-light">
                                <i class="fe fe-plus"></i> Add New Item
                            </button>
                        </div>

                        <div class="card-body pt-4">

                            <div class="row g-4">
                                <div class="col-lg-7">
                                    <div id="core-items-container">
                                        <!-- Existing items will be rendered here + new ones added via JS -->

                                        @php
                                            $coreItems = $data->metadata['sec2_items'] ?? [];
                                            $itemCount = count($coreItems);
                                        @endphp

                                        @forelse($coreItems as $index => $item)
                                            <div class="core-item mb-3 position-relative" data-index="{{ $index }}">
                                                <div class="card border bg-white shadow-none">
                                                    <div
                                                        class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                                                        <strong>Capability {{ $index + 1 }}</strong>
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger remove-core-item">
                                                            <i class="fe fe-trash-2"></i>
                                                        </button>
                                                    </div>
                                                    <div class="card-body p-3">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small">Title</label>
                                                            <input type="text"
                                                                name="sec2_items[{{ $index }}][title]"
                                                                class="form-control" value="{{ $item['title'] ?? '' }}"
                                                                placeholder="e.g. Real-time Voice Interaction" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold small">Description</label>
                                                            <input name="sec2_items[{{ $index }}][desc]" rows="2" class="form-control" value="{{ $item['desc'] ?? '' }}"
                                                                placeholder="Short description..." />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center text-muted py-5">
                                                No capabilities added yet. Click "Add New Item" to start.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Right side - static part (kept simple) -->
                                <div class="col-lg-5">
                                    <div class="card border bg-white shadow-none h-100">
                                        <div class="card-header py-2 bg-light">
                                            <strong>Main Section Info</strong>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Main Title</label>
                                                <input type="text" name="sec2_right_title" class="form-control"
                                                    value="{{ $data->metadata['sec2_right_title'] ?? '' }}"
                                                    placeholder="e.g. Our Core Strengths">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">Main Description</label>
                                                <textarea name="sec2_right_desc" rows="4" class="form-control" placeholder="Overall description...">{{ $data->metadata['sec2_right_desc'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- SECTION 3: Control & Optimize --}}
                    <div class="card shadow mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">3.Built For Operational Scale</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 border-end">
                                    <x-form.text name="sec3_title" label="Title" :value="$data->metadata['sec3_title'] ?? ''" />
                                    <x-form.text name="sec3_desc" label="Description" :value="$data->metadata['sec3_desc'] ?? ''" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.file name="bottom_video" label="Upload Section Video" />
                                    {{-- Video Preview Fix --}}
                                    @if (!empty($data->image4))
                                        <div class="mt-3 p-2 border bg-light">
                                            <video width="100%" height="200" controls class="shadow-sm ">
                                                {{-- image4 কলাম কল করুন --}}
                                                <source src="{{ asset($data->image4) }}" type="video/mp4">
                                            </video>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 4: Connected Across --}}
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">4. More Then Just a Phone Call</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8 border-end">
                                    <x-form.text name="sec4_title" label="Main Title" :value="$data->metadata['sec4_title'] ?? ''" />
                                    <x-form.text name="sec4_subtitle" label="Subtitle" :value="$data->metadata['sec4_subtitle'] ?? ''" />
                                    <div class="row mt-3">
                                        @for ($i = 0; $i < 3; $i++)
                                            <div class="col-md-4">
                                                <x-form.text name="sec4_features[{{ $i }}]"
                                                    label="Feature {{ $i + 1 }}" :value="$data->metadata['sec4_features'][$i] ?? ''" />
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <x-form.file name="image1" label="Upload Section Video"
                                        accept="video/mp4,video/webm" />

                                    @if (!empty($data->metadata['sec4_image']))
                                        <div class="mt-3 p-2 border bg-light ">
                                            <label class="d-block text-muted small mb-2">Video Preview:</label>
                                            <video width="100%" height="180" controls class="shadow-sm ">
                                                <source src="{{ asset($data->metadata['sec4_image']) }}"
                                                    type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 5: Flexible by Design --}}
                    <div class="card shadow mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">5. Instant Support</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <x-form.text name="sec5_main_title" label="Section Main Title" :value="$data->metadata['sec5_main_title'] ?? ''" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.text name="sec5_main_desc" label="Section Main Description"
                                        :value="$data->metadata['sec5_main_desc'] ?? ''" />
                                </div>
                            </div>

                            <hr>
                            <h6 class="fw-bold mb-4 text-primary"><i class="fe fe-layers me-1"></i> Interactive Content
                                Items (4)</h6>

                            <div class="row">
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="col-md-6 mb-4">
                                        <div class="card border shadow-none bg-light">
                                            <div class="card-header py-2 bg-primary-transparent">
                                                <span class="badge bg-primary">Item {{ $i + 1 }}</span>
                                            </div>
                                            <div class="card-body p-3">
                                                <x-form.text name="sec5_items[{{ $i }}][title]" label="Title"
                                                    :value="$data->metadata['sec5_items'][$i]['title'] ?? ''" />
                                                <x-form.text name="sec5_items[{{ $i }}][sub]"
                                                    label="Sub Title" :value="$data->metadata['sec5_items'][$i]['sub'] ?? ''" />

                                                <div class="form-group mb-3">
                                                    <label class="form-label fw-bold small">Description</label>
                                                    <textarea name="sec5_items[{{ $i }}][desc]" class="form-control" rows="2">{{ $data->metadata['sec5_items'][$i]['desc'] ?? '' }}</textarea>
                                                </div>

                                                <div class="mt-3 border-top pt-3">
                                                    <x-form.file name="sec5_item_image_{{ $i }}"
                                                        label="Upload Item Image" />
                                                    <input type="hidden"
                                                        name="sec5_items[{{ $i }}][old_image]"
                                                        value="{{ $data->metadata['sec5_items'][$i]['image'] ?? '' }}">

                                                    @if (!empty($data->metadata['sec5_items'][$i]['image']))
                                                        <div class="mt-2 text-center">
                                                            <img src="{{ asset($data->metadata['sec5_items'][$i]['image']) }}"
                                                                class="img-thumbnail"
                                                                style="max-height:100px; width: auto; background: white;">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    {{-- Sticky Submit Button --}}
                    <div class="card shadow-lg mb-5 sticky-bottom" style="z-index: 1000; bottom: 20px;">
                        <div class="card-body text-center p-3">
                            <button type="submit" class="btn btn-success btn-lg px-6 shadow-sm fw-bold">
                                <i class="fe fe-save me-2"></i> UPDATE ALL CONTENT
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS (শেষের দিকে / body এর আগে) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const container = document.getElementById('core-items-container');
            const addBtn = document.getElementById('add-core-item');

            let nextIndex = {{ $itemCount ?? 0 }};

            // Add new item
            addBtn.addEventListener('click', function() {
                const html = `
                <div class="core-item mb-3 position-relative" data-index="${nextIndex}">
                    <div class="card border bg-white shadow-none">
                        <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                            <strong>Capability ${nextIndex + 1}</strong>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-core-item">
                                <i class="fe fe-trash-2"></i>
                            </button>
                        </div>
                        <div class="card-body p-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Title</label>
                                <input type="text" name="sec2_items[${nextIndex}][title]"
                                    class="form-control" placeholder="e.g. Multi-language Support" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Description</label>
                              <input name="sec2_items[${nextIndex}][desc]" rows="2"
                                    class="form-control" placeholder="Short description..." required>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                container.insertAdjacentHTML('beforeend', html);
                nextIndex++;

                // Remove empty message if exists
                const emptyMsg = container.querySelector('.text-center.text-muted');
                if (emptyMsg) emptyMsg.remove();
            });


        // Remove with SweetAlert2
        container.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.remove-core-item');
            if (removeBtn) {
                const item = removeBtn.closest('.core-item');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        item.remove();

                        // Optional: success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Capability has been removed.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
        });
    </script>
@endpush
