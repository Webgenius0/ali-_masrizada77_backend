@extends('backend.app')

@section('content')
<style>
    .preview-box {
        width: 80px; height: 50px; object-fit: contain;
        border: 1px solid #ddd; background: #f9f9f9;
        border-radius: 4px; margin-top: 6px; display: block;
    }
    .icon-preview {
        width: 36px; height: 36px; object-fit: contain; margin-top: 4px;
    }
    .faq-item, .feat-item {
        position: relative;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        background: #fafafa;
        padding: 16px;
        margin-bottom: 12px;
    }
    .faq-item:hover, .feat-item:hover {
        border-color: #9ca3af;
    }
    .btn-remove {
        position: absolute;
        top: 10px; right: 10px;
        width: 32px; height: 32px;
        border-radius: 50%;
        background: #ef4444; color: white;
        border: none; font-size: 18px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; z-index: 10;
    }
    .btn-remove:hover { background: #dc2626; }
</style>

<div class="app-content main-content mt-0">
    <div class="side-app">

        <!-- Language Switcher -->
        <div class="page-header d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <h1 class="page-title">Infrastructure Management ({{ ucfirst($type ?? 'english') }})</h1>
            <div class="ms-auto">
                <div class="btn-group shadow-sm" role="group">
                    <a href="{{ route('admin.cms.home.infrastructure.index', ['type' => 'english']) }}"
                       class="btn {{ $type === 'english' ? 'btn-primary' : 'btn-outline-primary' }}">English</a>
                    <a href="{{ route('admin.cms.home.infrastructure.index', ['type' => 'de']) }}"
                       class="btn {{ $type === 'de' ? 'btn-danger' : 'btn-outline-danger' }}">German</a>
                    <!-- আরও ভাষা যোগ করতে চাইলে এখানে যোগ করো -->
                </div>
            </div>
        </div>

        <form action="{{ route('admin.cms.home.infrastructure.content') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <!-- 1. Top Header -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white"><h5>1. Top Header</h5></div>
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title</label>
                        <input type="text" name="sec1_title" class="form-control" value="{{ old('sec1_title', $data?->metadata['sec1_title'] ?? '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sub Title</label>
                        <input type="text" name="sec1_sub_title" class="form-control" value="{{ old('sec1_sub_title', $data?->metadata['sec1_sub_title'] ?? '') }}">
                    </div>
                </div>
            </div>

            <!-- 2. Categories -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white"><h5>2. Categories</h5></div>
                <div class="card-body row g-3">
                    @for($i = 0; $i < 3; $i++)
                    <div class="col-md-4">
                        <input type="text" name="sec2_data[{{$i}}][title]" class="form-control mb-2" placeholder="Title"
                               value="{{ old("sec2_data.$i.title", $data?->metadata['sec2_data'][$i]['title'] ?? '') }}">
                        <textarea name="sec2_data[{{$i}}][desc]" class="form-control mb-2" rows="2" placeholder="Description">
                            {{ old("sec2_data.$i.desc", $data?->metadata['sec2_data'][$i]['desc'] ?? '') }}
                        </textarea>
                        <input type="file" name="sec2_img_{{$i}}" class="form-control mb-2" onchange="livePreview(this, 'prev2_{{$i}}')">
                        <img id="prev2_{{$i}}" src="{{ asset($data?->metadata['sec2_images'][$i] ?? 'uploads/no_image.png') }}" class="preview-box">
                    </div>
                    @endfor
                </div>
            </div>

            <!-- 3. Detailed Features -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-warning text-dark"><h5>3. Detailed Features</h5></div>
                <div class="card-body">
                    @php $names = ['Cloud', 'Hybrid', 'On-Premise']; @endphp
                    @foreach($names as $key => $name)
                    <div class="p-4 border mb-4 bg-light rounded">
                        <h6 class="fw-bold mb-3 text-uppercase">{{ $name }}</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <input type="text" name="sec3_deployments[{{$key}}][title]" class="form-control" placeholder="Main Title"
                                       value="{{ old("sec3_deployments.$key.title", $data?->metadata['sec3_deployments'][$key]['title'] ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="sec3_deployments[{{$key}}][sub]" class="form-control" placeholder="Sub Title"
                                       value="{{ old("sec3_deployments.$key.sub", $data?->metadata['sec3_deployments'][$key]['sub'] ?? '') }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="sec3_deployments[{{$key}}][desc]" class="form-control" placeholder="Description"
                                       value="{{ old("sec3_deployments.$key.desc", $data?->metadata['sec3_deployments'][$key]['desc'] ?? '') }}">
                            </div>
                        </div>

                        <div id="feat-container-{{$key}}">
                            @php
                                $features = old("sec3_deployments.$key.features", $data?->metadata['sec3_deployments'][$key]['features'] ?? []);
                            @endphp
                            @foreach($features as $fIdx => $feat)
                            <div class="feat-item">
                                <button type="button" class="btn-remove remove-item">×</button>
                                <div class="row g-3 align-items-center">
                                    <div class="col-md-3">
                                        <input type="file" name="sec3_feat_img_{{$key}}_{{$fIdx}}" class="form-control form-control-sm" onchange="livePreview(this, 'prev3_{{$key}}_{{$fIdx}}')">
                                        <img id="prev3_{{$key}}_{{$fIdx}}" src="{{ asset($feat['icon'] ?? 'uploads/no_image.png') }}" class="icon-preview">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="sec3_deployments[{{$key}}][features][{{$fIdx}}][title]" class="form-control"
                                               value="{{ old("sec3_deployments.$key.features.$fIdx.title", $feat['title'] ?? '') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="sec3_deployments[{{$key}}][features][{{$fIdx}}][sub]" class="form-control"
                                               value="{{ old("sec3_deployments.$key.features.$fIdx.sub", $feat['sub'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-sm btn-dark mt-3 add-feat" data-id="{{$key}}">+ Add Feature</button>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- 4. Comparison Table -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">4. Comparison Table</h5>
                    <button type="button" id="add-table-row" class="btn btn-sm btn-success">+ Add Row</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 text-center">
                        <thead class="bg-light">
                            <tr>
                                <th>Feature</th>
                                <th>Cloud</th>
                                <th>Hybrid</th>
                                <th>On-Prem</th>
                                <th style="width:60px">Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @php $rows = old('table_rows', $data?->metadata['table_rows'] ?? []); @endphp
                            @foreach($rows as $idx => $row)
                            <tr class="table-row">
                                <td><input type="text" name="table_rows[{{$idx}}][f]" class="form-control" value="{{ old("table_rows.$idx.f", $row['f'] ?? '') }}"></td>
                                <td><input type="text" name="table_rows[{{$idx}}][c]" class="form-control" value="{{ old("table_rows.$idx.c", $row['c'] ?? '') }}"></td>
                                <td><input type="text" name="table_rows[{{$idx}}][h]" class="form-control" value="{{ old("table_rows.$idx.h", $row['h'] ?? '') }}"></td>
                                <td><input type="text" name="table_rows[{{$idx}}][o]" class="form-control" value="{{ old("table_rows.$idx.o", $row['o'] ?? '') }}"></td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-item">×</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 5. FAQ Management -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">5. FAQ Management</h5>
                    <button type="button" id="add-faq" class="btn btn-sm btn-info text-white">+ Add FAQ</button>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Section Title</label>
                            <input type="text" name="faq_title" class="form-control" value="{{ old('faq_title', $data?->metadata['faq_title'] ?? '') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Section Subtitle</label>
                            <input type="text" name="faq_sub_title" class="form-control" value="{{ old('faq_sub_title', $data?->metadata['faq_sub_title'] ?? '') }}">
                        </div>
                    </div>

                    <div id="faq-container">
                        @php $faqs = old('faqs', $data?->metadata['faqs'] ?? []); @endphp
                        @foreach($faqs as $index => $faq)
                        <div class="faq-item">
                            <button type="button" class="btn-remove remove-item">×</button>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-primary">Question</label>
                                <input type="text" name="faqs[{{ $index }}][q]" class="form-control" value="{{ old("faqs.$index.q", $faq['q'] ?? '') }}">
                            </div>
                            <div>
                                <label class="form-label fw-bold text-success">Answer</label>
                                <textarea name="faqs[{{ $index }}][a]" class="form-control" rows="3">{{ old("faqs.$index.a", $faq['a'] ?? '') }}</textarea>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="text-center mb-5">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow">SAVE ALL CHANGES</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// =============================================
// Live Preview
// =============================================
function livePreview(input, id) {
    if (!input.files?.[0]) return;
    const reader = new FileReader();
    reader.onload = e => document.getElementById(id).src = e.target.result;
    reader.readAsDataURL(input.files[0]);
}

// =============================================
// Add FAQ
// =============================================
document.getElementById('add-faq')?.addEventListener('click', () => {
    const container = document.getElementById('faq-container');
    const index = container.querySelectorAll('.faq-item').length;

    const div = document.createElement('div');
    div.className = 'faq-item';
    div.innerHTML = `
        <button type="button" class="btn-remove remove-item">×</button>
        <div class="mb-3">
            <label class="form-label fw-bold text-primary">Question</label>
            <input type="text" name="faqs[${index}][q]" class="form-control" placeholder="Enter question...">
        </div>
        <div>
            <label class="form-label fw-bold text-success">Answer</label>
            <textarea name="faqs[${index}][a]" class="form-control" rows="3" placeholder="Enter answer..."></textarea>
        </div>
    `;
    container.appendChild(div);
});

// =============================================
// Add Feature Item
// =============================================
document.querySelectorAll('.add-feat').forEach(btn => {
    btn.addEventListener('click', function() {
        const key = this.dataset.id;
        const container = document.getElementById(`feat-container-${key}`);
        const index = container.querySelectorAll('.feat-item').length;

        const div = document.createElement('div');
        div.className = 'feat-item';
        div.innerHTML = `
            <button type="button" class="btn-remove remove-item">×</button>
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <input type="file" name="sec3_feat_img_${key}_${index}" class="form-control form-control-sm" onchange="livePreview(this, 'prev3_${key}_${index}')">
                    <img id="prev3_${key}_${index}" src="{{ asset('uploads/no_image.png') }}" class="icon-preview">
                </div>
                <div class="col-md-4">
                    <input type="text" name="sec3_deployments[${key}][features][${index}][title]" class="form-control" placeholder="Feature Title">
                </div>
                <div class="col-md-4">
                    <input type="text" name="sec3_deployments[${key}][features][${index}][sub]" class="form-control" placeholder="Feature Sub">
                </div>
            </div>
        `;
        container.appendChild(div);
    });
});

// =============================================
// Add Table Row
// =============================================
document.getElementById('add-table-row')?.addEventListener('click', () => {
    const tbody = document.getElementById('table-body');
    const index = tbody.querySelectorAll('tr').length;

    const tr = document.createElement('tr');
    tr.className = 'table-row';
    tr.innerHTML = `
        <td><input type="text" name="table_rows[${index}][f]" class="form-control" placeholder="Feature"></td>
        <td><input type="text" name="table_rows[${index}][c]" class="form-control" placeholder="Cloud"></td>
        <td><input type="text" name="table_rows[${index}][h]" class="form-control" placeholder="Hybrid"></td>
        <td><input type="text" name="table_rows[${index}][o]" class="form-control" placeholder="On-Prem"></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-item">×</button></td>
    `;
    tbody.appendChild(tr);
});

// =============================================
// Remove Item (with animation & confirmation)
// =============================================
document.addEventListener('click', e => {
    if (!e.target.classList.contains('remove-item')) return;

    const item = e.target.closest('.faq-item, .feat-item, .table-row');

    if (!item) return;

    Swal.fire({
        title: 'Are you sure?',
        text: "This item will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then(result => {
        if (result.isConfirmed) {
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '0';
            item.style.transform = 'translateY(-10px)';
            setTimeout(() => item.remove(), 350);
        }
    });
});
</script>
@endpush
@endsection
