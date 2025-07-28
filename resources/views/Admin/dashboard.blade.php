@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="text-3xl font-extrabold text-black mb-8 tracking-tight">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        {{-- On The List --}}
        <div class="bg-yellow-100 border-l-8 border-yellow-500 rounded-2xl shadow-md p-6 text-center transition-all hover:shadow-xl">
            <p class="text-lg font-medium text-yellow-700 mb-1">On The List</p>
            <p class="text-4xl font-extrabold text-yellow-800">{{ $onTheListTickets }}</p>
        </div>

        {{-- Pending --}}
        <div class="bg-blue-100 border-l-8 border-blue-500 rounded-2xl shadow-md p-6 text-center transition-all hover:shadow-xl">
            <p class="text-lg font-medium text-blue-700 mb-1">Pending</p>
            <p class="text-4xl font-extrabold text-blue-800">{{ $pendingTickets }}</p>
        </div>

        {{-- Solved --}}
        <div class="bg-green-100 border-l-8 border-green-500 rounded-2xl shadow-md p-6 text-center transition-all hover:shadow-xl">
            <p class="text-lg font-medium text-green-700 mb-1">Solved</p>
            <p class="text-4xl font-extrabold text-green-800">{{ $solvedTickets }}</p>
        </div>

        {{-- Total Tickets --}}
        <div class="bg-red-100 border-l-8 border-red-500 rounded-2xl shadow-md p-6 text-center transition-all hover:shadow-xl">
            <p class="text-lg font-medium text-red-700 mb-1">Total Tickets</p>
            <p class="text-4xl font-extrabold text-red-800">{{ $withoutAgentTickets }}</p>
        </div>
    </div>

   <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    {{-- Bar Chart --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Statistik Tiket Solved per Hari</h2>
        <div class="h-64">
            <canvas id="barChart" class="w-full h-full"></canvas>
        </div>
    </div>

    {{-- Doughnut Chart --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-lg font-semibold mb-4 text-gray-800">Komposisi Status Tiket</h2>
        <div class="h-64">
            <canvas id="doughnutChart" class="w-full h-full"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const barCtx = document.getElementById('barChart').getContext('2d');
    const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');

    // Bar Chart - Tiket Solved per Hari
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Solved Tickets',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(34, 197, 94, 0.7)',
                borderColor: 'rgba(34, 197, 94, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 2,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#000' },
                    title: {
                        display: true,
                        text: 'Jumlah Tiket',
                        color: '#000'
                    }
                },
                x: {
                    ticks: { color: '#000' },
                    title: {
                        display: true,
                        text: 'Tanggal',
                        color: '#000'
                    }
                }
            },
            plugins: {
                legend: { labels: { color: '#000' } }
            }
        }
    });

    // Doughnut Chart - Komposisi Status
    new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            labels: ['On The List', 'Pending', 'Solved','Reject'],
            datasets: [{
                label: 'Total Tiket',
                data: [
                    {{ $onTheListTickets }},
                    {{ $pendingTickets }},
                    {{ $solvedTickets }},
                    {{ $rejectTickets }}
                ],
                backgroundColor: [
                    'rgba(253, 224, 71, 0.8)',   // kuning
                    'rgba(96, 165, 250, 0.8)',   // biru
                    'rgba(34, 197, 94, 0.9)',   // hijau
                    'rgba(239, 68, 68, 0.9)'   // merah
                ],
                borderColor: [
                    'rgba(202, 138, 4, 1)',
                    'rgba(30, 64, 175, 1)',
                    'rgba(22, 163, 74, 1)',
                    'rgba(220, 38, 38, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 1.5,
            plugins: {
                legend: { labels: { color: '#000' } }
            }
        }
    });
});
</script>
@endpush