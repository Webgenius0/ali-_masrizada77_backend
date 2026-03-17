@extends('backend.app')

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="page-header d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <h1 class="page-title text-primary fw-bold">Drive ThruAI
                <span class="text-muted small">({{ ucfirst($selected_type ?? 'english') }})</span>
            </h1>
            <div class="ms-auto">
                <div class="btn-group p-1 bg-white border shadow-sm" role="group">
                    <a href="{{ route('admin.cms.home.drive_thruai.index', ['type' => 'english']) }}"
                        class="btn {{ ($selected_type ?? 'english') == 'english' ? 'btn-primary' : 'btn-light' }} px-4">
                        English
                    </a>
                    <a href="{{ route('admin.cms.home.drive_thruai.index', ['type' => 'de']) }}"
                        class="btn {{ ($selected_type ?? '') == 'de' ? 'btn-danger' : 'btn-light' }} px-4">
                        Other Language
                    </a>
                </div>
            </div>
        </div>

        <div class="main-container container-fluid">
            <form action="{{ route('admin.cms.home.drive_thruai.content') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $selected_type ?? 'english' }}">

                {{-- SECTION 1: Hero Section --}}
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-header bg-dark text-white rounded-top">
                        <h5 class="mb-0 card-title"><i class="fe fe-layout me-2 text-warning"></i>1. Hero Section Content</h5>
                    </div>
                    <div class="card-body border">
                        <div class="row g-4">
                            <div class="col-md-7 border-end">
                                <div class="row g-3">
                                    <div class="col-12"><x-form.text name="sec1_title" label="Main Headline" :value="$data->metadata['sec1_title'] ?? ''" /></div>
                                    <div class="col-12"><x-form.text name="sec1_desc" label="Sub-headline Description" :value="$data->metadata['sec1_desc'] ?? ''" /></div>
                                    <div class="col-md-6"><x-form.text name="sec1_url_title" label="CTA Button Title" :value="$data->metadata['sec1_url_title'] ?? ''" /></div>
                                    <div class="col-md-6"><x-form.text name="sec1_url_link" label="CTA Button Link" :value="$data->metadata['sec1_url_link'] ?? ''" /></div>
                                </div>
                            </div>
                            <div class="col-md-5 bg-light-50">
                                <x-form.file name="video_file" label="Hero Background Video (MP4 Format)" accept="video/mp4" />
                                @if (!empty($data->video))
                                    <div class="mt-4 border overflow-hidden shadow-sm">
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

                {{-- SECTION 2: Convert Conversation --}}
                <div class="card border-0 shadow-sm mb-5">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title"><i class="fe fe-zap me-2"></i>2. Convert Conversation (Dynamic List)</h5>
                        <button type="button" class="btn btn-sm btn-white text-primary fw-bold shadow-sm" id="add-capability-item">
                            <i class="fe fe-plus-circle me-1"></i> Add Capability
                        </button>
                    </div>
                    <div class="card-body border">
                        <div class="row g-4">
                            <div class="col-md-8 border-end">
                                <div id="capability-items-container">
                                    @php $items = $data->metadata['sec2_items'] ?? []; @endphp
                                    @forelse ($items as $index => $item)
                                        <div class="capability-item mb-4 p-4 border shadow-none bg-white position-relative hover-shadow transition">
                                            <button type="button" class="btn btn-outline-danger btn-sm position-absolute remove-item" style="top: 15px; right: 15px;" title="Delete Item">
                                                <i class="fe fe-trash-2"></i>
                                            </button>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar avatar-sm bg-primary-transparent text-primary rounded-circle me-2 fw-bold">{{ $loop->iteration }}</div>
                                                <h6 class="mb-0 fw-bold text-dark text-uppercase small">Capability Detail</h6>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <label class="form-label fw-semibold small text-muted">Title</label>
                                                    <input type="text" name="sec2_items[{{ $index }}][title]" class="form-control form-control-lg border-faded" value="{{ $item['title'] ?? '' }}" placeholder="e.g., Enterprise Security">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label fw-semibold small text-muted">Description</label>
                                                    <textarea name="sec2_items[{{ $index }}][desc]" class="form-control border-faded" rows="3" placeholder="Briefly explain this...">{{ $item['desc'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5 empty-msg border border-dashed bg-light">
                                            <i class="fe fe-database text-muted display-6"></i>
                                            <p class="text-muted mt-2">No capabilities defined yet. Click "Add Capability" to start.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="sticky-top" style="top: 20px;">
                                    <div class="alert bg-primary-transparent text-primary border-primary p-3 mb-4">
                                        <h6 class="fw-bold mb-1"><i class="fe fe-info me-2"></i>Side Info</h6>
                                        <p class="small mb-0">These values appear on the right side of the capability list section.</p>
                                    </div>
                                    <x-form.text name="sec2_right_title" label="Main Section Title" :value="$data->metadata['sec2_right_title'] ?? ''" />
                                    <x-form.text name="sec2_right_desc" label="Main Section Description" :value="$data->metadata['sec2_right_desc'] ?? ''" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3 & 4 --}}
                <div class="row mb-5 g-4">
                    {{-- SECTION 3 --}}
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0 card-title"><i class="fe fe-activity me-2"></i>3. Automated Customer Response</h5>
                            </div>
                            <div class="card-body border">
                                <x-form.text name="sec3_title" label="Section Title" :value="$data->metadata['sec3_title'] ?? ''" />
                                <x-form.text name="sec3_desc" label="Description" :value="$data->metadata['sec3_desc'] ?? ''" />
                                <x-form.file name="sec3_image" label="Feature Image" accept="image/*" help="PNG, JPG, WebP — recommended 1200×600 or larger" />
                                @if (!empty($data->image4))
                                    <div class="mt-3">
                                        <label class="form-label small text-muted d-block mb-2">Current Feature Image:</label>
                                        <img src="{{ asset($data->image4) }}" alt="Preview" class="img-fluid shadow-sm border" style="max-height: 240px; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="mt-3 p-3 bg-light rounded text-center text-muted small">No image uploaded yet.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 4 --}}
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fe fe-link me-2"></i>4. More Than Just a Message</h5>
                                <button type="button" id="add-sec4-item" class="btn btn-sm btn-light">
                                    <i class="fe fe-plus"></i> Add Feature
                                </button>
                            </div>
                            <div class="card-body">
                                <x-form.text name="sec4_title" label="Main Title" :value="$data->metadata['sec4_title'] ?? ''" />
                                <x-form.text name="sec4_subtitle" label="Subtitle" :value="$data->metadata['sec4_subtitle'] ?? ''" />
                                <div id="sec4-items-container" class="mt-4">
                                    @php $features = $data->metadata['sec4_features'] ?? []; @endphp
                                    @forelse($features as $index => $feature)
                                        <div class="sec4-item card mb-3 border shadow-none position-relative" data-index="{{ $index }}">
                                            <button type="button" class="btn btn-sm btn-outline-danger position-absolute remove-sec4-item" style="top: 10px; right: 10px; z-index:10;">
                                                <i class="fe fe-trash-2"></i>
                                            </button>
                                            <div class="card-body p-3">
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-bold">Icon Image</label>
                                                        <x-form.file name="sec4_features[{{ $index }}][icon_image]" class="form-control-sm" accept="image/*" />
                                                        @if (!empty($feature['icon_image']))
                                                            <img src="{{ asset($feature['icon_image']) }}" alt="Icon" class="img-thumbnail mt-2" style="max-height: 60px;">
                                                        @endif
                                                        <input type="hidden" name="sec4_features[{{ $index }}][old_icon_image]" value="{{ $feature['icon_image'] ?? '' }}">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label class="form-label small fw-bold">Feature Title</label>
                                                        <input type="text" name="sec4_features[{{ $index }}][title]" class="form-control" value="{{ $feature['title'] ?? '' }}" required>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label small fw-bold">Description</label>
                                                        <textarea name="sec4_features[{{ $index }}][content]" class="form-control" rows="2">{{ $feature['content'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center text-muted py-5 empty-msg border border-dashed bg-light">
                                            <i class="fe fe-folder-open text-muted display-6"></i>
                                            <p class="mt-2">No features added yet.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-lg bg-white sticky-bottom rounded-0 border-top py-3" style="margin-left: -25px; margin-right: -25px; bottom: 0; z-index: 1020;">
                    <div class="container-fluid d-flex justify-content-center">
                        <button type="submit" class="btn btn-success btn-lg px-6 shadow fw-bold rounded-pill hvr-grow">
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
        const toast = (title) => Swal.fire({ icon: 'success', title, toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });

        const confirmDelete = (callback) => {
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete'
            }).then((res) => { if(res.isConfirmed) callback(); });
        };

        // Section 2 Logic
        const capContainer = document.getElementById('capability-items-container');
        document.getElementById('add-capability-item').addEventListener('click', () => {
            const index = capContainer.querySelectorAll('.capability-item').length;
            if (capContainer.querySelector('.empty-msg')) capContainer.innerHTML = '';

            const html = `
                <div class="capability-item mb-4 p-4 border bg-white position-relative hover-shadow transition">
                    <button type="button" class="btn btn-outline-danger btn-sm position-absolute remove-item" style="top: 15px; right: 15px; z-index:10;"><i class="fe fe-trash-2"></i></button>
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm bg-primary-transparent text-primary rounded-circle me-2 fw-bold">${index + 1}</div>
                        <h6 class="mb-0 fw-bold text-dark text-uppercase small">New Capability</h6>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3"><input type="text" name="sec2_items[${index}][title]" class="form-control border-faded" placeholder="Title"></div>
                        <div class="col-12"><textarea name="sec2_items[${index}][desc]" class="form-control border-faded" rows="3" placeholder="Description"></textarea></div>
                    </div>
                </div>`;
            capContainer.insertAdjacentHTML('beforeend', html);
            toast('Capability Added!');
        });

        capContainer.addEventListener('click', (e) => {
            const btn = e.target.closest('.remove-item');
            if (btn) confirmDelete(() => {
                btn.closest('.capability-item').remove();
                reindex('.capability-item', 'sec2_items');
            });
        });

        // Section 4 Logic
        const sec4Container = document.getElementById('sec4-items-container');
        document.getElementById('add-sec4-item').addEventListener('click', () => {
            const index = sec4Container.querySelectorAll('.sec4-item').length;
            if (sec4Container.querySelector('.empty-msg')) sec4Container.innerHTML = '';

            const html = `
                <div class="sec4-item card mb-3 border position-relative">
                    <button type="button" class="btn btn-sm btn-outline-danger position-absolute remove-sec4-item" style="top: 10px; right: 10px; z-index:10;"><i class="fe fe-trash-2"></i></button>
                    <div class="card-body p-3">
                        <div class="row g-3">
                            <div class="col-md-4"><input type="file" name="sec4_features[${index}][icon_image]" class="form-control form-control-sm"></div>
                            <div class="col-md-8"><input type="text" name="sec4_features[${index}][title]" class="form-control" placeholder="Title"></div>
                            <div class="col-12"><textarea name="sec4_features[${index}][content]" class="form-control" rows="2" placeholder="Content"></textarea></div>
                        </div>
                    </div>
                </div>`;
            sec4Container.insertAdjacentHTML('beforeend', html);
            toast('Feature Added!');
        });

        sec4Container.addEventListener('click', (e) => {
            const btn = e.target.closest('.remove-sec4-item');
            if (btn) confirmDelete(() => {
                btn.closest('.sec4-item').remove();
                reindex('.sec4-item', 'sec4_features');
            });
        });

        function reindex(selector, nameKey) {
            document.querySelectorAll(selector).forEach((item, idx) => {
                const badge = item.querySelector('.avatar');
                if (badge) badge.textContent = idx + 1;
                item.querySelectorAll('input, textarea').forEach(el => {
                    if (el.name) el.name = el.name.replace(/\[\d+\]/, `[${idx}]`);
                });
            });
        }
    });
</script>

<style>
    .border-faded { border-color: #e9ebfa !important; }
    .capability-item, .sec4-item { transition: all 0.3s ease; border-radius: 8px; }
    .capability-item:hover, .sec4-item:hover { border-color: #705ec8 !important; box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important; }
    .hvr-grow:hover { transform: scale(1.02); }
    .avatar.avatar-sm { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; }
</style>
@endpush
