<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session("success") }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('danger'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session("danger") }}',
                icon: 'success',
                confirmButtonText: 'Ok'
            });
        @endif
        @if (session('error'))
            Swal.fire({
                title: 'error!',
                text: '{{ session("error") }}',
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        @endif
    });
</script>