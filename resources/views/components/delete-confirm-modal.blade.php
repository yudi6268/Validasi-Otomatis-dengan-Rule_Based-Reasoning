<!-- resources/views/components/delete-confirm-modal.blade.php -->
<div id="deleteConfirmModal" class="delete-confirm-modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
  <div class="delete-box" style="background:#fff;padding:28px 28px 24px;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,0.12);min-width:280px;max-width:340px;text-align:center;">
    <div style="margin-bottom: 16px; font-size: 2.2rem; color: #f44336;">
      <i class="fas fa-exclamation-triangle"></i>
    </div>
    <h3 id="deleteModalMessage" style="margin-bottom:20px;font-size:16px;font-weight:600;color:#1a2a25;line-height:1.5;">Apakah Anda yakin ingin menghapus data ini?</h3>
    <div class="delete-buttons" style="display:flex;gap:16px;justify-content:center;">
      <form id="deleteConfirmForm" method="POST" action="">
        @csrf
        @method('DELETE')
        <button type="submit" style="background:#f44336;color:#fff;padding:10px 28px;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;transition:background 0.2s;">Ya, Hapus</button>
      </form>
      <button type="button" id="noBtnDelete" style="background:#eef0f3;color:#333;padding:10px 28px;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Batal</button>
    </div>
  </div>
</div>
<script>
  let deleteFormToSubmit = null;
  function showDeleteConfirmModal(formElement, message = "Apakah Anda yakin ingin menghapus data ini?") {
    deleteFormToSubmit = formElement;
    document.getElementById('deleteModalMessage').innerText = message;
    document.getElementById('deleteConfirmModal').style.display = 'flex';
  }
  function hideDeleteConfirmModal() {
    document.getElementById('deleteConfirmModal').style.display = 'none';
  }
  document.addEventListener('DOMContentLoaded', function() {
    var noBtn = document.getElementById('noBtnDelete');
    if(noBtn) noBtn.onclick = hideDeleteConfirmModal;
    
    // Close modal on ESC
    document.addEventListener('keydown', function(e) {
      if(e.key === 'Escape') hideDeleteConfirmModal();
    });
    
    // Close modal if click outside box
    var modal = document.getElementById('deleteConfirmModal');
    if (modal) {
      modal.addEventListener('click', function(e) {
        if(e.target === this) hideDeleteConfirmModal();
      });
    }

    // Submit handler
    var confirmForm = document.getElementById('deleteConfirmForm');
    if (confirmForm) {
      confirmForm.onsubmit = function(e) {
        e.preventDefault();
        if (deleteFormToSubmit) {
          deleteFormToSubmit.submit();
        }
        hideDeleteConfirmModal();
      };
    }
  });
</script>
<style>
  .delete-confirm-modal { display: none; }
  .delete-confirm-modal[style*="display: flex"] { display: flex !important; }
</style>
