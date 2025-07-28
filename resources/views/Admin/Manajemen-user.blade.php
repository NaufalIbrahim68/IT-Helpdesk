@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        Manajemen User
                    </h4>
                </div>
                <div class="card-body">
                        </div>
                    </div>
                    <!-- Users Table -->
                 <div class="table-responsive">
    <table class="table table-hover table-sm" id="usersTable">
        <thead class="table-dark">
            <tr>
                <th width="20">
                </th>
                <th>ID</th>
                <th>Nama</th>
                <th>NPK</th>
                <th>Department</th>
                <th>Division</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr data-user-id="{{ $user['id'] }}">
                    <td>

                    </td>
                    <td class="text-black">{{ $user['id'] }}</td>
                    <td class="text-black">{{ $user['fullname'] ?? '-' }}</td>
                    <td class="text-black">{{ $user['npk'] ?? '-' }}</td>
                    <td class="text-black">{{ $user['department'] ?? '-' }}</td>
                    <td class="text-black">{{ $user['division'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Tidak ada data pengguna</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>



                    <!-- Pagination (jika ada) -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                        <small class="text-muted">Menampilkan {{ is_countable($users) ? count($users) : 0 }} user</small>

                        </div>
                        <div>
                            <!-- Pagination links jika menggunakan paginate -->
                            {{-- {{ $users->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .selected-row {
        background-color: rgba(0, 123, 255, 0.1) !important;
    }

    @media (max-width: 768px) {
        .btn-group {
            flex-direction: column;
            width: 100%;
        }

        .btn-group .btn {
            margin-bottom: 5px;
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let selectedUsers = [];

        // Elements
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        const addToPriorityBtn = document.getElementById('addToPriority');
        const selectAllBtn = document.getElementById('selectAll');
        const clearSelectionBtn = document.getElementById('clearSelection');
        const selectedInfo = document.getElementById('selectedInfo');
        const selectedCount = document.getElementById('selectedCount');
        const searchInput = document.getElementById('searchInput');

        // Update UI berdasarkan selection
        function updateUI() {
            const selected = selectedUsers.length;
            selectedCount.textContent = selected;

            if (selected > 0) {
                selectedInfo.classList.remove('d-none');
                addToPriorityBtn.disabled = false;
            } else {
                selectedInfo.classList.add('d-none');
                addToPriorityBtn.disabled = true;
            }
        }

        // Handle checkbox changes
        userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const userId = parseInt(this.value);
                const userRow = this.closest('tr');

                if (this.checked) {
                    if (!selectedUsers.includes(userId)) {
                        selectedUsers.push(userId);
                        userRow.classList.add('selected-row');
                    }
                } else {
                    selectedUsers = selectedUsers.filter(id => id !== userId);
                    userRow.classList.remove('selected-row');
                }

                updateUI();
                updateSelectAllState();
            });
        });

        // Select all checkbox
        selectAllCheckbox.addEventListener('change', function() {
            if (this.checked) {
                selectAllVisible();
            } else {
                clearAllSelection();
            }
        });

        // Button handlers
        selectAllBtn.addEventListener('click', selectAllVisible);
        clearSelectionBtn.addEventListener('click', clearAllSelection);

        // Select all visible users
        function selectAllVisible() {
            selectedUsers = [];
            userCheckboxes.forEach(checkbox => {
                const userId = parseInt(checkbox.value);
                const userRow = checkbox.closest('tr');

                if (userRow.style.display !== 'none') {
                    checkbox.checked = true;
                    selectedUsers.push(userId);
                    userRow.classList.add('selected-row');
                }
            });
            updateUI();
            updateSelectAllState();
        }

        // Clear all selection
        function clearAllSelection() {
            selectedUsers = [];
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.closest('tr').classList.remove('selected-row');
            });
            updateUI();
            updateSelectAllState();
        }

        // Update select all checkbox state
        function updateSelectAllState() {
            const visibleCheckboxes = Array.from(userCheckboxes).filter(cb =>
                cb.closest('tr').style.display !== 'none'
            );
            const checkedVisible = visibleCheckboxes.filter(cb => cb.checked);

            selectAllCheckbox.checked = visibleCheckboxes.length > 0 &&
                checkedVisible.length === visibleCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedVisible.length > 0 &&
                checkedVisible.length < visibleCheckboxes.length;
        }

        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#usersTable tbody tr');

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                    // Uncheck hidden rows
                    const checkbox = row.querySelector('.user-checkbox');
                    if (checkbox && checkbox.checked) {
                        checkbox.checked = false;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                }
            });

            updateSelectAllState();
        });

        // Add to priority (akan mengirim ke server atau localStorage)
        addToPriorityBtn.addEventListener('click', function() {
            if (selectedUsers.length === 0) return;

            // Simulasi pengiriman ke server
            const userData = selectedUsers.map(userId => {
                const row = document.querySelector(`tr[data-user-id="${userId}"]`);
                return {
                    id: userId,
                    name: row.children[2].textContent.trim(),
                    npk: row.children[3].textContent.trim(),
                    department: row.children[4].textContent.trim()
                };
            });

            // Simpan ke localStorage untuk demo (dalam implementasi nyata, kirim ke server)
            let priorityUsers = JSON.parse(localStorage.getItem('priorityUsers') || '[]');

            userData.forEach(user => {
                if (!priorityUsers.find(p => p.id === user.id_user)) {
                    priorityUsers.push({
                        ...user,
                        priority: priorityUsers.length + 1,
                        dateAdded: new Date().toLocaleString()
                    });
                }
            });

            localStorage.setItem('priorityUsers', JSON.stringify(priorityUsers));

            // Show success message
            showToast(`${selectedUsers.length} user berhasil ditambahkan ke prioritas!`, 'success');

            // Clear selection
            clearAllSelection();
        });

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `alert alert-${type} position-fixed`;
            toast.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            animation: slideIn 0.3s ease;
        `;
            toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i>
            ${message}
        `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // CSS animations
        const style = document.createElement('style');
        style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
        document.head.appendChild(style);
    });
</script>
@endsection