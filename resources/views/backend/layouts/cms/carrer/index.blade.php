@extends('backend.app')

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <h1 class="page-title">Career Page Management ({{ ucfirst($type) }})</h1>
                <div class="btn-group p-1 bg-white border shadow-sm">
                    <a href="{{ route('admin.cms.home.career.index', ['type' => 'english']) }}" class="btn {{ $type == 'english' ? 'btn-primary' : 'btn-light' }}">English</a>
                    <a href="{{ route('admin.cms.home.career.index', ['type' => 'de']) }}" class="btn {{ $type == 'de' ? 'btn-danger' : 'btn-light' }}">German</a>
                </div>
            </div>

            <form action="{{ route('admin.cms.home.career.content') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                {{-- Section 1: Hero Section --}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white"><h5>1. Hero Section (Game Changer Area)</h5></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <x-form.text name="hero_title" label="Main Heading" :value="$data->metadata['hero_title'] ?? ''" />
                                <div class="mt-3">
                                    <label class="form-label fw-bold">Description Text</label>
                                    <textarea name="hero_desc" class="form-control" rows="3">{{ $data->metadata['hero_desc'] ?? '' }}</textarea>
                                </div>
                                <div class="mt-3">
                                    <x-form.text name="hero_btn_text" label="Button Label" :value="$data->metadata['hero_btn_text'] ?? ''" />
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <x-form.file name="hero_image" label="Side Image" />
                                <img src="{{ asset($data->metadata['hero_image'] ?? 'backend/images/no-image.png') }}" class="preview-img mt-2 border w-100" style="height:150px; object-fit:cover;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Job Role --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white d-flex align-items-center">
                        <i class="fas fa-briefcase me-2"></i>
                        <h5 class="mb-0">2. Job Role Section</h5>
                    </div>
                    <div class="card-body">
                        <label class="form-label fw-bold text-muted">Job Heading</label>
                        <x-form.text name="job_heading" :value="$data->metadata['job_heading'] ?? ''" placeholder="Enter job section heading..." />
                    </div>
                </div>

                {{-- Section 3: Stats & Interaction --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-dark text-white d-flex align-items-center">
                        <i class="fas fa-chart-line me-2"></i>
                        <h5 class="mb-0">3. Customer Interactions & Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="row border-bottom pb-4 mb-4 align-items-end">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold text-muted">Interaction Heading</label>
                                    <x-form.text name="stats_title" :value="$data->metadata['stats_title'] ?? ''" placeholder="Enter heading..." />
                                </div>
                                <div class="form-group">
                                    <label class="form-label fw-bold text-muted">Long Description</label>
                                    <textarea name="stats_desc" class="form-control" rows="4" placeholder="Write detailed description here...">{{ $data->metadata['stats_desc'] ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label fw-bold text-muted">Section Image</label>
                                    <x-form.file name="stats_image" />
                                    <div class="mt-2">
                                        <img src="{{ asset($data->metadata['stats_image'] ?? 'backend/images/no-image.png') }}"
                                             class="preview-img img-thumbnail w-100 shadow-sm"
                                             style="height:145px; object-fit:cover;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Stats Counters --}}
<div class="bg-light p-3 shadow-sm border-top border-primary border-3">
    <div class="row">
        {{-- Overall Section Title --}}
        <div class="col-md-12 mb-3">
            <label class="form-label fw-bold text-primary">Statistics Counter Main Title</label>
            <input type="text" name="stat_emp_title" class="form-control fw-bold"
                   placeholder="e.g. Our Global Impact" value="{{ $data->metadata['stat_emp_title'] ?? '' }}">
        </div>

        {{-- Card 1: Employees --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 text-center">
                    <label class="small fw-bold text-muted mb-1">Counter Label 1</label>
                    {{-- এই ইনপুটটি এখন লেবেল সেভ করবে (যেমন: Total Employees) --}}
                    <input type="text" name="stat_emp_label" class="form-control form-control-sm mb-2 text-center border-primary-subtle"
                           placeholder="e.g. Total Employees" value="{{ $data->metadata['stat_emp_label'] ?? 'Total Employees' }}">

                    <input type="number" name="stat_emp" class="form-control text-center fw-bold text-primary"
                           placeholder="250" value="{{ $data->metadata['stat_emp'] ?? '' }}">

                    <input type="text" name="stat_emp_desc" class="form-control form-control-sm mt-2"
                           placeholder="Short Description" value="{{ $data->metadata['stat_emp_desc'] ?? '' }}">
                </div>
            </div>
        </div>

        {{-- Card 2: Working Hours --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 text-center">
                    <label class="small fw-bold text-muted mb-1">Counter Label 2</label>
                    {{-- এই ইনপুটটি এখন লেবেল সেভ করবে (যেমন: Working Hours) --}}
                    <input type="text" name="stat_hours_label" class="form-control form-control-sm mb-2 text-center border-success-subtle"
                           placeholder="e.g. Working Hours" value="{{ $data->metadata['stat_hours_label'] ?? 'Working Hours' }}">

                    <input type="number" name="stat_hours" class="form-control text-center fw-bold text-success"
                           placeholder="500" value="{{ $data->metadata['stat_hours'] ?? '' }}">

                    <input type="text" name="stat_hours_desc" class="form-control form-control-sm mt-2"
                           placeholder="Short Description" value="{{ $data->metadata['stat_hours_desc'] ?? '' }}">
                </div>
            </div>
        </div>

        {{-- Card 3: Global Offices --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3 text-center">
                    <label class="small fw-bold text-muted mb-1">Counter Label 3</label>
                    {{-- এই ইনপুটটি এখন লেবেল সেভ করবে (যেমন: Global Offices) --}}
                    <input type="text" name="stat_offices_label" class="form-control form-control-sm mb-2 text-center border-info-subtle"
                           placeholder="e.g. Global Offices" value="{{ $data->metadata['stat_offices_label'] ?? 'Global Offices' }}">

                    <input type="number" name="stat_offices" class="form-control text-center fw-bold text-info"
                           placeholder="20" value="{{ $data->metadata['stat_offices'] ?? '' }}">

                    <input type="text" name="stat_offices_desc" class="form-control form-control-sm mt-2"
                           placeholder="Short Description" value="{{ $data->metadata['stat_offices_desc'] ?? '' }}">
                </div>
            </div>
        </div>
    </div>
</div>
                    </div>
                </div>

                {{-- Section 4: Benefits Accordion --}}
                {{-- <div class="card shadow mb-4">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">4. Unlock Your Benefits (Accordion List)</h5>
                        <button type="button" class="btn btn-sm btn-light add-benefit-row"><i class="fas fa-plus"></i> Add Benefit</button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6"><x-form.text name="benefits_title" label="Section Title" :value="$data->metadata['benefits_title'] ?? ''" /></div>
                            <div class="col-md-6"><x-form.text name="benefits_footer" label="Bottom Footer Text" :value="$data->metadata['benefits_footer'] ?? ''" /></div>
                        </div>
                        <div id="benefits-wrapper">
                            @if(isset($data->metadata['benefits_list']))
                                @foreach($data->metadata['benefits_list'] as $key => $benefit)
                                <div class="benefit-item border-start border-info border-4 p-3 mb-3 bg-light shadow-sm">
                                    <div class="d-flex justify-content-between mb-2">
                                        <h6 class="fw-bold">Benefit #{{ $key + 1 }}</h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-item">×</button>
                                    </div>
                                    <input type="text" name="benefits_list[{{$key}}][title]" class="form-control mb-2" placeholder="Title" value="{{ $benefit['title'] ?? '' }}">
                                    <textarea name="benefits_list[{{$key}}][desc]" class="form-control" placeholder="Description">{{ $benefit['desc'] ?? '' }}</textarea>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div> --}}

                <div class="card mb-5 shadow">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-success btn-lg w-50">SAVE CAREER DATA</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Image Preview Global
        $(document).on('change', 'input[type="file"]', function() {
            let reader = new FileReader();
            let preview = $(this).closest('div').find('.preview-img');
            if(preview.length === 0) {
                 preview = $(this).closest('.row').find('.preview-img');
            }
            reader.onload = (e) => { preview.attr('src', e.target.result); }
            reader.readAsDataURL(this.files[0]);
        });

        // Add Benefit Row
        $('.add-benefit-row').click(function() {
            let index = $('.benefit-item').length;
            let html = `
                <div class="benefit-item border-start border-info border-4 p-3 mb-3 bg-light shadow-sm" style="display:none;">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-bold text-info">New Benefit</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item">×</button>
                    </div>
                    <input type="text" name="benefits_list[${index}][title]" class="form-control mb-2" placeholder="e.g. Career Development">
                    <textarea name="benefits_list[${index}][desc]" class="form-control" placeholder="Description..."></textarea>
                </div>`;
            $('#benefits-wrapper').append(html);
            $('.benefit-item').last().fadeIn(300);
            reIndexBenefits();
        });

        // SweetAlert Delete Confirmation
        $(document).on('click', '.remove-item', function() {
            let item = $(this).closest('.benefit-item');
            Swal.fire({
                title: 'Are you sure?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    item.fadeOut(300, function() {
                        $(this).remove();
                        reIndexBenefits();
                    });
                }
            });
        });

        function reIndexBenefits() {
            $('.benefit-item').each(function(index) {
                $(this).find('input').attr('name', `benefits_list[${index}][title]`);
                $(this).find('textarea').attr('name', `benefits_list[${index}][desc]`);
                $(this).find('h6').first().text(`Benefit #${index + 1}`);
            });
        }
    });
</script>
@endpush
