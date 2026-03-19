@extends('backend.app')

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <div class="page-header d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <h1 class="page-title">Become a Partner Management ({{ ucfirst($type ?? 'english') }})</h1>
                    <div class="ms-auto">
                        <div class="btn-group p-1 bg-white border shadow-sm" role="group">
                            <a href="{{ route('admin.cms.home.partner.index', ['type' => 'english']) }}"
                                class="btn {{ ($type ?? 'english') == 'english' ? 'btn-primary' : 'btn-light' }}">
                                English
                            </a>
                            <a href="{{ route('admin.cms.home.partner.index', ['type' => 'de']) }}"
                                class="btn {{ ($type ?? '') == 'de' ? 'btn-danger' : 'btn-light' }}">
                                German
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.cms.home.partner.content') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type ?? 'english' }}">

                    {{-- Section 1: Hero Area --}}
                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5>1. Hero Section (Title, Subtitle & Image)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <x-form.text name="sec1_title" label="Main Title" :value="$data->metadata['sec1_title'] ?? ''" />
                                    <div class="mt-3">
                                        <x-form.text name="sec1_sub_title" label="Sub Title" :value="$data->metadata['sec1_sub_title'] ?? ''" />
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <x-form.file name="sec1_image" label="Hero Image" />
                                    <div class="mt-2">
                                        <img id="sec1_preview"
                                            src="{{ asset($data->metadata['sec1_image'] ?? 'backend/images/no-image.png') }}"
                                            class="border preview-img"
                                            style="height: 150px; width:100%; object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



{{-- Section 2: Our Ecosystem --}}
<div class="card shadow mb-4">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5>2. Our Ecosystem (Fixed Title & Dynamic Logos)</h5>
        <button type="button" class="btn btn-sm btn-light add-eco-row">+ Add New Logo</button>
    </div>
    <div class="card-body">

        <div class="row mb-4 border-bottom pb-4">
            <div class="col-md-6">
                <x-form.text name="eco_title" label="Section Main Title" :value="$data->metadata['eco_title'] ?? ''" />
            </div>
            <div class="col-md-6">
                <x-form.text name="eco_sub_title" label="Section Subtitle" :value="$data->metadata['eco_sub_title'] ?? ''" />
            </div>
        </div>

        <h6 class="mb-3 text-muted">Partner Logos & Links:</h6>
        <div class="row" id="ecosystem-wrapper">
            @if(isset($data->metadata['ecosystem']))
                @foreach($data->metadata['ecosystem'] as $key => $eco)
                <div class="col-md-3 mb-4 eco-item">
                    <div class="border p-3 bg-light position-relative shadow-sm" style="border-radius: 8px;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute remove-item" style="top:-10px; right:-10px; border-radius: 50%;">×</button>

                        <div class="text-center mb-2">
                            <img src="{{ asset($eco['image'] ?? 'backend/images/no-image.png') }}" class="preview-img" style="height: 60px; width: 100%; object-fit: contain;">
                        </div>

                        <div class="form-group mb-2">
                            <label class="small fw-bold">Update Logo</label>
                            <input type="file" name="eco_img_{{$key}}" class="form-control form-control-sm">
                        </div>

                        <div class="form-group">
                            <label class="small fw-bold">URL Link</label>
                            <input type="text" name="ecosystem[{{$key}}][link]" class="form-control form-control-sm" value="{{ $eco['link'] ?? '' }}" placeholder="https://...">
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

                    {{-- Section 3: Features --}}
                    <div class="card shadow mb-4">
                        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                            <h5>3. Features (Icon, Title, Description)</h5>
                            <button type="button" class="btn btn-sm btn-light add-feat-row">+ Add Feature</button>
                        </div>
                        <div class="card-body">
                            <div id="features-wrapper">
                                @if (isset($data->metadata['features']))
                                    @foreach ($data->metadata['features'] as $key => $feat)
                                        <div class="row mb-3 feat-item border-bottom pb-3">
                                            <div class="col-md-2 text-center">
                                                <img src="{{ asset($feat['icon'] ?? 'backend/images/no-image.png') }}"
                                                    class="preview-img mb-2" style="height: 50px;">
                                                <x-form.file name="feat_icon_{{ $key }}" label="Icon" />
                                            </div>
                                            <div class="col-md-4">
                                                <x-form.text name="features[{{ $key }}][title]"
                                                    label="Feature Title" :value="$feat['title'] ?? ''" />
                                            </div>
                                            <div class="col-md-5">
                                                <x-form.text name="features[{{ $key }}][desc]" label="Description"
                                                    :value="$feat['desc'] ?? ''" />
                                            </div>
                                            <div class="col-md-1 pt-4">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-item mt-2">×</button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Section 4: Main FAQ --}}
                    <div class="card shadow mb-4">
                        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                            <h5>4. FAQ Section</h5>
                            <button type="button" class="btn btn-sm btn-dark add-faq-row">+ Add FAQ</button>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <x-form.text name="faq_title" label="Section Main Title" :value="$data->metadata['faq_title'] ?? ''" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8" id="faq-wrapper">
                                    @if (isset($data->metadata['faqs']))
                                        @foreach ($data->metadata['faqs'] as $key => $faq)
                                            <div class="faq-item border p-3 mb-3 bg-light">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <strong>FAQ Item</strong>
                                                    <button type="button"
                                                        class="btn btn-link text-danger p-0 remove-item">Remove</button>
                                                </div>
                                                <x-form.text name="faqs[{{ $key }}][q]" label="Question"
                                                    :value="$faq['q'] ?? ''" />
                                                <div class="mt-2">
                                                    <x-form.text name="faqs[{{ $key }}][a]" label="Answer"
                                                        :value="$faq['a'] ?? ''" />
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-md-4 text-center">
                                    <x-form.file name="faq_image" label="Side Image" />
                                    <img src="{{ asset($data->metadata['faq_image'] ?? 'backend/images/no-image.png') }}"
                                        class="mt-2 border preview-img img-fluid" style="max-height: 250px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 5: Who We Partner With (NEW) --}}
                    <div class="card shadow mb-4">
                        <div class="card-header bg-success text-white">
                            <h5>5. Who We Partner With</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <x-form.text name="who_title" label="Section Title" :value="$data->metadata['who_title'] ?? ''" />
                                    <div class="mt-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="who_desc" class="form-control" rows="4">{{ $data->metadata['who_desc'] ?? '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <x-form.file name="who_image" label="Section Image" />
                                    <img src="{{ asset($data->metadata['who_image'] ?? 'backend/images/no-image.png') }}"
                                        class="mt-2 border preview-img img-fluid"
                                        style="height: 180px; width: 100%; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 6: Extra FAQs (NEW) --}}
                    <div class="card shadow mb-4 border-primary">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5>6. Additional FAQ Section</h5>
                            <button type="button" class="btn btn-sm btn-light add-extra-faq-row">+ Add Extra FAQ</button>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <x-form.text name="extra_faq_title" label="Extra FAQ Main Title" :value="$data->metadata['extra_faq_title'] ?? ''" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.text name="extra_faq_sub" label="Extra FAQ Subtitle" :value="$data->metadata['extra_faq_sub'] ?? ''" />
                                </div>
                            </div>
                            <div id="extra-faq-wrapper">
                                @if (isset($data->metadata['extra_faqs']))
                                    @foreach ($data->metadata['extra_faqs'] as $key => $exFaq)
                                        <div class="extra-faq-item border-start border-primary border-4 p-3 mb-3 bg-light">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h6 class="text-primary">Extra Question #{{ $key + 1 }}</h6>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger remove-item">Remove</button>
                                            </div>
                                            <x-form.text name="extra_faqs[{{ $key }}][q]" label="Question"
                                                :value="$exFaq['q'] ?? ''" />
                                            <div class="mt-2">
                                                <x-form.text name="extra_faqs[{{ $key }}][a]" label="Answer"
                                                    :value="$exFaq['a'] ?? ''" />
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card mb-5 shadow">
                        <div class="card-body p-4 text-center">
                            <button type="submit" class="btn btn-success btn-lg w-50">SAVE
                                {{ strtoupper($type ?? 'ENGLISH') }} DATA</button>
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
        // --- ১. ইমেজ প্রিভিউ লজিক (Universal) ---
        $(document).on('change', 'input[type="file"]', function() {
            let reader = new FileReader();
            let fileInput = $(this);
            // কার্ড বা আইটেম কন্টেইনার খুঁজে তার ভেতরের preview-img আপডেট করবে
            let preview = fileInput.closest('.card-body, .eco-item, .feat-item, .faq-item, .extra-faq-item, .col-md-4').find('.preview-img');

            reader.onload = (e) => {
                preview.attr('src', e.target.result);
            }
            if (this.files[0]) {
                reader.readAsDataURL(this.files[0]);
            }
        });

        // --- ২. ইকোসিস্টেম রো অ্যাড (Logos & Links) ---
        $('.add-eco-row').click(function() {
            let index = $('.eco-item').length;
            let html = `
                <div class="col-md-3 mb-4 eco-item">
                    <div class="border p-3 bg-light position-relative shadow-sm" style="border-radius: 8px;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute remove-item" style="top:-10px; right:-10px; border-radius: 50%;">×</button>
                        <div class="text-center mb-2">
                            <img src="{{ asset('backend/images/no-image.png') }}" class="preview-img" style="height: 60px; width: 100%; object-fit: contain;">
                        </div>
                        <div class="form-group mb-2">
                            <label class="small fw-bold">Upload Logo</label>
                            <input type="file" name="eco_img_${index}" class="form-control form-control-sm">
                        </div>
                        <div class="form-group">
                            <label class="small fw-bold">URL Link</label>
                            <input type="text" name="ecosystem[${index}][link]" class="form-control form-control-sm" placeholder="https://...">
                        </div>
                    </div>
                </div>`;
            $('#ecosystem-wrapper').append(html);
        });

        // --- ৩. ফিচার রো অ্যাড ---
        $('.add-feat-row').click(function() {
            let index = $('.feat-item').length;
            let html = `
                <div class="row mb-3 feat-item border-bottom pb-3">
                    <div class="col-md-2 text-center">
                        <img src="{{ asset('backend/images/no-image.png') }}" class="preview-img mb-2" style="height: 50px; width: 50px; object-fit: cover;">
                        <input type="file" name="feat_icon_${index}" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-4">
                        <label class="fw-bold">Feature Title</label>
                        <input type="text" name="features[${index}][title]" class="form-control">
                    </div>
                    <div class="col-md-5">
                        <label class="fw-bold">Description</label>
                        <input type="text" name="features[${index}][desc]" class="form-control">
                    </div>
                    <div class="col-md-1 pt-4 text-end">
                        <button type="button" class="btn btn-danger btn-sm remove-item mt-2">×</button>
                    </div>
                </div>`;
            $('#features-wrapper').append(html);
        });

        // --- ৪. মেইন FAQ রো অ্যাড ---
        $('.add-faq-row').click(function() {
            let index = $('#faq-wrapper .faq-item').length;
            let html = `
                <div class="faq-item border p-3 mb-3 bg-light">
                    <div class="d-flex justify-content-between mb-2">
                        <strong class="text-dark">FAQ Item #${index + 1}</strong>
                        <button type="button" class="btn btn-link text-danger p-0 remove-item" style="text-decoration:none;">Remove</button>
                    </div>
                    <div class="form-group">
                        <label>Question</label>
                        <input type="text" name="faqs[${index}][q]" class="form-control">
                    </div>
                    <div class="form-group mt-2">
                        <label>Answer</label>
                        <input type="text" name="faqs[${index}][a]" class="form-control">
                    </div>
                </div>`;
            $('#faq-wrapper').append(html);
        });

        // --- ৫. এক্সট্রা FAQ রো অ্যাড (Section 6) ---
        $('.add-extra-faq-row').click(function() {
            let index = $('.extra-faq-item').length;
            let html = `
                <div class="extra-faq-item border-start border-primary border-4 p-3 mb-3 bg-light shadow-sm">
                    <div class="d-flex justify-content-between mb-2">
                        <h6 class="text-primary fw-bold">Extra Question #${index + 1}</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item">Remove</button>
                    </div>
                    <div class="form-group">
                        <label>Question</label>
                        <input type="text" name="extra_faqs[${index}][q]" class="form-control border-primary">
                    </div>
                    <div class="form-group mt-2">
                        <label>Answer</label>
                        <textarea name="extra_faqs[${index}][a]" class="form-control" rows="2"></textarea>
                    </div>
                </div>`;
            $('#extra-faq-wrapper').append(html);
        });

        // --- ৬. রিমুভ আইটেম (With SweetAlert) ---
        $(document).on('click', '.remove-item', function() {
            let target = $(this).closest('.eco-item, .feat-item, .faq-item, .extra-faq-item');

            Swal.fire({
                title: 'Are you sure?',
                text: "This specific item will be removed!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    target.fadeOut(300, function() {
                        $(this).remove();
                    });
                }
            });
        });
    });
</script>
@endpush
