<script>
  document.addEventListener('DOMContentLoaded', function() {
    const logoutLink = document.querySelector('.logout-link');
    logoutLink.addEventListener('click', function(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Apakah anda yakin ingin keluar?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Keluar!',
            cancelButtonText: 'Batalkan'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../logout.php';
            }
        });
    });
});
</script>
