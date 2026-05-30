<!-- THIS FILE IS FOR REFERENCE - Contains the complete fixed JavaScript for hierarchical budget -->

// =========================
// UPDATE HIERARCHICAL TOTALS (FIXED)
// =========================
function updateHierarchicalTotals() {
    const tbody = document.getElementById('hierarchical-budget-tbody');
    if (!tbody) return;
    
    const rows = tbody.querySelectorAll('tr');
    
    // Build a map of amounts by indices
    const amounts = {
        program: {},    // pIdx -> amount
        kegiatan: {},   // pIdx_kIdx -> amount
        subkegiatan: {} // pIdx_kIdx_sIdx -> amount
    };
    
    // PASS 1: Read all sub-kegiatan amounts (leaf nodes)
    rows.forEach(row => {
        if (row.dataset.level === 'subkegiatan') {
            const amountInput = row.querySelector('input[name*="_amount"]');
            const nameInput = row.querySelector('textarea[name*="_name"]');
            if (amountInput && nameInput) {
                const match = nameInput.name.match(/hb_subkegiatan_(\d+)_(\d+)_(\d+)/);
                if (match) {
                    const key = `${match[1]}_${match[2]}_${match[3]}`;
                    amounts.subkegiatan[key] = parseAmount(amountInput.value || '0');
                }
            }
        }
    });
    
    // PASS 2: Calculate kegiatan totals (sum of their sub-kegiatan)
    rows.forEach(row => {
        if (row.dataset.level === 'kegiatan') {
            const amountInput = row.querySelector('input[name*="_amount"]');
            const nameInput = row.querySelector('textarea[name*="_name"]');
            if (amountInput && nameInput) {
                const match = nameInput.name.match(/hb_kegiatan_(\d+)_(\d+)/);
                if (match) {
                    const pIdx = match[1];
                    const kIdx = match[2];
                    const key = `${pIdx}_${kIdx}`;
                    
                    // Sum all sub-kegiatan under this kegiatan
                    let total = 0;
                    for (let sIdx = 0; ; sIdx++) {
                        const subKey = `${pIdx}_${kIdx}_${sIdx}`;
                        if (amounts.subkegiatan.hasOwnProperty(subKey)) {
                            total += amounts.subkegiatan[subKey];
                        } else if (sIdx > 0) {
                            break; // No more sub-kegiatan
                        }
                    }
                    
                    amounts.kegiatan[key] = total;
                    amountInput.value = total > 0 ? total.toLocaleString('id-ID') : '0';
                }
            }
        }
    });
    
    // PASS 3: Calculate program totals (sum of their kegiatan)
    let grandTotal = 0;
    rows.forEach(row => {
        if (row.dataset.level === 'program') {
            const amountInput = row.querySelector('input[name*="_amount"]');
            const nameInput = row.querySelector('textarea[name*="_name"]');
            if (amountInput && nameInput) {
                const match = nameInput.name.match(/hb_program_(\d+)/);
                if (match) {
                    const pIdx = match[1];
                    
                    // Sum all kegiatan under this program
                    let total = 0;
                    for (let kIdx = 0; ; kIdx++) {
                        const key = `${pIdx}_${kIdx}`;
                        if (amounts.kegiatan.hasOwnProperty(key)) {
                            total += amounts.kegiatan[key];
                        } else if (kIdx > 0) {
                            break; // No more kegiatan
                        }
                    }
                    
                    amounts.program[pIdx] = total;
                    amountInput.value = total > 0 ? total.toLocaleString('id-ID') : '0';
                    grandTotal += total;
                }
            }
        }
    });
    
    // PASS 4: Update grand total
    const grandTotalInput = document.getElementById('totalBudget');
    if (grandTotalInput) {
        grandTotalInput.value = grandTotal > 0 ? grandTotal.toLocaleString('id-ID') : '0';
    }
}
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\perjanjian\create_hierarchical_fix.blade.php ENDPATH**/ ?>