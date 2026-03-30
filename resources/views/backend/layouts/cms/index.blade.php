@extends('backend.app')

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <div class="page-header d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <h1 class="page-title">Home CMS Management ({{ ucfirst($type ?? 'english') }})</h1>
                    <div class="ms-auto">
                        <div class="btn-group p-1 bg-white border shadow-sm" role="group">
                            {{-- English Button --}}
                            <a href="{{ route('admin.cms.home.intro.index', ['type' => 'english']) }}"
                                class="btn {{ ($selected_type ?? 'english') == 'english' ? 'btn-primary' : 'btn-light' }}">
                                English
                            </a>

                            {{-- Other Language Button --}}
                            <a href="{{ route('admin.cms.home.intro.index', ['type' => 'de']) }}"
                                class="btn {{ ($selected_type ?? '') == 'de' ? 'btn-danger' : 'btn-light' }}">
                                Other Language
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.cms.home.intro.content') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <input type="hidden" name="type" value="{{ $type ?? 'english' }}">

                    <div class="card shadow mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5>1. Hero Section (Title, Video File, 2 Buttons)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6"><x-form.text name="title" label="Main Title" :value="$data->title ?? ''" />
                                </div>
                                <div class="col-md-6"><x-form.text name="sub_title" label="Short Title" :value="$data->sub_title ?? ''" />
                                </div>

                                <div class="col-md-12">
                                    <x-form.file name="video_file" label="Upload Hero Video (MP4)" />
                                    @if (isset($data->video) && file_exists(public_path($data->video)))
                                        <div class="mt-2">
                                            <video width="200" controls>
                                                <source src="{{ asset($data->video) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                            <br><small class="text-success">Current Video Path: {{ $data->video }}</small>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-3 mt-3">
                                    <x-form.text name="btn_text" label="Button 1 Title" :value="$data->btn_text ?? ''" />
                                </div>
                                <div class="col-md-3 mt-3">
                                    <x-form.text name="btn_link" label="Button 1 Link" :value="$data->btn_link ?? ''" />
                                </div>

                                <div class="col-md-3 mt-3">
                                    <x-form.text name="btn2_text" label="Button 2 Title" :value="$data->metadata['btn2_text'] ?? ''" />
                                </div>
                                <div class="col-md-3 mt-3">
                                    <x-form.text name="btn2_link" label="Button 2 Link" :value="$data->metadata['btn2_link'] ?? ''" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header bg-light border-bottom">
                            <h5>2. Info Section (Title + 3 Items)</h5>
                        </div>
                        <div class="card-body">
                            <x-form.text name="sec2_title" label="Title" :value="$data->metadata['sec2_title'] ?? ''" />
                            <x-form.text name="sec2_short" label="Short Title" :value="$data->metadata['sec2_short'] ?? ''" />

                            <div class="row">
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="col-md-4 border-end">
                                        <label class="badge badge-primary mb-2">Item {{ $i + 1 }}</label>
                                        {{-- Title for the item --}}
                                        <x-form.text name="sec2_items[{{ $i }}][title]"
                                            label="Text {{ $i + 1 }} Title" :value="$data->metadata['sec2_items'][$i]['title'] ?? ''" />

                                        {{-- Description for the item --}}
                                        <x-form.text name="sec2_items[{{ $i }}][desc]"
                                            label="Text {{ $i + 1 }} Description" :value="$data->metadata['sec2_items'][$i]['desc'] ?? ''" />
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    {{-- <div class="card shadow mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5>3. Feature Section (Picture + 4 Texts)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">

                                    <x-form.file name="image1" label="Upload Section Video (MP4)" />


                                    @if (isset($data->image1) && file_exists(public_path($data->image1)))
                                        <div class="mt-2">
                                            <video width="320" height="180" controls style="border: 1px solid #ddd; border-radius: 5px;">
                                                <source src="{{ asset($data->image1) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                            <br>
                                            <small class="text-success">Current Video: {{ basename($data->image1) }}</small>
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <small class="text-muted">No video uploaded yet.</small>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <x-form.text name="feature_title" label="Title" :value="$data->metadata['feature_title'] ?? ''" />
                                    <x-form.text name="feature_short" label="Short Title" :value="$data->metadata['feature_short'] ?? ''" />
                                    <div class="row">
                                        @for ($i = 0; $i < 4; $i++)
                                            <div class="col-md-6">
                                                <x-form.text name="feature_list[]" label="Feature {{ $i + 1 }}"
                                                    :value="$data->metadata['feature_list'][$i] ?? ''" />
                                            </div>
                                        @endfor
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-6">
                                            <x-form.text name="sec2_link_title" label="Link Title" :value="$data->metadata['sec2_link_title'] ?? ''" />
                                        </div>
                                        <div class="col-md-6">
                                            <x-form.text name="sec2_link_url" label="Link Name / URL" :value="$data->metadata['sec2_link_url'] ?? ''" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="card shadow mb-4">
                        <div class="card-header bg-dark text-white d-flex justify-content-between">
                            <h5>3. Case Study & Industry Section</h5>
                            <span class="badge badge-light">Stats: 75%, 95%, 37%</span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <x-form.text name="case_sec_title" label="Section Main Title" :value="$data->metadata['case_sec_title'] ?? ''" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.text name="case_sec_subtitle" label="Section Sub Title" :value="$data->metadata['case_sec_subtitle'] ?? ''" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 border-end">
                                    <h6 class="font-weight-bold text-primary mb-3">Left Side (Case Study Spotlight)</h6>

                                    <div class="mb-3">
                                        <x-form.text name="case_image_subtile" label="Case image subtitle"
                                            :value="$data->metadata['case_image_subtile'] ?? ''" />
                                    </div>

                                    <x-form.file name="image2" label="Case Study Main Image (Left)" />
                                    @if (isset($data->image2))
                                        <img src="{{ asset($data->image2) }}" class="mt-2 border"
                                            style="height: 150px; width:250px; object-fit: cover;">
                                    @endif
                                    <div class="mb-3">
                                        <x-form.text name="case_description" label="Case Study Description"
                                            :value="$data->metadata['case_description'] ?? ''" />
                                    </div>

                                    <div class="row mt-3">
                                        {{-- Stat 1 --}}
                                        <div class="col-md-4 border-end">
                                            <x-form.text name="stat_1_val" label="Stat 1 Percentage (%)"
                                                :value="$data->metadata['stat_1_val'] ?? '75'" />
                                            <x-form.text name="stat_1_text" label="Stat 1 Description" :value="$data->metadata['stat_1_text'] ?? ''" />
                                        </div>

                                        {{-- Stat 2 --}}
                                        <div class="col-md-4 border-end">
                                            <x-form.text name="stat_2_val" label="Stat 2 Percentage (%)"
                                                :value="$data->metadata['stat_2_val'] ?? '95'" />
                                            <x-form.text name="stat_2_text" label="Stat 2 Description" :value="$data->metadata['stat_2_text'] ?? ''" />
                                        </div>

                                        {{-- Stat 3 --}}
                                        <div class="col-md-4">
                                            <x-form.text name="stat_3_val" label="Stat 3 Percentage (%)"
                                                :value="$data->metadata['stat_3_val'] ?? '37'" />
                                            <x-form.text name="stat_3_text" label="Stat 3 Description"
                                                :value="$data->metadata['stat_3_text'] ?? ''" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="font-weight-bold text-success mb-3">Right Side (Industry Solutions)</h6>

                                    <div class="mb-3">
                                        <x-form.text name="industry_description" label="Industry Description"
                                            :value="$data->metadata['industry_description'] ?? ''" />
                                    </div>

                                    <div class="row">
                                        @for ($i = 0; $i < 5; $i++)
                                            @php $industry = $data->metadata['industry_items'][$i] ?? null; @endphp
                                            <div class="col-md-6 mb-3 border p-2 bg-light">
                                                <label class="badge badge-secondary">Item {{ $i + 1 }}</label>
                                                <x-form.text name="industry[{{ $i }}][title]" label="Title"
                                                    :value="$industry['title'] ?? ''" />

                                                @if (isset($industry['img']))
                                                    <img src="{{ asset($industry['img']) }}" class="mb-1"
                                                        style="height: 40px;">
                                                @endif
                                                <x-form.file name="industry[{{ $i }}][image]"
                                                    label="Upload Image" />
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4 border-info">
                        <div class="card-header bg-info text-white">
                            <h5>4. AI Powered CX Section</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-12"><x-form.text name="cx_title" label="CX Title"
                                        :value="$data->metadata['cx_title'] ?? ''" /></div>
                                <div class="col-md-12"><x-form.text name="cx_description" label="CX Description"
                                        :value="$data->metadata['cx_description'] ?? ''" /></div>
                                <div class="col-md-6"><x-form.text name="cx_link_title" label="Link Title"
                                        :value="$data->metadata['cx_link_title'] ?? ''" /></div>
                                <div class="col-md-6"><x-form.text name="cx_link_add" label="Link URL"
                                        :value="$data->metadata['cx_link_add'] ?? ''" />
                                </div>
                            </div>

                            <hr>
                            <h6 class="font-weight-bold mb-3 text-info">5. Features (Image + Title + Description)</h6>
                            <div class="row">
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="col-md-4 border-end">
                                        <label class="badge badge-info mb-2">Feature Item {{ $i + 1 }}</label>
                                        @php $cx_item = $data->metadata['cx_features'][$i] ?? null; @endphp

                                        @if (isset($cx_item['img_path']))
                                            <div class="mb-2">
                                                <img src="{{ asset($cx_item['img_path']) }}"
                                                    style="height: 60px; border-radius: 5px;" alt="">
                                            </div>
                                        @endif
                                        <x-form.file name="cx_items[{{ $i }}][image]" label="Upload Image" />
                                        <x-form.text name="cx_items[{{ $i }}][title]" label="Title"
                                            :value="$cx_item['title'] ?? ''" />
                                        <x-form.text name="cx_items[{{ $i }}][desc]" label="Description"
                                            :value="$cx_item['desc'] ?? ''" />
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-4 border-success">
                        <div class="card-header bg-success text-white">
                            <h5>5. Bottom Video & Call to Action</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <x-form.file name="bottom_video" label="Upload Bottom Section Video (MP4)" />
                                    @if (isset($data->image4) && file_exists(public_path($data->image4)))
                                        <div class="mt-2">
                                            <video width="200" controls>
                                                <source src="{{ asset($data->image4) }}" type="video/mp4">
                                            </video>
                                            <br><small class="text-success">Current Path: {{ $data->image4 }}</small>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-12 mt-3">
                                    <x-form.text name="bottom_desc" label="Section Description" :value="$data->metadata['bottom_desc'] ?? ''" />
                                </div>
                                <div class="col-md-6 mt-3">
                                    <x-form.text name="bottom_btn_title" label="Button Title" :value="$data->metadata['bottom_btn_title'] ?? ''" />
                                </div>
                                <div class="col-md-6 mt-3">
                                    <x-form.text name="bottom_btn_link" label="Button Link" :value="$data->metadata['bottom_btn_link'] ?? ''" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4 border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5>6. Start Deployment AI Agents</h5>
                        </div>
                        <div class="card-body">
                            <x-form.text name="ai_agents_title" label="Section Main Title" :value="$data->metadata['ai_agents_title'] ?? 'Start deployment AI Agents'" />
                            <x-form.text name="ai_agents_discription" label="Section Main Discription"
                                :value="$data->metadata['ai_agents_discription'] ?? 'Start deployment AI Agents'" />

                            <div class="row mt-3">
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="col-md-4">
                                        <div class="border p-3 mb-2 bg-light">
                                            <label class="badge badge-warning mb-2">Agent Step {{ $i + 1 }}</label>

                                            {{-- টাইটেল বা ধাপের নাম --}}
                                            <x-form.text name="ai_deployment[{{ $i }}][title]"
                                                label="Title {{ $i + 1 }}" :value="$data->metadata['ai_deployment'][$i]['title'] ?? ''" />

                                            {{-- ছোট বর্ণনা --}}
                                            <x-form.text name="ai_deployment[{{ $i }}][desc]"
                                                label="Description {{ $i + 1 }}" :value="$data->metadata['ai_deployment'][$i]['desc'] ?? ''" />
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4 border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5>7. FAQ Section</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <x-form.text name="faq_title" label="FAQ Section Title" :value="$data->metadata['faq_title'] ?? 'Frequently Asked Questions'" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.text name="faq_discription" label="FAQ Section Main Description"
                                        :value="$data->metadata['faq_discription'] ?? ''" />
                                </div>
                            </div>

                            <hr>
                            <h6 class="mb-3">Questions & Answers (Max 7)</h6>

                            <div class="row">
                                @for ($i = 0; $i < 7; $i++)
                                    <div class="col-md-6 mb-3">
                                        <div class="border p-3 bg-light shadow-sm">
                                            <label class="badge badge-dark mb-2">Item {{ $i + 1 }}</label>

                                            <x-form.text name="faq[{{ $i }}][q]"
                                                label="Question {{ $i + 1 }}" :value="$data->metadata['faq'][$i]['q'] ?? ''" />

                                            <x-form.text name="faq[{{ $i }}][a]"
                                                label="Answer {{ $i + 1 }}" :value="$data->metadata['faq'][$i]['a'] ?? ''" />
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-warning text-dark">
                                <h5>8.Better CX, better Business</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-form.text name="last_bettercx_title" label="Better CX, better Business"
                                            :value="$data->metadata['last_bettercx_title'] ??
                                                'Better CX, better Business'" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.text name="last_bettercx_desc"
                                            label="Better CX, better Business Description" :value="$data->metadata['last_bettercx_desc'] ?? ''" />
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card shadow mb-4 border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5>9. Logo Images</h5>
                            </div>

                            <div class="card-body">
                                <div class="row">

                                    {{-- Logo 1 --}}
                                    <div class="col-md-6">
                                        <x-form.file name="logo_img1" label="Logo Image 1" />

                                        @if (isset($data->metadata['logo_img1']))
                                            <div class="mt-2">
                                                <img src="{{ asset($data->metadata['logo_img1']) }}"
                                                    style="height: 70px; border:1px solid #ddd; padding:5px;">
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Logo 2 --}}
                                    <div class="col-md-6">
                                        <x-form.file name="logo_img2" label="Logo Image 2" />

                                        @if (isset($data->metadata['logo_img2']))
                                            <div class="mt-2">
                                                <img src="{{ asset($data->metadata['logo_img2']) }}"
                                                    style="height: 70px; border:1px solid #ddd; padding:5px;">
                                            </div>
                                        @endif
                                    </div>

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
