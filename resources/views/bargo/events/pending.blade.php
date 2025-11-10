@extends('layouts/contentNavbarLayout')

@section('title', 'Pending Events')

@section('content')
  <div class="container py-4">
    <h2 class="mb-4">Pending Events for Approval</h2>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if($pendingReviews->isEmpty())
      <div class="alert alert-info">No pending events at the moment.</div>
    @else
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="table-primary">
            <tr>
              <th>#</th>
              <th>Event Name</th>
              <th>Organization</th>
              <th>Submitted At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pendingReviews as $flow)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $flow->permit->name ?? 'N/A' }}</td>
                <td>{{ $flow->permit->organization->name ?? 'N/A' }}</td>
                <td>{{ $flow->created_at->format('F j, Y H:i') }}</td>
                <td>
                  <a href="{{ route('bargo.view.permit', $flow->permit->hashed_id) }}" class="btn btn-sm btn-primary"
                    target="_blank">View PDF</a>

                  <form action="{{ route('bargo.approve', $flow->approval_id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                  </form>

                  <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                    data-bs-target="#rejectModal{{ $flow->approval_id }}">Reject</button>

                  <!-- Reject Modal -->
                  <div class="modal fade" id="rejectModal{{ $flow->approval_id }}" tabindex="-1"
                    aria-labelledby="rejectModalLabel{{ $flow->approval_id }}" aria-hidden="true">
                    <div class="modal-dialog">
                      <form action="{{ route('bargo.reject', $flow->approval_id) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="rejectModalLabel{{ $flow->approval_id }}">Reject Event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <div class="mb-3">
                              <label for="comments" class="form-label">Comments</label>
                              <textarea name="comments" class="form-control" required></textarea>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>

                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
@endsection