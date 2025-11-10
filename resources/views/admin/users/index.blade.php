@php
  $container = 'container-xxl';
  $containerNav = 'container-xxl';
@endphp

@extends('layouts/contentNavbarLayout')
@include('admin.users.js')

@section('title', 'Users Management')

<style>
  th a {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: inherit;
  }

  th a:hover {
    text-decoration: underline;
  }
</style>

@section('content')
  <div class="{{ $container }}">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">User Accounts</h5>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
          <i class="bx bx-plus"></i> Create Account
        </a>
      </div>

      @if(session('success'))
        <div class="alert alert-success m-3">{{ session('success') }}</div>
      @endif

      <div class="table-responsive text-nowrap">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr>
              {{-- Static headers --}}
              <th>ID</th>
              <th>Username</th>
              <th>Email</th>

              {{-- Sortable: Role --}}
              <th>
                <a href="{{ route('users.index', ['sort' => 'account_role', 'direction' => ($sortField === 'account_role' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}"
                   class="text-decoration-none text-dark fw-semibold">
                  Role
                  @if($sortField === 'account_role')
                    @if($sortDirection === 'asc')
                      ▲
                    @else
                      ▼
                    @endif
                  @endif
                </a>
              </th>

              {{-- Sortable: Date Created --}}
              <th>
                <a href="{{ route('users.index', ['sort' => 'created_at', 'direction' => ($sortField === 'created_at' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}"
                   class="text-decoration-none text-dark fw-semibold">
                  Date Created
                  @if($sortField === 'created_at')
                    @if($sortDirection === 'asc')
                      ▲
                    @else
                      ▼
                    @endif
                  @endif
                </a>
              </th>

              {{-- Sortable: Time --}}
              <th>
                <a href="{{ route('users.index', ['sort' => 'created_at', 'direction' => ($sortField === 'created_at' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}"
                   class="text-decoration-none text-dark fw-semibold">
                  Time
                  @if($sortField === 'created_at')
                    @if($sortDirection === 'asc')
                      ▲
                    @else
                      ▼
                    @endif
                  @endif
                </a>
              </th>

              {{-- Static header: Actions --}}
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            @forelse($users as $user)
              <tr>
                <td>{{ $user->user_id }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="badge bg-label-info">{{ $user->account_role }}</span></td>
                <td>{{ $user->created_at->format('Y-m-d') }}</td>
                <td>{{ $user->created_at->format('H:i:s') }}</td>
                <td>
                  <a href="{{ route('users.edit', $user->user_id) }}" class="btn btn-sm btn-warning">
                    <i class="bx bx-edit"></i> Edit
                  </a>
                  <button type="button" class="btn btn-danger btn-sm"
                          data-bs-toggle="modal"
                          data-bs-target="#confirmDeleteModal"
                          data-user-id="{{ $user->user_id }}"
                          data-username="{{ $user->username }}">
                    <i class="bx bx-trash"></i> Delete
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted">No users found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Success Modal --}}
  @if(session('success'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-success text-white">
            <h5 class="modal-title" id="successModalLabel">Success</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            {{ session('success') }}
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
      });
    </script>
  @endif

  {{-- Delete Confirmation Modal --}}
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form method="POST" id="deleteUserForm">
        @csrf
        @method('DELETE')
        <div class="modal-content">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="confirmDeleteLabel">Confirm Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p id="deleteMessage">Are you sure you want to delete this account?</p>

            <div class="mb-3">
              <label for="adminPassword" class="form-label">Enter your admin password to confirm:</label>
              <div class="input-group">
                <input type="password" class="form-control" id="adminPassword" name="admin_password" required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                  <i class="bx bx-show"></i>
                </button>
              </div>
            </div>

            <div id="passwordError" class="text-danger d-none">Incorrect password. Please try again.</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </div>
        </div>
      </form>
    </div>
  </div>

@endsection
