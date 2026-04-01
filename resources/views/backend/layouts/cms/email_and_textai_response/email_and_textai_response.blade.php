@extends('backend.app')

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">

            <div class="page-header d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <h1 class="page-title text-primary fw-bold">Email_and_Text AI_response Overview <span
                        class="text-muted small">({{ ucfirst($selected_type ?? 'english') }})</span></h1>
                <div class="ms-auto">
                    <div class="btn-group p-1 bg-white border  shadow-sm" role="group">
                        <a href="{{ route('admin.cms.home.emailandtextai.index', ['type' => 'english']) }}"
                            class="btn {{ ($selected_type ?? 'english') == 'english' ? 'btn-primary' : 'btn-light' }} px-4">
                            English
                        </a>
                        <a href="{{ route('admin.cms.home.emailandtextai.index', ['type' => 'de']) }}"
                            class="btn {{ ($selected_type ?? '') == 'de' ? 'btn-danger' : 'btn-light' }} px-4">
                            Other Language
                        </a>
                    </div>
                </div>
            </div>

            <div class="main-container container-fluid">
                <form action="{{ route('admin.cms.home.emailandtextai.content') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="{{ $selected_type ?? 'english' }}">

                    {{-- SECTION 1: Hero Section --}}
                    <div class="card border-0 shadow-sm mb-5">
                        <div class="card-header bg-dark text-white rounded-top">
                            <h5 class="mb-0 card-title"><i class="fe fe-layout me-2 text-warning"></i>1. Hero Section
                                Content</h5>
                        </div>
                        <div class="card-body border">
                            <div class="row g-4">
                                <div class="col-md-7 border-end">
                                    <div class="row g-3">
                                        <div class="col-12"><x-form.text name="sec1_title" label="Main Headline"
                                                :value="$data->metadata['sec1_title'] ?? ''" /></div>
                                        <div class="col-12"><x-form.text name="sec1_desc" label="Sub-headline Description"
                                                :value="$data->metadata['sec1_desc'] ?? ''" /></div>
                                        <div class="col-md-6"><x-form.text name="sec1_url_title" label="CTA Button Title"
                                                :value="$data->metadata['sec1_url_title'] ?? ''" /></div>
                                        <div class="col-md-6"><x-form.text name="sec1_url_link" label="CTA Button Link"
                                                :value="$data->metadata['sec1_url_link'] ?? ''" /></div>
                                    </div>
                                </div>
                                <div class="col-md-5 bg-light-50">
                                    <x-form.file name="video_file" label="Hero Background Video (MP4 Format)" />
                                    @if (!empty($data->video))
                                        <div class="mt-4  border overflow-hidden shadow-sm">
                                            <div class="bg-secondary p-1 text-white small px-3">Current Video Preview</div>
                                            <video width="100%" height="auto" controls class="bg-black">
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
                    <div class="card border-0 shadow-sm mb-5">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 card-title"><i class="fe fe-zap me-2"></i>2. Convert Conversition (Dynamic List)
                            </h5>
                            <button type="button" class="btn btn-sm btn-white text-primary fw-bold shadow-sm"
                                id="add-capability-item">
                                <i class="fe fe-plus-circle me-1"></i> Add Capability
                            </button>
                        </div>
                        <div class="card-body border">
                            <div class="row g-4">
                                <div class="col-md-8 border-end">
                                    <div id="capability-items-container">
                                        @php $items = $data->metadata['sec2_items'] ?? []; @endphp

                                        @forelse ($items as $index => $item)
                                            <div
                                                class="capability-item mb-4 p-4 border shadow-none bg-white position-relative hover-shadow transition">
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-sm position-absolute remove-item"
                                                    style="top: 15px; right: 15px;" title="Delete Item">
                                                    <i class="fe fe-trash-2"></i>
                                                </button>
                                                <div class="d-flex align-items-center mb-3">
                                                    <div
                                                        class="avatar avatar-sm bg-primary-transparent text-primary rounded-circle me-2 fw-bold">
                                                        {{ $loop->iteration }}</div>
                                                    <h6 class="mb-0 fw-bold text-dark text-uppercase small">Capability
                                                        Detail</h6>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12 mb-3">
                                                        <label class="form-label fw-semibold small text-muted">Title</label>
                                                        <input type="text" name="sec2_items[{{ $index }}][title]"
                                                            class="form-control form-control-lg border-faded"
                                                            value="{{ $item['title'] ?? '' }}"
                                                            placeholder="e.g., Enterprise Security">
                                                    </div>
                                                    <div class="col-12">
                                                        <label
                                                            class="form-label fw-semibold small text-muted">Description</label>
                                                        <textarea name="sec2_items[{{ $index }}][desc]" class="form-control border-faded" rows="2"
                                                            placeholder="Briefly explain this capability...">{{ $item['desc'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-5 empty-msg border border-dashed bg-light">
                                                <i class="fe fe-database text-muted display-6"></i>
                                                <p class="text-muted mt-2">No capabilities defined yet. Click "Add
                                                    Capability" to start.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="sticky-top" style="top: 20px;">
                                        <div class="alert bg-primary-transparent text-primary border-primary p-3 mb-4">
                                            <h6 class="fw-bold mb-1 font-weight-bold"><i class="fe fe-info me-2"></i>Side
                                                Info</h6>
                                            <p class="small mb-0">These values appear on the right side of the capability
                                                list section.</p>
                                        </div>
                                        <x-form.text name="sec2_right_title" label="Main Section Title" :value="$data->metadata['sec2_right_title'] ?? ''" />
                                        <x-form.text name="sec2_right_desc" label="Main Section Description"
                                            :value="$data->metadata['sec2_right_desc'] ?? ''" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3 & 4 (Combined for brevity) --}}
                    <div class="row mb-5">
                        {{-- SECTION 3: Control & Optimize --}}
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0 card-title"><i class="fe fe-activity me-2"></i>3. Automatie Customer
                                        response
                                    </h5>
                                </div>
                                <div class="card-body border">
                                    <x-form.text name="sec3_title" label="Section Title" :value="$data->metadata['sec3_title'] ?? ''" />

                                    <x-form.text name="sec3_desc" label="Description" :value="$data->metadata['sec3_desc'] ?? ''" />

                                    <x-form.file name="sec3_image" label="Feature Image" accept="image/*"
                                        help="PNG, JPG, png,jpeg — recommended 1200×600 or larger" />

                                    @if (!empty($data->image4))
                                        <div class="mt-3">
                                            <label class="form-label small text-muted d-block mb-2">Current Feature
                                                Image:</label>
                                            <img src="{{ asset($data->image4) }}" alt="Section 3 Feature Preview"
                                                class="img-fluid  shadow-sm border"
                                                style="max-height: 240px; object-fit: cover; width: auto; display: block;">
                                        </div>
                                    @else
                                        <div class="mt-3 p-3 bg-light rounded text-center text-muted small">
                                            No image uploaded yet for this section
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 4: Connected Across --}}
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0 card-title"><i class="fe fe-link me-2"></i>4. More Then Just a Messgae
                                    </h5>
                                </div>
                                <div class="card-body border text-center">
                                    <x-form.text name="sec4_title" label="Main Title" :value="$data->metadata['sec4_title'] ?? ''" />
                                    <x-form.text name="sec4_subtitle" label="Subtitle" :value="$data->metadata['sec4_subtitle'] ?? ''" />

                                    <div class="form-group mt-3">
                                        <label class="form-label">Section 4 Features (Multiple)</label>
                                        <div id="sec4-features-container">
                                            @php
                                                $features = $data->metadata['sec4_features'] ?? [''];
                                            @endphp
                                            @foreach ($features as $feature)
                                                <div class="input-group mb-2">
                                                    <input type="text" name="sec4_features[]" class="form-control"
                                                        value="{{ $feature }}" placeholder="Enter feature text">
                                                    <button type="button"
                                                        class="btn btn-danger remove-feature">X</button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary mt-2" id="add-feature">
                                            <i class="fe fe-plus"></i> Add More Feature
                                        </button>
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
                    </div>

                    <div class="card border-0 shadow-lg bg-white sticky-bottom rounded-0 border-top py-3"
                        style="margin-left: -25px; margin-right: -25px; bottom: 0; z-index: 1020;">
                        <div class="container-fluid d-flex justify-content-center">
                            <button type="submit"
                                class="btn btn-success btn-lg px-6 shadow fw-bold rounded-pill hvr-grow">
                                <i class="fe fe-check-circle me-2"></i> SAVE CHANGES
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('capability-items-container');
            const addButton = document.getElementById('add-capability-item');

            // --- Section 2: Add Capability ---
            addButton.addEventListener('click', function() {
                const index = container.querySelectorAll('.capability-item').length;
                const emptyMsg = container.querySelector('.empty-msg');
                if (emptyMsg) emptyMsg.remove();

                const html = `
                <div class="capability-item mb-4 border bg-white position-relative shadow-none animate__animated animate__fadeInUp">
                    <button type="button" class="btn btn-outline-danger btn-sm position-absolute remove-item" style="top: 15px; right: 15px;">
                        <i class="fe fe-trash-2"></i>
                    </button>
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm bg-primary text-white rounded-circle me-2 fw-bold small">${index + 1}</div>
                        <h6 class="mb-0 fw-bold text-dark text-uppercase small">New Capability Detail</h6>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold small text-muted">Title</label>
                            <input type="text" name="sec2_items[${index}][title]" class="form-control form-control-lg border-faded" placeholder="Enter title...">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-muted">Description</label>
                            <textarea name="sec2_items[${index}][desc]" class="form-control border-faded" rows="3" placeholder="Briefly explain this..."></textarea>
                        </div>
                    </div>
                </div>`;
                container.insertAdjacentHTML('beforeend', html);
            });

            // --- Section 2: Remove with SweetAlert2 ---
            container.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-item');
                if (removeBtn) {
                    const item = removeBtn.closest('.capability-item');
                    confirmDelete(() => {
                        item.remove();
                        reindexItems();
                    });
                }
            });

            function reindexItems() {
                const items = container.querySelectorAll('.capability-item');
                items.forEach((item, idx) => {
                    const badge = item.querySelector('.avatar');
                    if (badge) badge.innerText = idx + 1;
                    const titleInput = item.querySelector('input[name*="[title]"]');
                    const descInput = item.querySelector('textarea[name*="[desc]"]');
                    if (titleInput) titleInput.name = `sec2_items[${idx}][title]`;
                    if (descInput) descInput.name = `sec2_items[${idx}][desc]`;
                });
            }

            // --- Section 4: Add More Feature ---
            document.getElementById('add-feature').addEventListener('click', function() {
                const featContainer = document.getElementById('sec4-features-container');
                const div = document.createElement('div');
                div.className = 'input-group mb-2 animate__animated animate__fadeInIn';
                div.innerHTML = `
                    <input type="text" name="sec4_features[]" class="form-control" placeholder="Enter feature text">
                    <button type="button" class="btn btn-danger remove-feature">X</button>
                `;
                featContainer.appendChild(div);
            });

            // --- Section 4: Remove Feature with SweetAlert2 ---
            document.addEventListener('click', function(e) {
                const removeBtn = e.target.closest('.remove-feature');
                if (removeBtn) {
                    const row = removeBtn.parentElement;
                    confirmDelete(() => {
                        row.remove();
                    });
                }
            });

            /**
             * Common SweetAlert Confirmation
             */
            function confirmDelete(callback) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This item will be removed from the list!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        callback();
                        Swal.fire({
                            icon: 'success',
                            title: 'Removed!',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    </script>
