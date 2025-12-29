{{--
Dynamic Delete Modal with JavaScript
Include this once in your layout to enable dynamic delete confirmations.
Usage: Add data attributes to delete buttons:
    <button type="button" class="btn btn-danger btn-delete"
        data-url="{{ route('users.destroy', $user->id) }}"
        data-name="{{ $user->nama_lengkap }}"
        data-title="Hapus User">
        Hapus
    </button>
--}}

<!-- Dynamic Delete Modal -->
<div class="modal fade" id="dynamicDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <!-- Animated Icon -->
                <div class="swal2-icon swal2-warning swal2-icon-show mb-3"
                    style="display: flex; border-color: #ffab00; color: #ffab00;">
                    <div class="swal2-icon-content" style="font-size: 2.5rem;">!</div>
                </div>

                <!-- Title -->
                <h4 class="mb-2" id="deleteModalTitle">Konfirmasi Hapus</h4>

                <!-- Message -->
                <p class="text-muted mb-1">Apakah Anda yakin ingin menghapus:</p>
                <p class="fw-bold text-dark mb-3" id="deleteModalItemName">Item</p>

                <!-- Warning -->
                <div class="alert alert-danger d-flex align-items-center py-2 mb-4" role="alert">
                    <i class="bx bx-error-circle me-2"></i>
                    <small>Tindakan ini tidak dapat dibatalkan!</small>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i> Batal
                    </button>
                    <form id="deleteModalForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" id="deleteModalConfirm">
                            <i class="bx bx-trash me-1"></i> Ya, Hapus!
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .swal2-icon {
        position: relative;
        box-sizing: content-box;
        justify-content: center;
        width: 5em;
        height: 5em;
        margin: 0 auto;
        zoom: normal;
        border: 0.25em solid transparent;
        border-radius: 50%;
        font-family: inherit;
        line-height: 5em;
        cursor: default;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .swal2-warning {
        border-color: #facea8;
        color: #f8bb86;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('dynamicDeleteModal');
        const deleteForm = document.getElementById('deleteModalForm');
        const deleteTitle = document.getElementById('deleteModalTitle');
        const deleteItemName = document.getElementById('deleteModalItemName');

        // Use event delegation for dynamic content
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete');
            if (btn) {
                e.preventDefault();

                const url = btn.getAttribute('data-url');
                const name = btn.getAttribute('data-name') || 'item ini';
                const title = btn.getAttribute('data-title') || 'Konfirmasi Hapus';

                deleteForm.setAttribute('action', url);
                deleteTitle.textContent = title;
                deleteItemName.textContent = '"' + name + '"';

                const modal = new bootstrap.Modal(deleteModal);
                modal.show();
            }
        });
    });
</script>
