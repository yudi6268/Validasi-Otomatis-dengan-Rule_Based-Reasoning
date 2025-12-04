// ====================================================================
// HIERARCHICAL BUDGET SYSTEM - FIXED VERSION
// Properly calculates cascading totals for Program > Kegiatan > Sub-Kegiatan
// ====================================================================

// =========================
// PARSE AMOUNT STRING TO NUMBER (remove separators)
// =========================
function parseAmount(str) {
    if (!str) return 0;
    return parseInt(String(str).replace(/\D/g, '') || 0);
}

// =========================
// FORMAT NUMBER INPUT (add separators while typing)
// =========================
function formatNumberInput(str) {
    if (!str) return '';
    const cleaned = String(str).replace(/\D/g, '');
    if (!cleaned) return '';
    return cleaned.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// =========================
// GENERATE HIERARCHICAL STRUCTURE
// =========================
function getHierarchicalBudgetStructure() {
    const tbody = document.getElementById('hierarchical-budget-tbody');
    if (!tbody) return { programs: [] };
    
    const rows = tbody.querySelectorAll('tr');
    const structure = { programs: [] };
    
    let currentProgram = null;
    let currentKegiatan = null;
    
    rows.forEach(row => {
        const level = row.dataset.level || 'program';
        const nameInput = row.querySelector('textarea[name*="_name"]');
        const amountInput = row.querySelector('input[name*="_amount"]');
        const ketInput = row.querySelector('textarea[name*="_ket"]');
        
        if (!amountInput) return;
        
        const rawAmount = parseAmount(amountInput.value || '0');
        
        if (level === 'program') {
            currentProgram = {
                name: nameInput?.value || '',
                amount: rawAmount.toString(),
                keterangan: ketInput?.value || '',
                kegiatan: []
            };
            structure.programs.push(currentProgram);
        } else if (level === 'kegiatan' && currentProgram) {
            currentKegiatan = {
                name: nameInput?.value || '',
                amount: rawAmount.toString(),
                keterangan: ketInput?.value || '',
                subKegiatan: []
            };
            currentProgram.kegiatan.push(currentKegiatan);
        } else if (level === 'subkegiatan' && currentKegiatan) {
            currentKegiatan.subKegiatan.push({
                name: nameInput?.value || '',
                amount: rawAmount.toString(),
                keterangan: ketInput?.value || ''
            });
        }
    });
    
    return structure;
}

// =========================
// RENDER HIERARCHICAL BUDGET TABLE
// =========================
function renderHierarchicalBudgetTable(structure) {
    const tbody = document.getElementById('hierarchical-budget-tbody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    let rowNum = 1;
    
    structure.programs.forEach((program, pIdx) => {
        const programNo = rowNum.toString();
        
        // PROGRAM ROW
        const programRow = createBudgetRow({
            no: programNo,
            name: program.name,
            amount: program.amount,
            keterangan: program.keterangan,
            level: 'program',
            inputNames: { name: `hb_program_${pIdx}_name`, amount: `hb_program_${pIdx}_amount`, ket: `hb_program_${pIdx}_ket` },
            onDelete: () => deleteProgram(pIdx),
            onAddKegiatan: () => addKegiatan(pIdx),
            onAmountChange: () => updateHierarchicalTotals()
        });
        tbody.appendChild(programRow);
        rowNum++;
        
        // KEGIATAN ROWS
        program.kegiatan.forEach((kegiatan, kIdx) => {
            const kegiatanNo = (programNo + '.' + (kIdx + 1)).toString();
            
            const kegiatanRow = createBudgetRow({
                no: kegiatanNo,
                name: kegiatan.name,
                amount: kegiatan.amount,
                keterangan: kegiatan.keterangan,
                level: 'kegiatan',
                inputNames: { name: `hb_kegiatan_${pIdx}_${kIdx}_name`, amount: `hb_kegiatan_${pIdx}_${kIdx}_amount`, ket: `hb_kegiatan_${pIdx}_${kIdx}_ket` },
                onDelete: () => deleteKegiatan(pIdx, kIdx),
                onAddSubKegiatan: () => addSubKegiatan(pIdx, kIdx),
                onAmountChange: () => updateHierarchicalTotals()
            });
            tbody.appendChild(kegiatanRow);
            
            // SUB-KEGIATAN ROWS
            kegiatan.subKegiatan.forEach((subKegiatan, sIdx) => {
                const subKegiatanNo = (kegiatanNo + '.' + (sIdx + 1)).toString();
                
                const subKegiatanRow = createBudgetRow({
                    no: subKegiatanNo,
                    name: subKegiatan.name,
                    amount: subKegiatan.amount,
                    keterangan: subKegiatan.keterangan,
                    level: 'subkegiatan',
                    inputNames: { name: `hb_subkegiatan_${pIdx}_${kIdx}_${sIdx}_name`, amount: `hb_subkegiatan_${pIdx}_${kIdx}_${sIdx}_amount`, ket: `hb_subkegiatan_${pIdx}_${kIdx}_${sIdx}_ket` },
                    onDelete: () => deleteSubKegiatan(pIdx, kIdx, sIdx),
                    onAmountChange: () => updateHierarchicalTotals()
                });
                tbody.appendChild(subKegiatanRow);
            });
        });
    });
}

// =========================
// CREATE BUDGET ROW HTML
// =========================
function createBudgetRow(config) {
    const tr = document.createElement('tr');
    tr.dataset.level = config.level;
    
    const noPadding = config.level === 'program' ? '0px' : (config.level === 'kegiatan' ? '20px' : '40px');
    const fontWeight = config.level === 'program' ? '600' : (config.level === 'kegiatan' ? '500' : '400');
    
    // NO CELL
    const noCell = document.createElement('td');
    noCell.style.cssText = `border: 1px solid #000; padding: 8px; text-align: center; font-weight: ${fontWeight};`;
    noCell.textContent = config.no;
    tr.appendChild(noCell);
    
    // NAME CELL
    const nameCell = document.createElement('td');
    nameCell.style.cssText = `border: 1px solid #000; padding: 8px; text-align: left; font-weight: ${fontWeight}; padding-left: calc(8px + ${noPadding});`;
    const nameTextarea = document.createElement('textarea');
    nameTextarea.name = config.inputNames.name;
    nameTextarea.value = config.name;
    nameTextarea.placeholder = 'Nama ' + (config.level === 'program' ? 'Program' : (config.level === 'kegiatan' ? 'Kegiatan' : 'Sub-Kegiatan'));
    nameTextarea.style.cssText = 'width: 95%; min-height: 35px; resize: none; padding: 4px; border: none; background: transparent; font-family: inherit; font-size: 12px;';
    nameTextarea.addEventListener('input', function() { autoExpand(this); config.onAmountChange(); });
    nameCell.appendChild(nameTextarea);
    tr.appendChild(nameCell);
    
    // AMOUNT CELL
    const amountCell = document.createElement('td');
    amountCell.style.cssText = `border: 1px solid #000; padding: 8px; text-align: right; font-weight: ${fontWeight};`;
    const amountInput = document.createElement('input');
    amountInput.type = 'text';
    amountInput.name = config.inputNames.amount;
    // Format the display value with separators
    amountInput.value = config.amount > 0 ? parseInt(config.amount).toLocaleString('id-ID') : '0';
    amountInput.placeholder = '0';
    amountInput.className = 'hierarchy-amount-input';
    amountInput.style.cssText = 'width: 95%; border: none; background: transparent; text-align: right; font-family: inherit; font-size: 12px; font-weight: ' + fontWeight;
    amountInput.addEventListener('input', function() {
        // Format with separators and update totals
        this.value = formatNumberInput(this.value);
        config.onAmountChange();
    });
    amountCell.appendChild(amountInput);
    tr.appendChild(amountCell);
    
    // KETERANGAN CELL
    const ketCell = document.createElement('td');
    ketCell.style.cssText = `border: 1px solid #000; padding: 8px; text-align: left;`;
    const ketTextarea = document.createElement('textarea');
    ketTextarea.name = config.inputNames.ket;
    ketTextarea.value = config.keterangan;
    ketTextarea.placeholder = 'Keterangan';
    ketTextarea.style.cssText = 'width: 95%; min-height: 35px; resize: none; padding: 4px; border: none; background: transparent; font-family: inherit; font-size: 12px;';
    ketTextarea.addEventListener('input', function() { autoExpand(this); });
    ketCell.appendChild(ketTextarea);
    tr.appendChild(ketCell);
    
    // ACTION CELL
    const actionCell = document.createElement('td');
    actionCell.style.cssText = 'border: 1px solid #000; padding: 8px; text-align: center;';
    
    // Delete button
    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.className = 'table-action-btn delete-btn';
    deleteBtn.innerHTML = '🗑';
    deleteBtn.title = 'Hapus';
    deleteBtn.addEventListener('click', config.onDelete);
    actionCell.appendChild(deleteBtn);
    
    // Add sub-item button
    if (config.level === 'program' && config.onAddKegiatan) {
        const addKegiatanBtn = document.createElement('button');
        addKegiatanBtn.type = 'button';
        addKegiatanBtn.className = 'table-action-btn add-btn';
        addKegiatanBtn.innerHTML = '➕ Kegiatan';
        addKegiatanBtn.title = 'Tambah Kegiatan';
        addKegiatanBtn.style.cssText = 'font-size: 11px; padding: 2px 4px;';
        addKegiatanBtn.addEventListener('click', config.onAddKegiatan);
        actionCell.appendChild(addKegiatanBtn);
    } else if (config.level === 'kegiatan' && config.onAddSubKegiatan) {
        const addSubBtn = document.createElement('button');
        addSubBtn.type = 'button';
        addSubBtn.className = 'table-action-btn add-btn';
        addSubBtn.innerHTML = '➕ Sub';
        addSubBtn.title = 'Tambah Sub-Kegiatan';
        addSubBtn.style.cssText = 'font-size: 11px; padding: 2px 4px;';
        addSubBtn.addEventListener('click', config.onAddSubKegiatan);
        actionCell.appendChild(addSubBtn);
    }
    
    tr.appendChild(actionCell);
    
    return tr;
}

// =========================
// HIERARCHICAL BUDGET ACTIONS
// =========================
function addProgram() {
    const structure = getHierarchicalBudgetStructure();
    structure.programs.push({
        name: '',
        amount: '0',
        keterangan: '',
        kegiatan: []
    });
    renderHierarchicalBudgetTable(structure);
    updateHierarchicalTotals();
}

function addKegiatan(pIdx) {
    const structure = getHierarchicalBudgetStructure();
    if (structure.programs[pIdx]) {
        structure.programs[pIdx].kegiatan.push({
            name: '',
            amount: '0',
            keterangan: '',
            subKegiatan: []
        });
        renderHierarchicalBudgetTable(structure);
        updateHierarchicalTotals();
    }
}

function addSubKegiatan(pIdx, kIdx) {
    const structure = getHierarchicalBudgetStructure();
    if (structure.programs[pIdx] && structure.programs[pIdx].kegiatan[kIdx]) {
        structure.programs[pIdx].kegiatan[kIdx].subKegiatan.push({
            name: '',
            amount: '0',
            keterangan: ''
        });
        renderHierarchicalBudgetTable(structure);
        updateHierarchicalTotals();
    }
}

function deleteProgram(pIdx) {
    if (confirm('Hapus program ini?')) {
        const structure = getHierarchicalBudgetStructure();
        structure.programs.splice(pIdx, 1);
        renderHierarchicalBudgetTable(structure);
        updateHierarchicalTotals();
    }
}

function deleteKegiatan(pIdx, kIdx) {
    if (confirm('Hapus kegiatan ini?')) {
        const structure = getHierarchicalBudgetStructure();
        if (structure.programs[pIdx]) {
            structure.programs[pIdx].kegiatan.splice(kIdx, 1);
            renderHierarchicalBudgetTable(structure);
            updateHierarchicalTotals();
        }
    }
}

function deleteSubKegiatan(pIdx, kIdx, sIdx) {
    if (confirm('Hapus sub-kegiatan ini?')) {
        const structure = getHierarchicalBudgetStructure();
        if (structure.programs[pIdx] && structure.programs[pIdx].kegiatan[kIdx]) {
            structure.programs[pIdx].kegiatan[kIdx].subKegiatan.splice(sIdx, 1);
            renderHierarchicalBudgetTable(structure);
            updateHierarchicalTotals();
        }
    }
}

// =========================
// UPDATE HIERARCHICAL TOTALS (FIXED VERSION)
// =========================
function updateHierarchicalTotals() {
    const tbody = document.getElementById('hierarchical-budget-tbody');
    if (!tbody) return;
    
    const rows = tbody.querySelectorAll('tr');
    
    // STEP 1: Collect all sub-kegiatan amounts (leaf nodes)
    const subKegiatanAmounts = {};
    rows.forEach(row => {
        if (row.dataset.level === 'subkegiatan') {
            const amountInput = row.querySelector('input[name*="_amount"]');
            const nameInput = row.querySelector('textarea[name*="_name"]');
            if (amountInput && nameInput) {
                const match = nameInput.name.match(/hb_subkegiatan_(\d+)_(\d+)_(\d+)/);
                if (match) {
                    const key = `${match[1]}_${match[2]}_${match[3]}`;
                    subKegiatanAmounts[key] = parseAmount(amountInput.value || '0');
                }
            }
        }
    });
    
    // STEP 2: Calculate kegiatan totals (sum their sub-kegiatan)
    const kegiatanAmounts = {};
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
                    // Update display with formatted number
                    amountInput.value = total > 0 ? total.toLocaleString('id-ID') : '0';
                }
            }
        }
    });
    
    // STEP 3: Calculate program totals (sum their kegiatan)
    let grandTotal = 0;
    const programAmounts = {};
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
                    // Update display with formatted number
                    amountInput.value = total > 0 ? total.toLocaleString('id-ID') : '0';
                    grandTotal += total;
                }
            }
        }
    });
    
    // STEP 4: Update grand total
    const grandTotalInput = document.getElementById('totalBudget');
    if (grandTotalInput) {
        grandTotalInput.value = grandTotal > 0 ? grandTotal.toLocaleString('id-ID') : '0';
    }
    
    console.log('Hierarchical totals updated', { subKegiatanAmounts, kegiatanAmounts, programAmounts, grandTotal });
}

// =========================
// INITIALIZE HIERARCHICAL BUDGET
// =========================
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing hierarchical budget...');
    
    // Initialize with one empty program
    const initialStructure = {
        programs: [{
            name: '',
            amount: '0',
            keterangan: '',
            kegiatan: []
        }]
    };
    
    renderHierarchicalBudgetTable(initialStructure);
    updateHierarchicalTotals();
});

// =========================
// SAVE TO SUPABASE
// =========================
function saveToSupabase(e) {
    e.preventDefault();
    
    const form = document.querySelector("form");
    const formData = new FormData(form);
    
    // Serialize hierarchical budget structure and add to form
    const hierarchicalStructure = getHierarchicalBudgetStructure();
    formData.append('hierarchical_budget_json', JSON.stringify(hierarchicalStructure));
    
    // Tampilkan loading
    const btn = e.target;
    const originalText = btn.innerText;
    btn.disabled = true;
    btn.innerText = '⏳ Menyimpan...';
    
    fetch('/perjanjian/save', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(async response => {
        // Try to parse JSON body if present
        const contentType = response.headers.get('content-type') || '';
        let data = {};
        if (contentType.includes('application/json')) {
            data = await response.json();
        } else {
            // If server returned HTML or plain text, read it for debugging
            const text = await response.text();
            throw new Error('Unexpected server response: ' + text.substring(0, 200));
        }

        // If response not OK, extract message or validation errors
        if (!response.ok) {
            let msg = data.message || 'Gagal menyimpan data.';
            if (data.errors) {
                // collect validation errors
                const errs = [];
                for (const k in data.errors) {
                    errs.push(...data.errors[k]);
                }
                if (errs.length) msg = errs.join(' | ');
            }
            throw new Error(msg);
        }

        return data;
    })
    .then(data => {
        btn.disabled = false;
        btn.innerText = originalText;

        if (data.success) {
            showSuccessMessage(data.message || 'Berhasil disimpan.');
            setTimeout(() => {
                window.location.href = '/perjanjian';
            }, 1200);
        } else {
            showErrorMessage(data.message || 'Gagal menyimpan data.');
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerText = originalText;
        console.error('Save error:', error);
        showErrorMessage(error.message || 'Error saat menyimpan');
    });
}

// =========================
// SHOW SUCCESS MESSAGE
// =========================
function showSuccessMessage(message) {
    const div = document.createElement('div');
    div.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        z-index: 9999;
        font-size: 14px;
    `;
    div.innerText = '✓ ' + message;
    document.body.appendChild(div);
    
    setTimeout(() => div.remove(), 3000);
}

// =========================
// SHOW ERROR MESSAGE
// =========================
function showErrorMessage(message) {
    const div = document.createElement('div');
    div.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #dc3545;
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        z-index: 9999;
        font-size: 14px;
    `;
    div.innerText = '✗ ' + message;
    document.body.appendChild(div);
    
    setTimeout(() => div.remove(), 5000);
}
