<!-- resources/views/components/status-modal.blade.php -->
<div id="statusModal" class="logout-modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
  <div class="logout-box" style="background:#fff;padding:32px 24px;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,0.12);min-width:320px;text-align:center;">
    <h3 id="statusModalTitle" style="margin-bottom:18px;"></h3>
    <p id="statusModalDesc" style="margin-bottom:24px;color:#555;"></p>
    <div class="logout-buttons" style="display:flex;gap:16px;justify-content:center;">
      <form id="statusModalForm" method="POST" style="margin:0;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <input type="hidden" name="status" id="statusModalInput">
        <button type="submit" id="statusModalYesBtn" style="background:#00B5A0;color:#fff;padding:8px 24px;border:none;border-radius:6px;font-weight:600;">YA</button>
      </form>
      <button type="button" id="statusModalNoBtn" style="background:#eee;color:#333;padding:8px 24px;border:none;border-radius:6px;font-weight:600;">TIDAK</button>
    </div>
  </div>
</div>
<script>
  function showStatusModal(actionUrl, status, nama, statusLabel) {
    document.getElementById('statusModal').style.display = 'flex';
    document.getElementById('statusModalForm').action = actionUrl;
    document.getElementById('statusModalInput').value = status;
    document.getElementById('statusModalTitle').innerText = 'Ubah Status Pengguna';
    document.getElementById('statusModalDesc').innerHTML = 'Apakah Anda yakin ingin mengubah status <b>' + nama + '</b> menjadi <b>' + statusLabel + '</b>?';
  }
  function hideStatusModal() {
    document.getElementById('statusModal').style.display = 'none';
  }
  document.addEventListener('DOMContentLoaded', function() {
    var noBtn = document.getElementById('statusModalNoBtn');
    if(noBtn) noBtn.onclick = hideStatusModal;
    document.addEventListener('keydown', function(e) {
      if(e.key === 'Escape') hideStatusModal();
    });
    document.getElementById('statusModal').addEventListener('click', function(e) {
      if(e.target === this) hideStatusModal();
    });
  });
</script><?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views\components\status-modal.blade.php ENDPATH**/ ?>