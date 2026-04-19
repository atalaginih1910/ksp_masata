<?php
/**
 * Footer Partial
 * MASATA PINJAMIN
 */
?>

<!-- Main Content Close -->
</div>

<!-- Modal Base (untuk form dinamis) -->
<div class="modal fade" id="formModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="modalForm" method="POST">
                <div class="modal-body" id="modalBody">
                    <!-- Konten akan diisi oleh JS -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Tom Select (Searchable Select) -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

<!-- Custom JS -->
<script src="<?php echo BASE_URL; ?>/assets/js/script.js"></script>

<script>
    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });

        // Searchable dropdown untuk data anggota/kategori yang panjang
        document.querySelectorAll('select.member-select').forEach((el) => {
            if (!el.tomselect) {
                new TomSelect(el, {
                    create: false,
                    sortField: {
                        field: 'text',
                        direction: 'asc'
                    },
                    placeholder: 'Ketik untuk mencari...'
                });
            }
        });
    });
</script>

</body>
</html>
