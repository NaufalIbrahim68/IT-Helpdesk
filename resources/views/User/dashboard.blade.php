@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center mb-2">
                @php
                $user = session('user');
                @endphp
                <h4 class="mb-0 me-2 text-black">
                    Halo, {{ Auth::user()->name ?? 'User' }}
                </h4>
                <span style="font-size: 1.4rem;">👋</span>
            </div>
            <h5 class="mb-4 text-black">Berikut adalah notifikasi status pengerjaan Kamu</h5>
        </div>
    </div>
   <div class="row">
        @forelse($subjects as $subject)
        <div class="col-md-6 col-lg-4 mb-4">
            @php
                $status = strtolower($subject->status ?? 'pending');
                $statusClass = str_replace(' ', '-', $status);

                $badgeClass = match($status) {
                    'pending' => 'bg-warning text-dark',
                    'in progress' => 'bg-info text-white',
                    'done' => 'bg-success text-white',
                    'reject' => 'bg-danger text-white',
                    'on the list' => 'bg-purple text-white',
                    default => 'bg-secondary text-white',
                };
            @endphp

            <div class="card shadow-lg border-0 h-100 position-relative overflow-hidden status-card-{{ $statusClass }}"
                style="border-radius: 15px; transition: all 0.3s ease;">
                
                <!-- Decorative elements -->
                <div class="position-absolute top-0 end-0 opacity-25"
                    style="width: 100px; height: 100px; background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%); border-radius: 50%; transform: translate(30px, -30px);"></div>

                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title fw-bold mb-0" style="color: #2c3e50;">
                            {{ $subject->subject ?? 'Tidak ada subjek' }}
                        </h5>
                        <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill"
                            style="font-size: 0.75rem; font-weight: 600;">
                            {{ ucfirst($subject->status ?? 'pending') }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <p class="card-text mb-2" style="color: #34495e;">
                            <i class="fas fa-calendar-alt me-2" style="color: #3498db;"></i>
                            <strong>Dibuat:</strong>
                            {{ isset($subject->created_at) ? \Carbon\Carbon::parse($subject->created_at)->format('d M Y, H:i') : 'Tidak diketahui' }}
                        </p>
                    </div>

                    @if(isset($subject->description) && !empty($subject->description))
                    <div class="mt-3 p-3 rounded-3" style="background: rgba(255,255,255,0.6); backdrop-filter: blur(10px);">
                        <p class="card-text mb-0 small" style="color: #2c3e50; line-height: 1.5;">
                            <i class="fas fa-info-circle me-2" style="color: #3498db;"></i>
                            {{ $subject->description }}
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Hover effect -->
                <div class="position-absolute bottom-0 start-0 w-100 h-2"
                    style="background: linear-gradient(90deg, #3498db 0%, #2ecc71 50%, #f39c12 100%); opacity: 0; transition: opacity 0.3s ease;"></div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center p-4"
                style="background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%); border-radius: 15px;">
                <i class="fas fa-info-circle me-3" style="font-size: 1.5rem; color: #1976D2;"></i>
                <div>
                    <h6 class="mb-1" style="color: #1565C0;">Belum ada notifikasi</h6>
                    <p class="mb-0" style="color: #1976D2;">Belum ada notifikasi pengerjaan yang tersedia saat ini.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>


<style>

   
    .bg-purple {
        background-color: #9b59b6 !important;
        color: #fff !important;
    }


    .status-card-pending {
        background: linear-gradient(135deg, #FFE082 0%, #FFD54F 100%);
    }

    .status-card-in-progress {
        background: linear-gradient(135deg, #81D4FA 0%, #4FC3F7 100%);
    }

    .status-card-done {
        background: linear-gradient(135deg, #A5D6A7 0%, #81C784 100%);
    }

    .status-card-reject {
        background: linear-gradient(135deg, #EF9A9A 0%, #E57373 100%);
    }

    .status-card-on-the-list {
        background: linear-gradient(135deg, #CE93D8 0%, #BA68C8 100%);
    }

    .status-card-default {
        background: linear-gradient(135deg, #F5F5F5 0%, #E0E0E0 100%);
    }

    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
    }

    .card:hover .position-absolute.bottom-0 {
        opacity: 1 !important;
    }

    .badge {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .alert {
        border-left: 4px solid #1976D2;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1rem !important;
        }
    }

    .nav-item.active>.nav-link {
        background-color: #f0f0f0;
        font-weight: bold;
        border-left: 4px solid #000;
    }
</style>
@endsection