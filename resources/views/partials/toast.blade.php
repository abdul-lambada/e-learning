{{-- Toast Container --}}
<div class="bs-toast toast-placement-ex m-2 fade bg-{{ session('success') ? 'success' : (session('error') ? 'danger' : (session('warning') ? 'warning' : 'info')) }} top-0 end-0 hide"
    role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000" id="flashToast"
    @if (session('success') || session('error') || session('warning') || session('info')) data-show="true" @endif>
    <div class="toast-header">
        <i
            class="bx bx-{{ session('success') ? 'check-circle text-success' : (session('error') ? 'x-circle text-danger' : (session('warning') ? 'error text-warning' : 'info-circle text-info')) }} me-2"></i>
        <span class="me-auto fw-semibold">
            @if (session('success'))
                Berhasil!
            @elseif(session('error'))
                Error!
            @elseif(session('warning'))
                Peringatan!
            @else
                Informasi
            @endif
        </span>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        {{ session('success') ?? (session('error') ?? (session('warning') ?? (session('info') ?? ''))) }}
    </div>
</div>

{{-- Toast Styles --}}
<style>
    .bs-toast {
        position: fixed;
        z-index: 1090;
    }

    .toast-placement-ex {
        position: fixed;
        top: 1rem;
        right: 1rem;
    }
</style>

{{-- Toast Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const flashToast = document.getElementById('flashToast');
        if (flashToast && flashToast.dataset.show === 'true') {
            flashToast.classList.remove('hide');
            flashToast.classList.add('show');

            // Auto hide after delay
            setTimeout(function() {
                flashToast.classList.remove('show');
                flashToast.classList.add('hide');
            }, 4000);
        }
    });
</script>
