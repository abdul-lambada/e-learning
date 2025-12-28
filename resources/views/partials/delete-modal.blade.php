{{--
Delete Confirmation Modal Partial
Usage: @include('partials.delete-modal', ['id' => 'deleteModalUser', 'title' => 'Hapus User', 'message' => 'Apakah yakin?', 'formAction' => route('...'), 'itemName' => $user->nama])
--}}

<div class="modal fade" id="{{ $id ?? 'deleteModal' }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                {{-- Animated Icon --}}
                <div class="mb-3">
                    <span class="avatar avatar-lg bg-label-danger rounded-circle p-3" style="width: 80px; height: 80px;">
                        <i class="bx bx-trash bx-lg"></i>
                    </span>
                </div>

                {{-- Title --}}
                <h4 class="mb-2">{{ $title ?? 'Konfirmasi Hapus' }}</h4>

                {{-- Message --}}
                <p class="text-muted mb-1">{{ $message ?? 'Apakah Anda yakin ingin menghapus data ini?' }}</p>

                @if (isset($itemName))
                    <p class="fw-bold text-dark mb-4">"{{ $itemName }}"</p>
                @else
                    <div class="mb-4"></div>
                @endif

                {{-- Warning --}}
                <div class="alert alert-warning d-flex align-items-center py-2 mb-4" role="alert">
                    <i class="bx bx-error me-2"></i>
                    <small>Tindakan ini tidak dapat dibatalkan!</small>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i> Batal
                    </button>
                    <form action="{{ $formAction ?? '#' }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bx bx-trash me-1"></i> Ya, Hapus!
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
