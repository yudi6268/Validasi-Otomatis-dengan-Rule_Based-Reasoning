{{-- MODAL INPUT TARGET TRIWULAN PER BULAN --}}
<div id="targetModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; overflow-y:auto;">
    <div style="background:white; max-width:850px; margin:30px auto; border-radius:12px; box-shadow:0 4px 30px rgba(0,0,0,0.4);">
        <div style="background:#00B5A0; color:white; padding:20px; border-radius:12px 12px 0 0; display:flex; justify-content:space-between; align-items:center;">
            <h3 style="margin:0; font-size:18px;"><i class="fas fa-calendar-alt"></i> Atur Target Triwulan per Bulan</h3>
            <button onclick="closeTargetModal()" style="background:transparent; border:none; color:white; font-size:28px; cursor:pointer; line-height:1;">&times;</button>
        </div>
        
        <div style="padding:25px;">
            {{-- Info Sub Kegiatan --}}
            <div style="background:#f8f9fa; padding:15px; border-radius:8px; margin-bottom:20px; border-left:4px solid #00B5A0;">
                <div style="display:grid; grid-template-columns:auto 1fr; gap:10px; margin-bottom:10px;">
                    <div style="font-weight:600;">No:</div>
                    <div id="modalNo"></div>
                    <div style="font-weight:600;">Pagu Anggaran:</div>
                    <div id="modalPagu" style="color:#00B5A0; font-weight:700; font-size:16px;"></div>
                </div>
                <div style="margin-top:10px;"><strong>Nama Sub Kegiatan:</strong> <span id="modalNama"></span></div>
            </div>

            {{-- Toggle Input Mode --}}
            <div style="margin-bottom:20px; text-align:center;">
                <button type="button" id="btnNominal" onclick="switchInputMode('nominal')" 
                        style="padding:10px 30px; border:2px solid #00B5A0; background:#00B5A0; color:white; border-radius:8px 0 0 8px; cursor:pointer; font-weight:600; transition:all 0.3s;">
                    💰 Nominal (Rp)
                </button>
                <button type="button" id="btnProsentase" onclick="switchInputMode('prosentase')" 
                        style="padding:10px 30px; border:2px solid #00B5A0; background:white; color:#00B5A0; border-radius:0 8px 8px 0; cursor:pointer; font-weight:600; transition:all 0.3s;">
                    📊 Prosentase (%)
                </button>
            </div>

            {{-- Sisa Pagu & Progress Bar --}}
            <div style="background:linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); padding:15px; border-radius:8px; margin-bottom:20px; border:2px solid #ffc107; box-shadow:0 2px 8px rgba(255,193,7,0.2);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <div>
                        <div style="font-size:12px; color:#856404; font-weight:600; margin-bottom:3px;">SISA PAGU</div>
                        <span id="sisaPagu" style="font-size:20px; font-weight:700; color:#d9534f;">Rp 0</span>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:12px; color:#856404; font-weight:600; margin-bottom:3px;">TOTAL DISTRIBUSI</div>
                        <span id="totalDistribusi" style="font-size:20px; font-weight:700; color:#00B5A0;">0%</span>
                    </div>
                </div>
                <div style="width:100%; background:#e0e0e0; height:12px; border-radius:6px; overflow:hidden; box-shadow:inset 0 2px 4px rgba(0,0,0,0.1);">
                    <div id="progressBar" style="width:0%; background:linear-gradient(90deg, #00B5A0 0%, #00d4aa 100%); height:100%; transition:all 0.5s ease; box-shadow:0 2px 4px rgba(0,181,160,0.3);"></div>
                </div>
            </div>

            {{-- Input Bulan per Triwulan --}}
            <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:15px;">
                {{-- TW 1 --}}
                <div style="border:2px solid #00B5A0; border-radius:8px; padding:15px; background:#f8fffd;">
                    <h4 style="margin:0 0 12px 0; color:#00B5A0; font-size:14px; text-align:center; border-bottom:2px solid #00B5A0; padding-bottom:8px;">
                        📅 TRIWULAN I
                    </h4>
                    <div style="margin-bottom:8px;">
                        <label style="font-size:11px; color:#666; display:block; margin-bottom:3px;">Januari</label>
                        <input type="text" id="bln1" oninput="formatModalInput(this)" placeholder="0"
                               style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; text-align:right; font-weight:600;">
                    </div>
                    <div style="margin-bottom:8px;">
                        <label style="font-size:11px; color:#666; display:block; margin-bottom:3px;">Februari</label>
                        <input type="text" id="bln2" oninput="formatModalInput(this)" placeholder="0"
                               style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; text-align:right; font-weight:600;">
                    </div>
                    <div>
                        <label style="font-size:11px; color:#666; display:block; margin-bottom:3px;">Maret</label>
                        <input type="text" id="bln3" oninput="formatModalInput(this)" placeholder="0"
                               style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; text-align:right; font-weight:600;">
                    </div>
                    <div style="margin-top:10px; padding-top:10px; border-top:1px solid #ddd; text-align:right; font-weight:700; color:#00B5A0;">
                        Total: <span id="totalTW1Modal">0</span>
                    </div>
                </div>

                {{-- TW 2 --}}
                <div style="border:2px solid #17a2b8; border-radius:8px; padding:15px; background:#f1faff;">
                    <h4 style="margin:0 0 12px 0; color:#17a2b8; font-size:14px; text-align:center; border-bottom:2px solid #17a2b8; padding-bottom:8px;">
                        📅 TRIWULAN II
                    </h4>
                    <div style="margin-bottom:8px;">
                        <label style="font-size:11px; color:#666; display:block; margin-bottom:3px;">April</label>
                        <input type="text" id="bln4" oninput="formatModalInput(this)" placeholder="0"
                               style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; text-align:right; font-weight:600;">
                    </div>
                    <div style="margin-bottom:8px;">
                        <label style="font-size:11px; color:#666; display:block; margin-bottom:3px;">Mei</label>
                        <input type="text" id="bln5" oninput="formatModalInput(this)" placeholder="0"
                               style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; text-align:right; font-weight:600;">
                    </div>
                    <div>
                        <label style="font-size:11px; color:#666; display:block; margin-bottom:3px;">Juni</label>
                        <input type="text" id="bln6" oninput="formatModalInput(this)" placeholder="0"
                               style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; text-align:right; font-weight:600;">
                    </div>
                    <div style="margin-top:10px; padding-top:10px; border-top:1px solid #ddd; text-align:right; font-weight:700; color:#17a2b8;">
                        Total: <span id="totalTW2Modal">0</span>
                    </div>
                </div>

                {{-- TW 3 --}}
                <div style="border:2px solid #ffc107; border-radius:8px; padding:15px; background:#fffdf0;">
                    <h4 style="margin:0 0 12px 0; color:#ff9800; font-size:14px; text-align:center; border-bottom:2px solid #ffc107; padding-bottom:8px;">
                        📅 TRIWULAN III
                    </h4>
                    <div style="margin-bottom:8px;">
                        <label style="font-size:11px; color:#666; display:block; margin-bottom:3px;">Juli</label>
                        <input type="text" id="bln7" oninput="formatModalInput(this)" placeholder="0"
                               style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; text-align:right; font-weight:600;">
                    </div>
                    <div style="margin-bottom:8px;">
                        <label style="font-size:11px; color:#666; display:block; margin-bottom:3px;">Agustus</label>
                        <input type="text" id="bln8" oninput="formatModalInput(this)" placeholder="0"
                               style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; text-align:right; font-weight:600;">
                    </div>
                    <div>
                        <label style="font-size:11px; color:#666; display:block; margin-bottom:3px;">September</label>
                        <input type="text" id="bln9" oninput="formatModalInput(this)" placeholder="0"
                               style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; text-align:right; font-weight:600;">
                    </div>
                    <div style="margin-top:10px; padding-top:10px; border-top:1px solid #ddd; text-align:right; font-weight:700; color:#ff9800;">
                        Total: <span id="totalTW3Modal">0</span>
                    </div>
                </div>

                {{-- TW 4 --}}
                <div style="border:2px solid #dc3545; border-radius:8px; padding:15px; background:#fff5f5;">
                    <h4 style="margin:0 0 12px 0; color:#dc3545; font-size:14px; text-align:center; border-bottom:2px solid #dc3545; padding-bottom:8px;">
                        📅 TRIWULAN IV
                    </h4>
                    <div style="margin-bottom:8px;">
                        <label style="font-size:11px; color:#666; display:block; margin-bottom:3px;">Oktober</label>
                        <input type="text" id="bln10" oninput="formatModalInput(this)" placeholder="0"
                               style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; text-align:right; font-weight:600;">
                    </div>
                    <div style="margin-bottom:8px;">
                        <label style="font-size:11px; color:#666; display:block; margin-bottom:3px;">November</label>
                        <input type="text" id="bln11" oninput="formatModalInput(this)" placeholder="0"
                               style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; text-align:right; font-weight:600;">
                    </div>
                    <div>
                        <label style="font-size:11px; color:#666; display:block; margin-bottom:3px;">Desember</label>
                        <input type="text" id="bln12" oninput="formatModalInput(this)" placeholder="0"
                               style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px; text-align:right; font-weight:600;">
                    </div>
                    <div style="margin-top:10px; padding-top:10px; border-top:1px solid #ddd; text-align:right; font-weight:700; color:#dc3545;">
                        Total: <span id="totalTW4Modal">0</span>
                    </div>
                </div>
            </div>

            {{-- Warning Message --}}
            <div id="warningMessage" style="display:none; padding:12px 15px; border-radius:6px; margin-top:15px; border-left:4px solid;">
                <i class="fas fa-exclamation-triangle"></i> <strong>Peringatan:</strong> <span id="warningText"></span>
            </div>

            {{-- Action Buttons --}}
            <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px; padding-top:20px; border-top:2px solid #e0e0e0;">
                <button type="button" onclick="closeTargetModal()" 
                        style="padding:12px 30px; background:#6c757d; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:600; transition:all 0.3s;">
                    ✖ Batal
                </button>
                <button type="button" id="btnSimpanTarget" onclick="saveTargetModal()" 
                        style="padding:12px 30px; background:#00B5A0; color:white; border:none; border-radius:6px; cursor:pointer; font-weight:600; box-shadow:0 2px 8px rgba(0,181,160,0.3); transition:all 0.3s;">
                    💾 Simpan Target
                </button>
            </div>
        </div>
    </div>
</div>
