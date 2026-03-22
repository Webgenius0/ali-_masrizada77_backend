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
                            <h3 class="card-title mb-0">Applicant Info: {{ $application->first_name }} {{ $application->last_name }}</h3>
                            <div class="card-options">
                                <a href="{{ route('admin.home.getalljob.index') }}" class="btn btn-sm btn-primary">Back</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <h5 class="fw-bold text-primary mb-3">Personal Information</h5>
                                    <p><strong>First Name:</strong> {{ $application->first_name }}</p>
                                    <p><strong>Last Name:</strong> {{ $application->last_name }}</p>
                                    <p><strong>Email Address:</strong> {{ $application->email }}</p>
                                    <p><strong>Phone Number:</strong> {{ $application->phone_number }}</p>
                                    <p><strong>Country:</strong> {{ $application->country }}</p>
                                </div>

                                <div class="col-md-6 mb-4 border-start">
                                    <h5 class="fw-bold text-primary mb-3">Professional Information</h5>
                                    <p><strong>Most Recent Employer:</strong> {{ $application->most_recent_employer }}</p>
                                    <p><strong>Most Recent Job Title:</strong> {{ $application->most_recent_job_title }}</p>
                                    <p><strong>Application Date:</strong> {{ $application->created_at->format('d M, Y h:i A') }}</p>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5 class="fw-bold text-primary mb-3">Submitted Documents</h5>
                                    <div class="btn-list">
                                        <a href="{{ asset($application->resume_path) }}" target="_blank" class="btn btn-success">
                                            <i class="fe fe-download me-2"></i>Download Resume (CV)
                                        </a>

                                        @if($application->cover_letter_path)
                                        <a href="{{ asset($application->cover_letter_path) }}" target="_blank" class="btn btn-warning text-white">
                                            <i class="fe fe-file-text me-2"></i>View Cover Letter
                                        </a>
                                        @else
                                        <span class="badge bg-light text-muted p-2">No Cover Letter Provided</span>
                                        @endif
                                    </div>
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
