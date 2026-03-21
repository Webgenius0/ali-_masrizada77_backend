@extends('backend.app')
@section('content')

<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 class="page-title">Energy and Utilities CMS Management</h1>
                <div class="ms-auto">
                    <a href="?type=english" class="btn {{ $type == 'english' ? 'btn-primary' : 'btn-outline-primary' }}">English</a>
                    <a href="?type=de" class="btn {{ $type == 'de' ? 'btn-info' : 'btn-outline-info' }}">German</a>
                </div>
            </div>

            <form action="{{ route('admin.cms.home.energy.content') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="row">
                    <!-- Section 1: Hero Video -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title">Section 1: Hero Video</h3>
                            </div>
                            <div class="card-body">
                                <input type="text" name="sec1_title" class="form-control mb-2" placeholder="Title" value="{{ $data->metadata['sec1_title'] ?? '' }}">
                                <input type="text" name="sec1_sub_title" class="form-control mb-2" placeholder="Sub Title" value="{{ $data->metadata['sec1_sub_title'] ?? '' }}">

                                <label class="form-label mt-2">Video File</label>
                                <input type="file" name="sec1_video" class="form-control mb-2" accept="video/mp4" onchange="previewVideo(this, 'sec1_prev')">
                                <video id="sec1_prev" width="100%" height="180" controls class="{{ isset($data->metadata['sec1_video']) ? '' : 'd-none' }}">
                                    <source src="{{ asset($data->metadata['sec1_video'] ?? '') }}" type="video/mp4">
                                </video>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Spotlight & Stats -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-dark text-white">
                                <h3 class="card-title">Section 2: Spotlight & Stats</h3>
                            </div>
                            <div class="card-body">
                                <input type="text" name="sec2_title" class="form-control mb-2" placeholder="Title" value="{{ $data->metadata['sec2_title'] ?? '' }}">
                                <input type="text" name="sec2_sub_title" class="form-control mb-2" placeholder="Sub Title" value="{{ $data->metadata['sec2_sub_title'] ?? '' }}">

                                <label class="form-label mt-2">Video File</label>
                                <input type="file" name="sec2_video" class="form-control mb-2" accept="video/mp4" onchange="previewVideo(this, 'sec2_prev')">
                                <video id="sec2_prev" width="100%" height="180" controls class="{{ isset($data->metadata['sec2_video']) ? '' : 'd-none' }} mb-3">
                                    <source src="{{ asset($data->metadata['sec2_video'] ?? '') }}" type="video/mp4">
                                </video>

                                <div class="row">
                                    @for($i = 0; $i < 3; $i++)
                                    <div class="col-4">
                                        <input type="text" name="sec2_stats[{{$i}}][val]" class="form-control mb-1" placeholder="75%" value="{{ $data->metadata['sec2_stats'][$i]['val'] ?? '' }}">
                                        <input type="text" name="sec2_stats[{{$i}}][title]" class="form-control" placeholder="e.g. Patient Satisfaction" value="{{ $data->metadata['sec2_stats'][$i]['title'] ?? '' }}">
                                    </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Smarter Communication -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Section 3: Better CX</h3>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addSec3()">+ Add Item</button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4 pb-3 border-bottom">
                            <div class="col-md-4">
                                <label class="form-label">Main Title</label>
                                <input type="text" name="sec3_title" class="form-control" value="{{ $data->metadata['sec3_title'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Main Description</label>
                                <input type="text" name="sec3_desc" class="form-control" value="{{ $data->metadata['sec3_desc'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Side Image</label>
                                <input type="file" name="sec3_image" class="form-control mb-2" accept="image/*" onchange="previewImage(this, 'sec3_img_prev')">
                                <img id="sec3_img_prev" src="{{ asset($data->metadata['sec3_image'] ?? 'backend/images/no-image.png') }}" class="img-thumbnail" style="max-width:140px;">
                            </div>
                        </div>

                        <div id="sec3-wrapper">
                            @foreach($data->metadata['sec3_items'] ?? [] as $key => $item)
                            <div class="row mb-3 border p-3 item-box align-items-center position-relative">
                                <div class="col-md-3">
                                    <input type="file" name="sec3_icon_{{$key}}" class="form-control mb-2" accept="image/*" onchange="previewImage(this, 'sec3_icon_prev_{{$key}}')">
                                    <img id="sec3_icon_prev_{{$key}}" src="{{ asset($item['icon'] ?? 'backend/images/no-image.png') }}" width="60" height="60" class="mx-auto d-block">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="sec3_items[{{$key}}][title]" class="form-control mb-2" placeholder="Title" value="{{ $item['title'] ?? '' }}">
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="sec3_items[{{$key}}][desc]" class="form-control" placeholder="Description" value="{{ $item['desc'] ?? '' }}">
                                </div>
                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Section 4: Modern Patient Operations -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Section 4: Operations AI Patient </h3>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addSec4()">+ Add Feature</button>
                    </div>
                    <div class="card-body">
                        <input type="text" name="sec4_title" class="form-control mb-3 w-75" placeholder="Section Title" value="{{ $data->metadata['sec4_title'] ?? '' }}">
                         <input type="text" name="sec4_sub_title" class="form-control mb-3 w-75" placeholder="Section Sub Title" value="{{ $data->metadata['sec4_sub_title'] ?? '' }}">


                        <div id="sec4-wrapper" class="row">
                            @foreach($data->metadata['sec4_items'] ?? [] as $key => $item)
                            <div class="col-md-4 mb-3 item-box">
                                <div class="border ">
                                    <input type="file" name="sec4_icon_{{$key}}" class="form-control mb-2" accept="image/*" onchange="previewImage(this, 'sec4_icon_prev_{{$key}}')">
                                    <div class="text-center mb-2">
                                        <img id="sec4_icon_prev_{{$key}}" src="{{ asset($item['icon'] ?? 'backend/images/no-image.png') }}" width="50" height="50">
                                    </div>
                                    <input type="text" name="sec4_items[{{$key}}][title]" class="form-control mb-2" placeholder="Feature Title" value="{{ $item['title'] ?? '' }}">
                                    <button type="button" class="btn btn-danger btn-sm w-100 remove-item">Remove</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Section 5 -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h3 class="card-title">Section 5: Design Update Regulated </h3>
                            </div>
                            <div class="card-body">
                                <input type="text" name="sec5_title" class="form-control mb-2" placeholder="Title" value="{{ $data->metadata['sec5_title'] ?? '' }}">
                                <textarea name="sec5_desc" class="form-control mb-3" rows="4" placeholder="Description">{{ $data->metadata['sec5_desc'] ?? '' }}</textarea>

                                <label class="form-label">Video File</label>
                                <input type="file" name="sec5_video" class="form-control mb-2" accept="video/mp4" onchange="previewVideo(this, 'sec5_prev')">
                                <video id="sec5_prev" width="100%" height="160" controls class="{{ isset($data->metadata['sec5_video']) ? '' : 'd-none' }}">
                                    <source src="{{ asset($data->metadata['sec5_video'] ?? '') }}" type="video/mp4">
                                </video>
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: FAQ -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">Section 6: FAQ Section</h3>
                                <button type="button" class="btn btn-sm btn-primary" onclick="addFaq()">+ Add FAQ</button>
                            </div>
                            <div class="card-body">
                                <input type="text" name="sec6_title" class="form-control mb-3" placeholder="FAQ Section Title" value="{{ $data->metadata['sec6_title'] ?? '' }}">
                                <input type="text" name="sec6_sub_title" class="form-control mb-3" placeholder="FAQ Section Sub Title" value="{{ $data->metadata['sec6_sub_title'] ?? '' }}">

                                <div id="faq-wrapper">
                                    @foreach($data->metadata['sec6_faqs'] ?? [] as $key => $faq)
                                    <div class="border p-3 mb-3 item-box position-relative">
                                        <input type="text" name="sec6_faqs[{{$key}}][q]" class="form-control mb-2" placeholder="Question" value="{{ $faq['q'] ?? '' }}">
                                        <textarea name="sec6_faqs[{{$key}}][a]" class="form-control mb-2" rows="3" placeholder="Answer">{{ $faq['a'] ?? '' }}</textarea>
                                        <button type="button" class="btn btn-danger btn-sm remove-item">Remove FAQ</button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end py-4">
                    <button type="submit" class="btn btn-success btn-lg px-5">UPDATE HEALTHCARE PAGE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Image preview
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Video preview
    function previewVideo(input, previewId) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const blobUrl = URL.createObjectURL(file);
            const video = document.getElementById(previewId);
            video.querySelector('source').src = blobUrl;
            video.classList.remove('d-none');
            video.load();
            // video.play();  // optional – usually better not to auto-play
        }
    }

    // Add Section 3 item
    function addSec3() {
        const wrapper = document.getElementById('sec3-wrapper');
        const index = wrapper.querySelectorAll('.item-box').length;
        const html = `
            <div class="row mb-3 border p-3 item-box align-items-center position-relative">
                <div class="col-md-3">
                    <input type="file" name="sec3_icon_${index}" class="form-control mb-2" accept="image/*" onchange="previewImage(this, 'sec3_icon_prev_${index}')">
                    <img id="sec3_icon_prev_${index}" src="{{ asset('backend/images/no-image.png') }}" width="60" height="60" class="mx-auto d-block">
                </div>
                <div class="col-md-3">
                    <input type="text" name="sec3_items[${index}][title]" class="form-control mb-2" placeholder="Title">
                </div>
                <div class="col-md-5">
                    <input type="text" name="sec3_items[${index}][desc]" class="form-control" placeholder="Description">
                </div>
                <div class="col-md-1 text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-item">×</button>
                </div>
            </div>`;
        wrapper.insertAdjacentHTML('beforeend', html);
    }

    // Add Section 4 feature
    function addSec4() {
        const wrapper = document.getElementById('sec4-wrapper');
        const index = wrapper.querySelectorAll('.item-box').length;
        const html = `
            <div class="col-md-4 mb-3 item-box">
                <div class="border">
                    <input type="file" name="sec4_icon_${index}" class="form-control mb-2" accept="image/*" onchange="previewImage(this, 'sec4_icon_prev_${index}')">
                    <div class="text-center mb-2">
                        <img id="sec4_icon_prev_${index}" src="{{ asset('backend/images/no-image.png') }}" width="50" height="50">
                    </div>
                    <input type="text" name="sec4_items[${index}][title]" class="form-control mb-2" placeholder="Feature Title">
                    <button type="button" class="btn btn-danger btn-sm w-100 remove-item">Remove</button>
                </div>
            </div>`;
        wrapper.insertAdjacentHTML('beforeend', html);
    }

    // Add FAQ
    function addFaq() {
        const wrapper = document.getElementById('faq-wrapper');
        const index = wrapper.querySelectorAll('.item-box').length;
        const html = `
            <div class="border p-3 mb-3 item-box position-relative">
                <input type="text" name="sec6_faqs[${index}][q]" class="form-control mb-2" placeholder="Question">
                <textarea name="sec6_faqs[${index}][a]" class="form-control mb-2" rows="3" placeholder="Answer"></textarea>
                <button type="button" class="btn btn-danger btn-sm remove-item">Remove FAQ</button>
            </div>`;
        wrapper.insertAdjacentHTML('beforeend', html);
    }

    // Remove item with confirmation
    document.addEventListener('click', function(e) {
        if (!e.target.classList.contains('remove-item')) return;

        e.preventDefault();
        const item = e.target.closest('.item-box');

        Swal.fire({
            title: 'Remove this item?',
            text: "You won't be able to undo this action",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, remove it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                item.style.opacity = '0';
                setTimeout(() => {
                    item.remove();
                    Swal.fire({
                        title: 'Removed!',
                        text: 'Item has been removed.',
                        icon: 'success',
                        timer: 500,
                        showConfirmButton: false
                    });
                }, 300);
            }
        });
    });
</script>

@endsection
