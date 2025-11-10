@php
$container = 'container-xxl';
@endphp

@extends('layouts/contentNavbarLayout')

@section('title', 'My Profile')

{{-- Link to the custom profile stylesheet --}}
<link rel="stylesheet" href="{{ asset('assets/css/profile.css') }}">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@section('content')
<div class="{{ $container }} py-4 profile-page">
    <!-- Profile Header -->
  <div class="profile-header-card mb-4">

  <!-- Left Section: Avatar + Info -->
  <div class="d-flex align-items-center">
    <div class="me-3">
        <img src="{{ asset($admin->profile->profile_picture_path ?? 'assets/images/slsu_logo.png') }}"
            alt="Avatar" class="profile-avatar" style="cursor: pointer;"
            data-bs-toggle="modal" data-bs-target="#avatarModal">
    </div>

  <!-- Avatar Modal -->
  <div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content bg-transparent border-0 d-flex justify-content-center align-items-center">
        <div class="modal-body p-0 text-center">
          <img src="{{ asset($admin->profile->profile_picture_path ?? 'assets/images/slsu_logo.png') }}"
              alt="Avatar"
              class="img-fluid rounded"
              style="max-width: 95%; max-height: 95vh; margin: auto; display: block;">
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>




   <div class="flex-grow-1">
      <h4 class="profile-username mb-1">
        {{ $admin->profile->first_name ?? '' }}
        {{ $admin->profile->middle_name ?? '' }}
         {{ $admin->profile->last_name ?? '' }}
      </h4>

      <p class="profile-bio text-muted mb-1 d-inline">
          <i class="mdi mdi-map-marker-outline me-1"></i>
          Southern Leyte State University |
      </p>

      <h1 class="profile-role mb-1 d-inline ms-2">
        {{ $admin->account_role ?? '' }}
     </h1>





      {{-- <p class="profile-location text-muted mb-0">
        <i class="mdi mdi-map-marker-outline"></i>
        {{ $admin->profile->address ?? '' }}
      </p> --}}

      {{-- <p class="profile-bio text-muted mt-2 mb-0">
        {{ $admin->profile->office ?? '' }}
      </p> --}}
    </div>

  </div>




  <!-- Right Section: Edit Box -->
<div class="edit-box text-center">
  <button type="button" class="edit-box" data-bs-toggle="modal" data-bs-target="#editProfileModal">
    <i class="mdi mdi-pencil-outline me-1"></i> Edit
  </button>
</div>


<!-- Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">


          @csrf
          @method('PUT')

          <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name"
                  value="{{ $admin->profile->first_name ?? '' }}" placeholder="Enter your first name">
          </div>

           <div class="mb-3">
            <label for="middle_name" class="form-label">Middle Name</label>
            <input type="text" class="form-control" id="middle_name" name="middle_name"
                  value="{{ $admin->profile->middle_name ?? '' }}" placeholder="Enter your middle name">
          </div>

          <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name"
                  value="{{ $admin->profile->last_name ?? '' }}" placeholder="Enter your last name">
          </div>

          <div class="mb-3">
            <label for="contact_number" class="form-label">Contact Number</label>
            <input type="text" class="form-control" id="contact_number" name="contact_number"
                  value="{{$admin->profile->contact_number ?? ''}}" placeholder="Enter your contact number">
          </div>

          <div class="mb-3">
            <label for="address" class="form-label">Address (Barangay,Municipality,Province)</label>
            <input type="text" class="form-control" id="address" name="address"
                  value="{{ $admin->profile->address ?? '' }}" placeholder="Enter your address">
          </div>

          <div class="mb-3">
            <label for="office" class="form-label">Office</label>
            <input type="text" class="form-control" id="office" name="office"
                  value="{{ $admin->profile->office ?? ''}}" placeholder="Enter your office">
          </div>

         <div class="mb-3">
            <label for="avatar" class="form-label">Avatar</label>
            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
        </div>


          <!-- Add more fields as needed -->

          <div class="text-end">
            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
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
                    <div class="personal-detail-card">
                        <div class="personal-card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Personal Details</h5>
                        </div>
                        <div class="personal-card-body">
                            <p><strong>First Name  :</strong> {{ $admin->profile->first_name ?? 'none'  }}</p>
                            <p><strong>Middle Name:</strong> {{ $admin->profile->middle_name ?? 'none'  }}</p>
                            <p><strong>Last Name  :</strong> {{ $admin->profile->last_name ?? 'none'  }}</p>
                            <p><strong>Contact #  :</strong> {{ $admin->profile->contact_number ?? 'none' }}</p>
                            <p><strong>Email    :</strong> {{ $admin->email ?? 'none' }}</p>
                            <p><strong>Address    :</strong> {{ $admin->profile->address ?? 'none' }}</p>
                        </div>
                    </div>
                </div>

                <!--Other Details Card 2 -->
                <div class="col-md-6 mb-4">
                   <div class="other-detail-card">
                        <div class="other-card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Other Details</h5>
                        </div>
                        <div class="other-card-body">
                            <p><strong>Office     :</strong> {{ $admin->profile->office ?? 'none' }}</p>
                            <p><strong>Joining Date:</strong> {{ $admin->created_at->format('Y-m-d') ?? '' }}</p>
                            <p><strong>Status:</strong> {{ $admin->status ?? 'Active' }}</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection
