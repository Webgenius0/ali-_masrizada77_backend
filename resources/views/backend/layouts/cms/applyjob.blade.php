@extends('backend.app')

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
           <div class="page-header">
    <h1 class="page-title">CMS: Get In Touch Sidebar</h1>
    <div class="ms-auto">
        <div class="btn-list">
            <a href="?type=english"
               class="btn {{ (request('type') == 'english' || !request('type')) ? 'btn-primary' : 'btn-outline-primary' }} btn-pill">
                <span class="me-1">🇺🇸</span> English
            </a>

            <a href="?type=de"
               class="btn {{ request('type') == 'de' ? 'btn-info' : 'btn-outline-info' }} btn-pill">
                <span class="me-1">🇩🇪</span> German (DE)
            </a>
        </div>
    </div>
</div>
            <div class="card">
                <form action="{{ route('admin.cms.home.applyjob.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="type" value="{{ request('type') ?? 'english' }}">
                    <input type="hidden" name="section" value="get-in-touch">

                    <div class="card-header border-bottom">
                        <h3 class="card-title">Editing Content for: <b class="text-primary">{{ strtoupper(request('type') ?? 'english') }}</b></h3>
                    </div>

                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-label">Main Title</label>
                            <input type="text" name="contact_title" class="form-control"
                                   value="{{ $data->metadata['contact_title'] ?? 'We are always open for new Talents.' }}">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="contact_desc" class="form-control" rows="3">{{ $data->metadata['contact_desc'] ?? '' }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Support Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fe fe-mail"></i></span>
                                        <input type="email" name="contact_email" class="form-control"
                                               value="{{ $data->metadata['contact_email'] ?? 'Support@TheDignityDraw.org' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Contact Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fe fe-phone"></i></span>
                                        <input type="text" name="contact_phone" class="form-control"
                                               value="{{ $data->metadata['contact_phone'] ?? '408-858-9300' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <button type="submit" class="btn btn-primary">Update {{ strtoupper(request('type') ?? 'english') }} Content</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
