@extends('backend.app')

@section('content')
    <div class="app-content main-content mt-0">
        <div class="side-app">
            <div class="main-container container-fluid">

                <div class="page-header d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <h1 class="page-title">Home CMS - {{ ucfirst($slug) }} ({{ ucfirst($type ?? 'english') }})</h1>
                    <div class="ms-auto">
                        <div class="btn-group p-1 bg-white border shadow-sm" role="group">
                            {{-- English Button --}}
                            <a href="{{ route('admin.cms.home.cms.index', ['slug' => $slug, 'type' => 'english']) }}"
                                class="btn {{ ($type ?? 'english') == 'english' ? 'btn-primary' : 'btn-light' }}">
                                English
                            </a>

                            {{-- Other Language Button --}}
                            <a href="{{ route('admin.cms.home.cms.index', ['slug' => $slug, 'type' => 'de']) }}"
                                class="btn {{ ($type ?? '') == 'de' ? 'btn-danger' : 'btn-light' }}">
                                Other Language
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ফর্ম অ্যাকশনে স্লাগ এবং টাইপ পাস করা হয়েছে --}}
                <form action="{{ route('admin.cms.home.cms.update', ['slug' => $slug]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="type" value="{{ $type ?? 'english' }}">

                    {{-- 1. Hero Section --}}
                    {{-- <div class="card shadow mb-4">
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
                                            </video>
                                            <br><small class="text-success">Current Video: {{ $data->video }}</small>
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
                    </div> --}}

                    {{-- 2. Info Section --}}
                    {{-- <div class="card shadow mb-4">
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
                                        <x-form.text name="sec2_items[{{ $i }}][title]" label="Text Title"
                                            :value="$data->metadata['sec2_items'][$i]['title'] ?? ''" />
                                        <x-form.text name="sec2_items[{{ $i }}][desc]" label="Description"
                                            :value="$data->metadata['sec2_items'][$i]['desc'] ?? ''" />
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div> --}}

                    {{-- 3. Feature Section --}}
                    <div class="card shadow mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5>3. Feature Section (Picture + 4 Texts)</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                            <div class="col-md-6">
                                {{-- ভিডিও ফাইল ইনপুট --}}
                                <x-form.file name="image1" label="Upload Section Video (MP4)" />

                                {{-- ভিডিও প্রিভিউ সেকশন --}}
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
                                            <x-form.text name="sec2_link_url" label="URL" :value="$data->metadata['sec2_link_url'] ?? ''" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 6. Case Study & Industry
                    <div class="card shadow mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5>6. Case Study & Industry Section</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 border-end">
                                    <x-form.file name="image2" label="Case Study Main Image" />
                                    @if (isset($data->image2))
                                        <img src="{{ asset($data->image2) }}" class="mt-2 border" style="height: 100px;">
                                    @endif
                                    <x-form.text name="case_description" label="Description" :value="$data->metadata['case_description'] ?? ''" />
                                    <div class="row mt-3">
                                        <div class="col-md-4"><x-form.text name="stat_75" label="Stat 75%"
                                                :value="$data->metadata['stat_75'] ?? ''" /></div>
                                        <div class="col-md-4"><x-form.text name="stat_95" label="Stat 95%"
                                                :value="$data->metadata['stat_95'] ?? ''" /></div>
                                        <div class="col-md-4"><x-form.text name="stat_37" label="Stat 37%"
                                                :value="$data->metadata['stat_37'] ?? ''" /></div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <x-form.text name="industry_description" label="Industry Description"
                                        :value="$data->metadata['industry_description'] ?? ''" />
                                    <div class="row">
                                        @for ($i = 0; $i < 4; $i++)
                                            @php $industry = $data->metadata['industry_items'][$i] ?? null; @endphp
                                            <div class="col-md-6 mb-3 border p-2 bg-light">
                                                <x-form.text name="industry[{{ $i }}][title]" label="Title"
                                                    :value="$industry['title'] ?? ''" />
                                                <x-form.file name="industry[{{ $i }}][image]"
                                                    label="Icon" />
                                                @if (isset($industry['img']))
                                                    <img src="{{ asset($industry['img']) }}" class="mb-1"
                                                        style="height: 200px;">
                                                @endif
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 4. AI Powered CX --}}
                    {{-- <div class="card shadow mb-4 border-info">
                        <div class="card-header bg-info text-white">
                            <h5>4. AI Powered CX Section</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12"><x-form.text name="cx_title" label="CX Title"
                                        :value="$data->metadata['cx_title'] ?? ''" /></div>
                                <div class="col-md-12"><x-form.text name="cx_description" label="CX Description"
                                        :value="$data->metadata['cx_description'] ?? ''" /></div>
                            </div>
                            <hr>
                            <div class="row">
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="col-md-4 border-end">
                                        @php $cx_item = $data->metadata['cx_features'][$i] ?? null; @endphp
                                        @if (isset($cx_item['img_path']))
                                            <img src="{{ asset($cx_item['img_path']) }}" style="height: 50px;">
                                        @endif
                                        <x-form.file name="cx_items[{{ $i }}][image]" label="Icon" />
                                        <x-form.text name="cx_items[{{ $i }}][title]" label="Title"
                                            :value="$cx_item['title'] ?? ''" />
                                        <x-form.text name="cx_items[{{ $i }}][desc]" label="Desc"
                                            :value="$cx_item['desc'] ?? ''" />
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="card shadow mb-4 border-success">
                        <div class="card-header bg-success text-white">
                            <h5>7. Bottom Video & Call to Action</h5>
                        </div>
                        <div class="card-body">
                            <x-form.file name="bottom_video" label="Video File" />
                            @if (isset($data->image4))

                                <video width="200" controls class="mt-2">
                                    <source src="{{ asset($data->image4) }}">
                                </video>
                            @endif
                            <x-form.text name="bottom_desc" label="Description" :value="$data->metadata['bottom_desc'] ?? ''" />
                            <div class="row mt-3">
                                <div class="col-md-6"><x-form.text name="bottom_btn_title" label="Button Text"
                                        :value="$data->metadata['bottom_btn_title'] ?? ''" /></div>
                                <div class="col-md-6"><x-form.text name="bottom_btn_link" label="Link"
                                        :value="$data->metadata['bottom_btn_link'] ?? ''" /></div>
                            </div>
                        </div>
                    </div> --}}

                    {{-- 9. FAQ Section --}}
                    {{-- <div class="card shadow mb-4 border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h5>9. FAQ Section</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @for ($i = 0; $i < 6; $i++)
                                    <div class="col-md-6 mb-3">
                                        <div class="border p-2 bg-light">
                                            <x-form.text name="faq[{{ $i }}][q]"
                                                label="Question {{ $i + 1 }}" :value="$data->metadata['faq'][$i]['q'] ?? ''" />
                                            <x-form.text name="faq[{{ $i }}][a]"
                                                label="Answer {{ $i + 1 }}" :value="$data->metadata['faq'][$i]['a'] ?? ''" />
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div> --}}

                    <div class="card mb-5 shadow">
                        <div class="card-body p-4 text-center">
                            <button type="submit" class="btn btn-success btn-lg w-50">SAVE {{ strtoupper($slug) }}
                                DATA</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
