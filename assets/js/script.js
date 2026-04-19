/**
 * Custom JavaScript
 * MASATA PINJAMIN
 */

// Konfirmasi delete
function confirmDelete(url) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        window.location.href = url;
    }
}

// Format rupiah
function formatRupiah(value) {
    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    });
    return formatter.format(value);
}

// Format tanggal
function formatTanggal(date) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(date).toLocaleDateString('id-ID', options);
}

// Datatable with search
function setupDataTable() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('dataTable');
    
    if (searchInput && table) {
        searchInput.addEventListener('keyup', function() {
            filterTable(this.value, table);
        });
    }
}

function filterTable(value, table) {
    const rows = table.querySelectorAll('tbody tr');
    const searchValue = value.toLowerCase();
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchValue) ? '' : 'none';
    });
}

// Export to CSV
function exportToCSV(filename) {
    const csv = [];
    const rows = document.querySelectorAll('table tr');
    
    rows.forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const csvRow = [];
        
        cols.forEach(col => {
            csvRow.push('"' + col.textContent.replace(/"/g, '""') + '"');
        });
        
        csv.push(csvRow.join(','));
    });
    
    downloadCSV(csv.join('\n'), filename);
}

function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.href = URL.createObjectURL(csvFile);
    downloadLink.download = filename;
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Print table
function printTable(title) {
    const printWindow = window.open('', '', 'height=600,width=800');
    const table = document.querySelector('table').outerHTML;
    
    printWindow.document.write('<html><head><title>' + title + '</title>');
    printWindow.document.write('<style>table {border-collapse: collapse;} th, td {border: 1px solid #ddd; padding: 10px; text-align: left;}');
    printWindow.document.write('</style></head><body>');
    printWindow.document.write('<h2>' + title + '</h2>');
    printWindow.document.write(table);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}

// Initialize on DOM loaded
document.addEventListener('DOMContentLoaded', function() {
    setupDataTable();
});
