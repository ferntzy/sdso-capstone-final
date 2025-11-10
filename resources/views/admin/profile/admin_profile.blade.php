@php
$container = 'container-xxl';
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'My Profile')

{{-- Link to the custom profile stylesheet --}}
<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">

@section('content')
<div class="{{ $container }} py-4 profile-page">
    <!-- Profile Header -->
    <div class="profile-card mb-4">
        <div class="profile-card-body d-flex align-items-center">
            <div class="me-3">
                <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Profile Picture" class="profile-avatar rounded-circle" width="100" height="100">
            </div>
            <div class="flex-grow-1">
                <h4 class="profile-username mb-1">{{ $admin->username ?? 'Admin Name' }}</h4>
                <p class="profile-role text-muted mb-1">{{ $admin->account_role ?? 'Role' }}</p>
                <p class="profile-location text-muted mb-0">
                    <i class="mdi mdi-map-marker-outline"></i> {{ $admin->location ?? 'Location' }}
                </p>
                <p class="profile-bio text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit...</p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="profile-tabs nav nav-tabs mb-4" id="profileTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="about-tab" data-bs-toggle="tab" href="#about" role="tab">About</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="password-tab" data-bs-toggle="tab" href="#password" role="tab">Password</a>
        </li>
    </ul>

    <div class="tab-content" id="profileTabsContent">
        <!-- About Tab -->
        <div class="tab-pane fade show active" id="about" role="tabpanel">
            <div class="row">
                <!-- Personal Details Card 1 -->
                <div class="col-md-6 mb-4">
                    <div class="profile-card">
                        <div class="profile-card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Personal Details</h5>
                        </div>
                        <div class="profile-card-body">
                            <p><strong>Name:</strong> {{ $admin->name ?? 'John Doe' }}</p>
                            <p><strong>Date of Birth:</strong> {{ $admin->dob ?? '01 Jan 1990' }}</p>
                            <p><strong>Email ID:</strong> {{ $admin->email ?? 'admin@example.com' }}</p>
                            <p><strong>Mobile:</strong> {{ $admin->mobile ?? '123-456-7890' }}</p>
                            <p><strong>Address:</strong> {{ $admin->address ?? '123 Street, City, Country' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Personal Details Card 2 -->
                <div class="col-md-6 mb-4">
                    <div class="profile-card">
                        <div class="profile-card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Other Details</h5>
                        </div>
                        <div class="profile-card-body">
                            <p><strong>Department:</strong> {{ $admin->department ?? 'Management' }}</p>
                            <p><strong>Joining Date:</strong> {{ $admin->joining_date ?? '01 Jan 2020' }}</p>
                            <p><strong>Role:</strong> {{ $admin->account_role ?? 'Admin' }}</p>
                            <p><strong>Status:</strong> {{ $admin->status ?? 'Active' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Password Tab -->
        <div class="tab-pane fade" id="password" role="tabpanel">
            <div class="profile-card">
                <div class="profile-card-body">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
