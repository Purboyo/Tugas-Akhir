@extends(auth()->user()->role . '.app')

@section('content')
<div class="container-fluid mt-4 text-dark">
    <div class="row mb-4">
        <div class="col">
            <h2 class="font-weight-bold">Admin Dashboard</h2>
            <p class="text-muted">Welcome back! Here's a quick overview of the system status and activity.</p>
        </div>
    </div>

    <div class="row">
        {{-- Total Users --}}
        <div class="col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs text-primary text-uppercase mb-1">Total Users</div>
                        <div class="h4 font-weight-bold">{{ $users }}</div>
                        <small class="text-muted">All registered system users</small>
                    </div>
                    <i class="fa fa-users fa-2x text-primary"></i>
                </div>
            </div>
        </div>

        {{-- Reminders Completed --}}
        <div class="col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs text-success text-uppercase mb-1">Completed Reminders</div>
                        <div class="h4 font-weight-bold text-success">{{ $reminderCompleted }}</div>
                        <small class="text-muted">Tasks successfully completed</small>
                    </div>
                    <i class="fa fa-check-circle fa-2x text-success"></i>
                </div>
            </div>
        </div>

        {{-- Reminders Pending --}}
        <div class="col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs text-warning text-uppercase mb-1">Pending Reminders</div>
                        <div class="h4 font-weight-bold text-warning">{{ $reminderPending }}</div>
                        <small class="text-muted">Scheduled but not yet handled</small>
                    </div>
                    <i class="fa fa-hourglass fa-2x text-warning"></i>
                </div>
            </div>
        </div>

        {{-- Total Laboratories --}}
        <div class="col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs text-info text-uppercase mb-1">Laboratories</div>
                        <div class="h4 font-weight-bold">{{ $labs }}</div>
                        <small class="text-muted">Labs registered in the system</small>
                    </div>
                    <i class="fa fa-flask fa-2x text-info"></i>
                </div>
            </div>
        </div>

        {{-- Total Technicians --}}
        <div class="col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-xs text-dark text-uppercase mb-1">Technicians</div>
                        <div class="h4 font-weight-bold">{{ $technicians }}</div>
                        <small class="text-muted">Active technicians assigned to labs</small>
                    </div>
                    <i class="fa fa-wrench fa-2x text-dark"></i>
                  </div>
            </div>
        </div>
    </div>
</div>
@endsection
