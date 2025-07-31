@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-black mb-8 tracking-tight">Daftar Ticketing</h1>
                    <p class="text-gray-600">Kelola dan pantau status tiket support</p>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                        Total: {{ $tickets->total() }} tiket
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('ticketing.index') }}" class="flex flex-wrap items-center gap-4">

                <!-- Filter Status -->
                <div class="flex items-center space-x-2">
                    <label for="status" class="text-sm font-medium text-dark">Status:</label>
                    <select name="status" id="status"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">-- Semua Status --</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>🔄 Belum Ditangani
                        </option>
                        <option value="in progress" {{ request('status') == 'in progress' ? 'selected' : '' }}>⚡ Sedang
                            Ditangani</option>
                        <option value="solved" {{ request('status') == 'solved' ? 'selected' : '' }}>✅ Selesai</option>
                        <option value="on the list" {{ request('status') == 'on the list' ? 'selected' : '' }}>📋 Dalam Daftar
                        </option>
                        <option value="reject" {{ request('status') == 'reject' ? 'selected' : '' }}>❌ Ditolak</option>
                    </select>
                </div>

                <!-- Search -->
                <div class="flex items-center space-x-2 ml-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Cari nama, subject..."
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                    <!-- Tombol Submit -->
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Filter
                    </button>

                    <!-- Tombol Reset -->
                    <a href="{{ route('ticketing.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Table Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-[1000px] divide-y divide-gray-200 table table-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Nama
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Subject
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">
                                Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Tanggal
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-black uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($tickets as $ticket)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-black">{{ $ticket->name ?? 'Unknown User' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-black font-medium">{{ $ticket->subject }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-black max-w-xs truncate" title="{{ $ticket->description }}">
                                        {{ $ticket->description }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <form action="{{ route('ticketing.updateStatus', $ticket->ticketing_id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="status-select border-0 rounded-full text-xs font-medium px-3 py-1 focus:ring-2 focus:ring-offset-2 cursor-pointer transition-all duration-200
                                                                    @if($ticket->status == 'pending') bg-yellow-100 text-yellow-800 focus:ring-yellow-500
                                                                    @elseif($ticket->status == 'in progress') bg-blue-100 text-blue-800 focus:ring-blue-500
                                                                    @elseif($ticket->status == 'solved') bg-green-100 text-green-800 focus:ring-green-500
                                                                    @elseif($ticket->status == 'on the list') bg-purple-100 text-purple-800 focus:ring-purple-500
                                                                    @elseif($ticket->status == 'reject') bg-red-100 text-red-800 focus:ring-red-500
                                                                    @else bg-gray-100 text-gray-800 focus:ring-gray-500
                                                                    @endif" onchange="this.form.submit()">
                                            <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>🔄 Belum
                                                Ditangani</option>
                                            <option value="in progress" {{ $ticket->status == 'in progress' ? 'selected' : '' }}>⚡
                                                Sedang Ditangani</option>
                                            <option value="solved" {{ $ticket->status == 'solved' ? 'selected' : '' }}>✅ Sudah
                                                Selesai</option>
                                            <option value="on the list" {{ $ticket->status == 'on the list' ? 'selected' : '' }}>
                                                📋 Dalam Daftar</option>
                                            <option value="reject" {{ $ticket->status == 'reject' ? 'selected' : '' }}>❌ Ditolak
                                            </option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">
                                    {{ $ticket->created_at->format('d-m-Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <form action="{{ route('ticketing.destroy', $ticket->ticketing_id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus tiket ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 hover:bg-red-200 transition-colors duration-150"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('ticketing.addToPriority', $ticket->ticketing_id) }}" method="POST"
                                        class="d-inline ml-1">
                                        @csrf
                                        <button type="submit"
                                            class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 hover:bg-yellow-200 transition-colors duration-150"
                                            title="Tambah ke Prioritas">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-black text-sm">
                                    Tidak ada tiket ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Enhanced Pagination -->
            @if($tickets->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    <!-- Mobile Pagination -->
                    <div class="flex items-center justify-between sm:hidden">
                        <div class="flex-1 flex justify-between">
                            @if ($tickets->onFirstPage())
                                <span
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $tickets->appends(request()->query())->previousPageUrl() }}"
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                    Previous
                                </a>
                            @endif

                            @if ($tickets->hasMorePages())
                                <a href="{{ $tickets->appends(request()->query())->nextPageUrl() }}"
                                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 leading-5 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                                    Next
                                </a>
                            @else
                                <span
                                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default leading-5 rounded-md">
                                    Next
                                </span>
                            @endif
                        </div>

                        <!-- Mobile Page Info -->
                        <div class="text-center mt-3">
                            <p class="text-sm text-gray-700">
                                Halaman {{ $tickets->currentPage() }} dari {{ $tickets->lastPage() }}
                            </p>
                        </div>
                    </div>

                    <!-- Desktop Pagination -->
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Menampilkan
                                <span class="font-medium">{{ $tickets->firstItem() ?? 0 }}</span>
                                sampai
                                <span class="font-medium">{{ $tickets->lastItem() ?? 0 }}</span>
                                dari
                                <span class="font-medium">{{ $tickets->total() }}</span>
                                tiket
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                {{-- Previous Page Link --}}
                                @if ($tickets->onFirstPage())
                                    <span
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-default">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @else
                                    <a href="{{ $tickets->appends(request()->query())->previousPageUrl() }}"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- First Page Link --}}
                                @if($tickets->currentPage() > 3)
                                    <a href="{{ $tickets->appends(request()->query())->url(1) }}"
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                        1
                                    </a>
                                    @if($tickets->currentPage() > 4)
                                        <span
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                            ...
                                        </span>
                                    @endif
                                @endif

                                {{-- Pagination Elements --}}
                                @for($i = max(1, $tickets->currentPage() - 2); $i <= min($tickets->lastPage(), $tickets->currentPage() + 2); $i++)
                                    @if ($i == $tickets->currentPage())
                                        <span
                                            class="relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600 cursor-default">
                                            {{ $i }}
                                        </span>
                                    @else
                                        <a href="{{ $tickets->appends(request()->query())->url($i) }}"
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                            {{ $i }}
                                        </a>
                                    @endif
                                @endfor

                                {{-- Last Page Link --}}
                                @if($tickets->currentPage() < $tickets->lastPage() - 2)
                                    @if($tickets->currentPage() < $tickets->lastPage() - 3)
                                        <span
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                            ...
                                        </span>
                                    @endif
                                    <a href="{{ $tickets->appends(request()->query())->url($tickets->lastPage()) }}"
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                        {{ $tickets->lastPage() }}
                                    </a>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($tickets->hasMorePages())
                                    <a href="{{ $tickets->appends(request()->query())->nextPageUrl() }}"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @else
                                    <span
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 cursor-default">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <style>
            .status-select {
                appearance: none;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
                background-position: right 0.5rem center;
                background-repeat: no-repeat;
                background-size: 1.5em 1.5em;
                padding-right: 2.5rem;
            }

            .status-select:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            /* Custom scrollbar for mobile */
            .overflow-x-auto::-webkit-scrollbar {
                height: 8px;
            }

            .overflow-x-auto::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }

            .overflow-x-auto::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 10px;
            }

            .overflow-x-auto::-webkit-scrollbar-thumb:hover {
                background: #555;
            }

            /* Loading state for pagination */
            .pagination-loading {
                opacity: 0.6;
                pointer-events: none;
            }
        </style>
@endsection