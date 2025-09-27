<div class="table-responsive">
    <table id="tabel_user" class="table table-hover table-bordered align-middle custom-tabel-usulan" style="width:100%">
        <thead class="table-info text-center">
            <tr class="text-nowrap bg-light text-dark fw-semibold">
                <th width="1%">No</th>
                <th><i class="bx bx-user"></i> Username</th>
                <th><i class="bx bx-shield-quarter me-1 text-primary"></i> Level</th>

                <th width="15%"><i class="bx bx-cog me-1 text-secondary"></i> Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($result as $row)
                <tr>
                    <td class="text-center fw-semibold">{{ $loop->iteration }}</td>
                    <td data-label="Username">
                        <i class="bi bi-person-circle me-1"></i>
                        <span class="fw-medium">{{ $row->email }}</span>
                    </td>
                    <td data-label="Level">
                        <span class="badge bg-light text-dark shadow-sm px-3 py-2 rounded-pill">
                            {{ cek_userdata($row->level) }}
                        </span>
                    </td>

                    <td class="text-center" data-label="Aksi">
                        <div class="btn-group" role="group">
                            <button type="button"
                                class="btn btn-sm btn-outline-primary dropdown-toggle shadow-sm rounded-pill px-3"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bx bx-cog me-1"></i> Aksi
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="#"
                                        data-url="{{ route('userdata.username', $row->id) }}" id="tombol-form-modal">
                                        <i class="bx bx-edit-alt text-primary"></i> Edit User
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="#"
                                        data-url="{{ route('userdata.resetpassword', $row->id) }}"
                                        id="tombol-form-modal">
                                        <i class="bx bx-key text-warning"></i> Reset Password
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('userdata.destroy', $row->id) }}"
                                        class="formDelete d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                            <i class="bx bx-trash-alt"></i> Hapus User
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#tabel_user').DataTable({
            language: {
                url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            ordering: true,
            responsive: true,
            autoWidth: false
        });
    });
</script>

<style>
    /* Hover efek tabel sama seperti tabel penandatangan */
    #tabel_user tbody tr:hover {
        background-color: #f7f9fc !important;
        transition: background-color 0.3s ease;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f1f1f1;
        transition: background-color 0.2s ease;
    }

    /* Responsive collapse mode untuk mobile */
    @media (max-width: 768px) {
        table.dataTable thead {
            display: none;
        }

        table.dataTable tbody tr {
            display: block;
            margin-bottom: 0.75rem;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        table.dataTable tbody td {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem;
            border: none;
            border-bottom: 1px solid #f1f1f1;
        }

        table.dataTable tbody td:last-child {
            border-bottom: none;
        }

        table.dataTable tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #555;
        }
    }
</style>
