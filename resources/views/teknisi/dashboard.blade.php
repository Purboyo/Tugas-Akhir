@extends('teknisi.app')

@section('content')

{{-- Page Heading --}}
            <!-- row -->
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">Today Expenses </div>
                                    <div class="stat-digit"> <i class="fa fa-usd"></i>8500</div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-success w-85" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">Income Detail</div>
                                    <div class="stat-digit"> <i class="fa fa-usd"></i>7800</div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-primary w-75" role="progressbar" aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">Task Completed</div>
                                    <div class="stat-digit"> <i class="fa fa-usd"></i> 500</div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-warning w-50" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        <div class="card">
                            <div class="stat-widget-two card-body">
                                <div class="stat-content">
                                    <div class="stat-text">Task Completed</div>
                                    <div class="stat-digit"> <i class="fa fa-usd"></i>650</div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-danger w-65" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                        <!-- /# card -->
                        
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    @if(Auth::user()->role === 'admin')
                                        Reminder Hari Ini
                                    @else
                                        Reminder Kamu
                                    @endif
                                </h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    @forelse($todayReminders as $reminder)
                                        <li class="list-group-item d-flex justify-content-between align-items-center 
                                            {{ Auth::user()->role === 'admin' ? 'bg-info text-white' : '' }}">
                                            <div>
                                                <strong>
                                                    @if(Auth::user()->role === 'admin')
                                                        {{ $reminder->user->name }}:
                                                    @endif
                                                    {{ $reminder->title }}
                                                </strong>
                                                <br>
                                                <small>{{ $reminder->reminder_date->format('d M Y') }}</small>
                                            </div>

                                            @if(Auth::user()->role === 'admin')
                                                <form action="{{ route('admin.reminder.destroy', $reminder->id) }}" method="POST" onsubmit="return confirm('Hapus reminder ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-light">Hapus</button>
                                                </form>
                                            @endif
                                        </li>
                                    @empty
                                        <li class="list-group-item text-muted">Tidak ada reminder.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>


@endsection