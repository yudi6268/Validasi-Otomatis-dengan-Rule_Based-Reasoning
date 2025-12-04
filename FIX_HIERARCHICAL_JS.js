// FIXED HIERARCHICAL BUDGET JAVASCRIPT
// Replace the updateHierarchicalTotals function with this version

function updateHierarchicalTotals() {
    const tbody = document.getElementById('hierarchical-budget-tbody');
    if (!tbody) return;
    
    const rows = tbody.querySelectorAll('tr');
    
    // Map to store all amounts: key -> amount (in plain numbers)
    const subKegiatanAmounts = {}; // pIdx_kIdx_sIdx -> amount
    const kegiatanAmounts = {};    // pIdx_kIdx -> calculated total
    const programAmounts = {};     // pIdx -> calculated total
    
    // STEP 1: Collect all leaf node amounts (sub-kegiatan)
    rows.forEach(row => {
        if (row.dataset.level === 'subkegiatan') {
            const amountInput = row.querySelector('input[name*="_amount"]');
            const nameInput = row.querySelector('textarea[name*="_name"]');
            if (amountInput && nameInput) {
                const match = nameInput.name.match(/hb_subkegiatan_(\d+)_(\d+)_(\d+)/);
                if (match) {
                    const key = `${match[1]}_${match[2]}_${match[3]}`;
                    const amount = parseAmount(amountInput.value || '0');
                    subKegiatanAmounts[key] = amount;
                }
            }
        }
    });
    
    // STEP 2: Calculate kegiatan totals and update display
    rows.forEach(row => {
        if (row.dataset.level === 'kegiatan') {
            const amountInput = row.querySelector('input[name*="_amount"]');
            const nameInput = row.querySelector('textarea[name*="_name"]');
            if (amountInput && nameInput) {
                const match = nameInput.name.match(/hb_kegiatan_(\d+)_(\d+)/);
                if (match) {
                    const pIdx = match[1];
                    const kIdx = match[2];
                    
                    // Sum all sub-kegiatan under this kegiatan
                    let total = 0;
                    for (let sIdx = 0; ; sIdx++) {
                        const subKey = `${pIdx}_${kIdx}_${sIdx}`;
                        if (subKegiatanAmounts.hasOwnProperty(subKey)) {
                            total += subKegiatanAmounts[subKey];
                        } else if (sIdx > 0) {
                            break;
                        }
                    }
                    
                    kegiatanAmounts[`${pIdx}_${kIdx}`] = total;
                    // Update display
                    amountInput.value = total > 0 ? total.toLocaleString('id-ID') : '0';
                }
            }
        }
    });
    
    // STEP 3: Calculate program totals and update display
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
                        const kKey = `${pIdx}_${kIdx}`;
                        if (kegiatanAmounts.hasOwnProperty(kKey)) {
                            total += kegiatanAmounts[kKey];
                        } else if (kIdx > 0) {
                            break;
                        }
                    }
                    
                    programAmounts[pIdx] = total;
                    // Update display
                    amountInput.value = total > 0 ? total.toLocaleString('id-ID') : '0';
                    grandTotal += total;
                }
            }
        }
    });
    
    // STEP 4: Update grand total display
    const grandTotalInput = document.getElementById('totalBudget');
    if (grandTotalInput) {
        grandTotalInput.value = grandTotal > 0 ? grandTotal.toLocaleString('id-ID') : '0';
    }
    
    console.log('Updated totals:', { subKegiatanAmounts, kegiatanAmounts, programAmounts, grandTotal });
}
