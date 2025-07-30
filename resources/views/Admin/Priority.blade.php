@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h4 class="mb-0">
                            <i class="fas fa-star me-2"></i>
                            Tabel Prioritas User
                        </h4>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card-body">


                        <!-- Priority Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-sm" id="priorityTable">
                                <thead class="table-warning">
                                    <tr>
                                        <th width="40">
                                        </th>
                                        <th class="text-dark">No</th>
                                        <th class="text-dark">Nama</th>
                                        <th class="text-dark">Subject</th>
                                        <th class="text-dark">Deskripsi</th>
                                        <th class="text-dark">Tanggal Ditambahkan</th>
                                        <th width="100" class="text-dark">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="priorityTableBody">
                                    @forelse ($prioritas as $index => $item)
                                        <tr>
                                            <td>
                                            </td>
                                            <td class="text-dark">{{ $index + 1 }}</td>
                                            <td class="text-dark">{{ $item->name }}</td>
                                            <td class="text-dark">{{ $item->ticket->subject ?? '-' }}</td>
                                            <td class="text-dark">{{ $item->ticket->description ?? '-' }}</td>
                                            <td class="text-dark">{{ \Carbon\Carbon::parse($item->added_at)->format('d-m-Y') }}
                                            </td>
                                            <td class="text-dark">
                                                <form action="{{ route('prioritas.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin hapus user ini dari prioritas?')"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr id="emptyRow">
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-star fa-2x mb-2 opacity-50"></i>
                                                <br>
                                                Belum ada user yang diprioritaskan
                                                <br>
                                                <small>Tambahkan user dari menu Manajemen User</small>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .form-check-input:checked {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .selected-priority-row {
            background-color: rgba(255, 193, 7, 0.2) !important;
        }

        .priority-number {
            font-weight: bold;
            color: #856404;
        }
    </style>


@endsection