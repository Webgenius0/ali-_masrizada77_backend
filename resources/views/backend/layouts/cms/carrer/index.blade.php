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

                {{-- Section 1: Hero (Image 1 reference) --}}
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
                                {{-- <div class="mt-3">
                                    <x-form.text name="hero_btn_text" label="Button Label" :value="$data->metadata['hero_btn_text'] ?? ''" />
                                </div> --}}
                            </div>
                            <div class="col-md-4 text-center">
                                <x-form.file name="hero_image" label="Side Image" />
                                <img src="{{ asset($data->metadata['hero_image'] ?? 'backend/images/no-image.png') }}" class="preview-img mt-2 border w-100" style="height:150px; object-fit:cover;">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 2: Stats & Interaction (Image 2 reference) --}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-dark text-white"><h5>2. Customer Interactions & Stats</h5></div>
                    <div class="card-body">
                        <div class="row border-bottom pb-4 mb-4">
                            <div class="col-md-8">
                                <x-form.text name="stats_title" label="Interaction Heading" :value="$data->metadata['stats_title'] ?? ''" />
                                <textarea name="stats_desc" class="form-control mt-2" placeholder="Long description...">{{ $data->metadata['stats_desc'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <x-form.file name="stats_image" label="Right Image" />
                                <img src="{{ asset($data->metadata['stats_image'] ?? 'backend/images/no-image.png') }}" class="preview-img mt-2 border w-100" style="height:100px; object-fit:cover;">
                            </div>
                        </div>
                        <div class="row text-center mb-3">
                            <div class="col-md-12">
                                <input type="text" name="stat_emp_title" class="form-control fw-bold" placeholder="Write Title " value="{{ $data->metadata['stat_emp_title'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="stat_emp" class="form-control fw-bold" placeholder="250+" value="{{ $data->metadata['stat_emp'] ?? '' }}">
                                <input type="text" name="stat_emp_desc" class="form-control mt-1 small" placeholder="Label (e.g. Employees)" value="{{ $data->metadata['stat_emp_desc'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="stat_hours" class="form-control fw-bold" placeholder="50k+" value="{{ $data->metadata['stat_hours'] ?? '' }}">
                                <input type="text" name="stat_hours_desc" class="form-control mt-1 small" placeholder="Label (e.g. Hours)" value="{{ $data->metadata['stat_hours_desc'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="stat_offices" class="form-control fw-bold" placeholder="20+" value="{{ $data->metadata['stat_offices'] ?? '' }}">
                                <input type="text" name="stat_offices_desc" class="form-control mt-1 small" placeholder="Label (e.g. Offices)" value="{{ $data->metadata['stat_offices_desc'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section 3: Benefits Accordion (Image 3 reference) --}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5>3. Unlock Your Benefits (Accordion List)</h5>
                        <button type="button" class="btn btn-sm btn-light add-benefit-row">+ Add New Benefit</button>
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
                                        <button type="button" class="btn btn-sm btn-danger remove-item">×</button>
                                    </div>
                                    <input type="text" name="benefits_list[{{$key}}][title]" class="form-control mb-2" placeholder="Title" value="{{ $benefit['title'] ?? '' }}">
                                    <textarea name="benefits_list[{{$key}}][desc]" class="form-control" placeholder="Description">{{ $benefit['desc'] ?? '' }}</textarea>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-5 shadow"><div class="card-body text-center"><button type="submit" class="btn btn-success btn-lg w-50">SAVE CAREER DATA</button></div></div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Image Preview
        $(document).on('change', 'input[type="file"]', function() {
            let reader = new FileReader();
            let preview = $(this).closest('.col-md-4').find('.preview-img');
            reader.onload = (e) => { preview.attr('src', e.target.result); }
            reader.readAsDataURL(this.files[0]);
        });

        // Add Benefit Row
        $('.add-benefit-row').click(function() {
            let index = $('.benefit-item').length;
            let html = `
                <div class="benefit-item border-start border-info border-4 p-3 mb-3 bg-light shadow-sm">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-bold text-info">New Benefit</h6>
                        <button type="button" class="btn btn-sm btn-danger remove-item">×</button>
                    </div>
                    <input type="text" name="benefits_list[${index}][title]" class="form-control mb-2" placeholder="e.g. Career Development">
                    <textarea name="benefits_list[${index}][desc]" class="form-control" placeholder="Description..."></textarea>
                </div>`;
            $('#benefits-wrapper').append(html);
        });

        $(document).on('click', '.remove-item', function() { $(this).closest('.benefit-item').remove(); });
    });
</script>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Image Preview
        $(document).on('change', 'input[type="file"]', function() {
            let reader = new FileReader();
            let preview = $(this).closest('.col-md-4').find('.preview-img');
            reader.onload = (e) => { preview.attr('src', e.target.result); }
            reader.readAsDataURL(this.files[0]);
        });

        // Add Benefit Row
        $('.add-benefit-row').click(function() {
            let index = $('.benefit-item').length;
            let html = `
                <div class="benefit-item border-start border-info border-4 p-3 mb-3 bg-light shadow-sm">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="fw-bold text-info">New Benefit</h6>
                        <button type="button" class="btn btn-sm btn-danger remove-item">×</button>
                    </div>
                    <input type="text" name="benefits_list[${index}][title]" class="form-control mb-2" placeholder="e.g. Career Development">
                    <textarea name="benefits_list[${index}][desc]" class="form-control" placeholder="Description..."></textarea>
                </div>`;
            $('#benefits-wrapper').append(html);
        });

        // SweetAlert Delete Confirmation
        $(document).on('click', '.remove-item', function() {
            let item = $(this).closest('.benefit-item');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to remove this benefit item?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    item.fadeOut(300, function() {
                        $(this).remove();
                        // আইটেম রিমুভ হওয়ার পর ইনডেক্স রিনেম করার প্রয়োজন হতে পারে যদি আপনি সিরিয়াল মেনটেইন করতে চান
                        reIndexBenefits();
                    });

                    Swal.fire(
                        'Deleted!',
                        'Benefit item has been removed.',
                        'success'
                    )
                }
            });
        });

        // ইন্ডেক্স ঠিক রাখার জন্য ফাংশন (অপশনাল কিন্তু ভালো প্র্যাকটিস)
        function reIndexBenefits() {
            $('.benefit-item').each(function(index) {
                $(this).find('input').attr('name', `benefits_list[${index}][title]`);
                $(this).find('textarea').attr('name', `benefits_list[${index}][desc]`);
                $(this).find('h6').text(`Benefit #${index + 1}`);
            });
        }
    });
</script>
@endpush
@endpush
