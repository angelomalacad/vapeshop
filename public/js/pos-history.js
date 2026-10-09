(function () {
    function printReceipt(orderId) {
        var modalContent = document.querySelector('#receiptModal' + orderId + ' .modal-body');
        if (!modalContent) return;

        var clone = modalContent.cloneNode(true);
        var printWindow = window.open('', '_blank', 'width=420,height=640');
        if (!printWindow) return;

        printWindow.document.open();
        printWindow.document.write('<html><head><title>Receipt</title>');
        printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
        printWindow.document.write('<style>body{padding:20px;font-family:Arial,sans-serif}@media print{body{padding:0}}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write(clone.outerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        printWindow.focus();
        setTimeout(function () {
            printWindow.print();
            printWindow.close();
        }, 250);
    }

    window.printReceipt = printReceipt;

    document.addEventListener('DOMContentLoaded', function () {
        var cells = document.querySelectorAll('.notes-cell');
        for (var i = 0; i < cells.length; i++) {
            cells[i].addEventListener('click', function () {
                var fullNote = this.querySelector('.notes-full');
                if (!fullNote) return;

                var tr = this.closest('tr');
                var codeEl = tr ? tr.querySelector('code') : null;
                var orderNumber = codeEl ? codeEl.textContent : '';

                document.getElementById('notesOrderNumber').textContent = 'Order: ' + orderNumber;
                document.getElementById('notesContent').textContent = fullNote.textContent.trim();
                var modal = new bootstrap.Modal(document.getElementById('notesModal'));
                modal.show();
            });
        }
    });
})();