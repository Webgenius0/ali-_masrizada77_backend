@extends('backend.app')

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">

        <div class="page-header d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <h1 class="page-title">NV Platform Overview ({{ ucfirst($selected_type ?? 'english') }})</h1>
            <div class="ms-auto">
                <div class="btn-group p-1 bg-white border shadow-sm" role="group">
                    {{-- English Button --}}
                    <a href="{{ route('admin.cms.home.npoverview.index', ['type' => 'english']) }}"
                        class="btn {{ ($selected_type ?? 'english') == 'english' ? 'btn-primary' : 'btn-light' }}">
                        English
                    </a>

                    {{-- Other Language Button (German Example) --}}
                    <a href="{{ route('admin.cms.home.npoverview.index', ['type' => 'de']) }}"
                        class="btn {{ ($selected_type ?? '') == 'de' ? 'btn-danger' : 'btn-light' }}">
                        Other Language
                    </a>
                </div>
            </div>
        </div>

        <div class="main-container container-fluid">

            <form action="{{ route('admin.cms.home.npoverview.content') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Hidden type field to maintain language consistency --}}
                <input type="hidden" name="type" value="{{ $selected_type ?? 'english' }}">

                {{-- =========================
                SECTION 1: Hero Section
                =========================--}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5>1. Hero Section</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-form.text name="sec1_title" label="Title" :value="$data->metadata['sec1_title'] ?? ''" />
                                <x-form.text name="sec1_desc" label="Description" :value="$data->metadata['sec1_desc'] ?? ''" />
                                <x-form.text name="sec1_url_title" label="URL Title" :value="$data->metadata['sec1_url_title'] ?? ''" />
                                <x-form.text name="sec1_url_link" label="URL Link" :value="$data->metadata['sec1_url_link'] ?? ''" />
                            </div>
                            <div class="col-md-6">
                                @for($i=1; $i<=1; $i++)
                                    <x-form.file name="sec1_img{{$i}}" label="Hero Image {{$i}}" />
                                    @php $imgField = 'image'.$i; @endphp
                                    @if(!empty($data->$imgField))
                                        <div class="mt-2 mb-3">
                                            <img src="{{ asset($data->$imgField) }}" class="img-thumbnail" style="max-width:150px;">
                                            <p class="small text-muted">Current: {{ basename($data->$imgField) }}</p>
                                        </div>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =========================
                SECTION 2: Info Section (With Video)
                =========================--}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-info text-white">
                        <h5>2. AN Unfied Interaction (Video Content)</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 border-end">
                                @for($i=0; $i<3; $i++)
                                    <div class="mb-4 p-2 border-bottom">
                                        <label class="badge badge-info mb-2">Left Item {{ $i+1 }}</label>
                                        <x-form.text name="sec2_left[{{$i}}][title]" label="Title" :value="$data->metadata['sec2_left'][$i]['title'] ?? ''" />
                                        <x-form.text name="sec2_left[{{$i}}][desc]" label="Description" :value="$data->metadata['sec2_left'][$i]['desc'] ?? ''" />
                                    </div>
                                @endfor
                            </div>
                            <div class="col-md-6">
                                <x-form.text name="sec2_right_title" label="Right Side Title" :value="$data->metadata['sec2_right_title'] ?? ''" />
                                <x-form.text name="sec2_right_desc" label="Right Side Description" :value="$data->metadata['sec2_right_desc'] ?? ''" />

                                <div class="mt-4 p-3 bg-light ">
                                    <x-form.file name="sec2_video" label="Upload Section Video (MP4)" />
                                    @if(!empty($data->video))
                                        <div class="mt-2">
                                            <video width="100%" height="200" controls class=" shadow-sm">
                                                <source src="{{ asset($data->video) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =========================
                SECTION 3: Feature Section
                =========================--}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5>3. Control & Optimize</h5>
                    </div>
                    <div class="card-body">
                        <x-form.text name="sec3_title" label="Main Section Title" :value="$data->metadata['sec3_title'] ?? ''" />
                        <div class="row mt-3">
                            @for($i=0; $i<3; $i++)
                                <div class="col-md-4">
                                    <div class="p-3 border ">
                                        <x-form.text name="sec3_items[{{$i}}][title]" label="Feature Title {{ $i+1 }}" :value="$data->metadata['sec3_items'][$i]['title'] ?? ''" />
                                        <x-form.text name="sec3_items[{{$i}}][desc]" label="Feature Description {{ $i+1 }}" :value="$data->metadata['sec3_items'][$i]['desc'] ?? ''" />
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                {{-- =========================
                SECTION 4: Image Section
                =========================--}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5>4. Connected Across Section</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <x-form.text name="sec4_title" label="Title" :value="$data->metadata['sec4_title'] ?? ''" />
                                <x-form.text name="sec4_desc" label="Description" :value="$data->metadata['sec4_desc'] ?? ''" />
                            </div>
                            <div class="col-md-4 text-center">
                                <x-form.file name="sec4_image" label="Upload Image" />
                                @if(!empty($data->metadata['sec4_image']))
                                    <img src="{{ asset($data->metadata['sec4_image']) }}" class="img-thumbnail mt-2" style="max-height:150px;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =========================
                SECTION 5: Image Description Section
                =========================--}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5>5. Flexiable BY design Section</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <x-form.text name="sec5_title" label="Title" :value="$data->metadata['sec5_title'] ?? ''" />
                                <x-form.text name="sec5_desc" label="Description" :value="$data->metadata['sec5_desc'] ?? ''" />
                                <x-form.text name="sec5_img_desc" label="Image Caption/Description" :value="$data->metadata['sec5_img_desc'] ?? ''" />
                            </div>
                            <div class="col-md-4 text-center">
                                <x-form.file name="sec5_image" label="Upload Image" />
                                @if(!empty($data->metadata['sec5_image']))
                                    <img src="{{ asset($data->metadata['sec5_image']) }}" class="img-thumbnail mt-2" style="max-height:150px;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =========================
                SECTION 6: Gallery Section
                =========================--}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0">6. Human Oversight AI Section</h5>
                    </div>
                    <div class="card-body">
                        <x-form.text name="sec6_title" label="Gallery Main Title" :value="$data->metadata['sec6_title'] ?? ''" />
                        <x-form.text name="sec6_subtitle" label="Gallery Subtitle" :value="$data->metadata['sec6_subtitle'] ?? ''" />
                        <div class="row mt-4">
                            @for($i=0; $i<3; $i++)
                                <div class="col-md-4">
                                    <div class="card border p-3">
                                        <x-form.file name="sec6_items[{{$i}}][image]" label="Gallery Image {{$i+1}}" />
                                        @if(!empty($data->metadata['sec6_items'][$i]['image']))
                                            <img src="{{ asset($data->metadata['sec6_items'][$i]['image']) }}" class="img-thumbnail mt-2 mb-2" style="height:120px; object-fit: cover;">
                                        @endif
                                        <x-form.text name="sec6_items[{{$i}}][title]" label="Title" :value="$data->metadata['sec6_items'][$i]['title'] ?? ''" />
                                        <x-form.text name="sec6_items[{{$i}}][desc]" label="Description" :value="$data->metadata['sec6_items'][$i]['desc'] ?? ''" />
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                {{-- =========================
                SECTION 7: CTA Section
                =========================--}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5>7. AI Infrastructure </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <x-form.text name="sec7_title" label="CTA Title" :value="$data->metadata['sec7_title'] ?? ''" />
                                <x-form.text name="sec7_desc" label="CTA Description" :value="$data->metadata['sec7_desc'] ?? ''" />
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-form.text name="sec7_url_title" label="Button Title" :value="$data->metadata['sec7_url_title'] ?? ''" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.text name="sec7_url_link" label="Button Link" :value="$data->metadata['sec7_url_link'] ?? ''" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <x-form.file name="sec7_image" label="CTA Background/Side Image" />
                                @if(!empty($data->metadata['sec7_image']))
                                    <img src="{{ asset($data->metadata['sec7_image']) }}" class="img-thumbnail mt-2" style="max-height:150px;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="card shadow mb-5 sticky-bottom">
                    <div class="card-body text-center p-3">
                        <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                            <i class="fe fe-save me-2"></i> UPDATE ALL CONTENT
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
