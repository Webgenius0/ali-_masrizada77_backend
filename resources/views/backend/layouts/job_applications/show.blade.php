@extends('backend.app', ['title' => 'Application Details'])

@section('content')
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <div class="page-header">
                <div>
                    <h1 class="page-title">Application Details</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"><i class="fe fe-home me-2 fs-14"></i>Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.home.getalljob.index') }}">Job Applications</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h3 class="card-title mb-0">Applicant: {{ $application->first_name }} {{ $application->last_name }}</h3>
                            <div class="card-options">
                                <a href="{{ route('admin.home.getalljob.index') }}" class="btn btn-sm btn-primary">
                                    <i class="fe fe-arrow-left me-2"></i>Back
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <h5 class="fw-bold text-primary mb-3">Personal Information</h5>
                                    <p><strong>First Name:</strong> {{ $application->first_name }}</p>
                                    <p><strong>Last Name:</strong> {{ $application->last_name }}</p>
                                    <p><strong>Email:</strong> {{ $application->email }}</p>
                                    <p><strong>Phone:</strong> {{ $application->phone_number }}</p>
                                    <p><strong>Country:</strong> {{ $application->country }}</p>
                                </div>

                                <div class="col-md-6 mb-4 border-start">
                                    <h5 class="fw-bold text-primary mb-3">Application Context</h5>
                                    <p><strong>Submitted At:</strong> {{ $application->created_at->format('d M, Y h:i A') }}</p>
                                    <p><strong>Status:</strong> <span class="badge bg-success-transparent text-success">Received</span></p>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <h5 class="fw-bold text-primary mb-3">Why NovaVoca?</h5>
                                    <div class="p-4 bg-light border  shadow-sm" style="white-space: pre-line;">
                                        {{-- আমরা এপিআই-তে 'why_novavoca' ডাটা 'most_recent_employer' কলামে সেভ করছি --}}
                                        {{ $application->most_recent_employer ?? 'No information provided.' }}
                                    </div>
                                </div>
                            </div>

                            {{-- যেহেতু রেজুমি নেই, তাই ডকুমেন্ট সেকশনটি শুধু তথ্যের জন্য রাখা হলো বা চাইলে আপনি এটি বাদ দিতে পারেন --}}
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="fw-bold text-primary mb-3">Submission Info</h5>
                                    <p class="text-muted small">This application was submitted via the online form. No physical documents were attached.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
