@extends('backend.app')

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="page-header d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
            <h1 class="page-title">About Us ({{ ucfirst($type ?? 'english') }})</h1>
            <div class="btn-group p-1 bg-white border shadow-sm">
                <a href="{{ route('admin.cms.home.aboutus.index', ['type' => 'english']) }}" class="btn {{ ($type ?? 'english') == 'english' ? 'btn-primary' : 'btn-light' }}">English</a>
                <a href="{{ route('admin.cms.home.aboutus.index', ['type' => 'de']) }}" class="btn {{ ($type ?? '') == 'de' ? 'btn-danger' : 'btn-light' }}">Other Language</a>
            </div>
        </div>

        <div class="main-container container-fluid">
            <form action="{{ route('admin.cms.home.aboutus.content') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="{{ $type ?? 'english' }}">

                <div class="card shadow mb-4">
                    <div class="card-header bg-primary text-white"><h5>1. Hero Section</h5></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <x-form.text name="hero_title" label="Title" :value="$data->metadata['hero_title'] ?? ''" />
                                <x-form.text name="hero_subtitle" label="Subtitle" :value="$data->metadata['hero_subtitle'] ?? ''" />
                            </div>
                            <div class="col-md-4">
                                <x-form.file name="hero_image" label="Hero Image" />
                                @if(!empty($data->image1))
                                    <img src="{{ asset($data->image1) }}" class="img-thumbnail mt-2" style="height: 80px;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header bg-info text-white"><h5>2. Intro Title Section</h5></div>
                    <div class="card-body">
                        <x-form.text name="sec2_title" label="Title" :value="$data->metadata['sec2_title'] ?? ''" />
                        <x-form.text name="sec2_subtitle" label="Subtitle" :value="$data->metadata['sec2_subtitle'] ?? ''" />
                    </div>
                </div>

                <div class="card shadow mb-4 border-success">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">3. Title, Image & Q&A</h5>
                        <button type="button" class="btn btn-sm btn-light add-qa" data-container="qa-3" data-name="sec3_qa">Add Q&A Item</button>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <x-form.text name="sec3_title" label="Title" :value="$data->metadata['sec3_title'] ?? ''" />
                                <label>Image Description</label>
                                <textarea name="sec3_img_desc" class="form-control" rows="2">{{ $data->metadata['sec3_img_desc'] ?? '' }}</textarea>
                            </div>
                            <div class="col-md-4">
                                <x-form.file name="section3_image" label="Section Image" />
                                @if(!empty($data->metadata['sec3_image']))
                                    <img src="{{ asset($data->metadata['sec3_image']) }}" class="img-thumbnail mt-2" style="height: 80px;">
                                @endif
                            </div>
                        </div>
                        <div id="qa-3">
                            @foreach($data->metadata['sec3_qa'] ?? [] as $index => $qa)
                                <div class="qa-item border p-3 mb-2 bg-light">
                                    <input type="text" name="sec3_qa[{{ $index }}][q]" class="form-control mb-1" placeholder="Question" value="{{ $qa['q'] }}">
                                    <textarea name="sec3_qa[{{ $index }}][a]" class="form-control mb-1" placeholder="Answer">{{ $qa['a'] }}</textarea>
                                    <button type="button" class="btn btn-sm btn-danger remove-qa">Remove Item</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header bg-warning"><h5>4. Highlights Title Section</h5></div>
                    <div class="card-body">
                        <x-form.text name="sec4_title" label="Title" :value="$data->metadata['sec4_title'] ?? ''" />
                        <x-form.text name="sec4_subtitle" label="Subtitle" :value="$data->metadata['sec4_subtitle'] ?? ''" />
                    </div>
                </div>

                <div class="card shadow mb-4 border-primary">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">5. Title, Subtitle & Q&A</h5>
                        <button type="button" class="btn btn-sm btn-light add-qa" data-container="qa-5" data-name="sec5_qa">Add Q&A Item</button>
                    </div>
                    <div class="card-body">
                        <x-form.text name="sec5_title" label="Title" :value="$data->metadata['sec5_title'] ?? ''" />
                        <x-form.text name="sec5_subtitle" label="Subtitle" :value="$data->metadata['sec5_subtitle'] ?? ''" />
                        <div id="qa-5" class="mt-3">
                            @foreach($data->metadata['sec5_qa'] ?? [] as $index => $qa)
                                <div class="qa-item border p-3 mb-2 bg-light">
                                    <input type="text" name="sec5_qa[{{ $index }}][q]" class="form-control mb-1" placeholder="Question" value="{{ $qa['q'] }}">
                                    <textarea name="sec5_qa[{{ $index }}][a]" class="form-control mb-1" placeholder="Answer">{{ $qa['a'] }}</textarea>
                                    <button type="button" class="btn btn-sm btn-danger remove-qa">Remove Item</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="text-center mb-5 sticky-bottom bg-white p-3 shadow-lg border-top">
                    <button type="submit" class="btn btn-success btn-lg px-5">UPDATE ALL SECTIONS</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('click', function(e) {
        // Add Q&A
        if (e.target.classList.contains('add-qa')) {
            const containerId = e.target.getAttribute('data-container');
            const namePrefix = e.target.getAttribute('data-name');
            const container = document.getElementById(containerId);
            const index = Date.now(); // Unique index for new items

            const html = `
                <div class="qa-item border p-3 mb-2 bg-light shadow-sm">
                    <input type="text" name="${namePrefix}[${index}][q]" class="form-control mb-1" placeholder="Question" required>
                    <textarea name="${namePrefix}[${index}][a]" class="form-control mb-1" placeholder="Answer" required></textarea>
                    <button type="button" class="btn btn-sm btn-danger remove-qa">Remove Item</button>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
        }

        // Remove Q&A with SweetAlert
        if (e.target.classList.contains('remove-qa')) {
            const element = e.target.closest('.qa-item');
            Swal.fire({
                title: 'Delete this item?',
                text: "This will remove the item from the list.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    element.remove();
                }
            });
        }
    });
</script>
@endpush
