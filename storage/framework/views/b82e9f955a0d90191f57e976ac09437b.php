<!-- resources/views/components/logout-modal.blade.php -->
<div id="logoutModal" class="logout-modal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.3);align-items:center;justify-content:center;">
  <div class="logout-box" style="background:#fff;padding:28px 28px 24px;border-radius:12px;box-shadow:0 4px 24px rgba(0,0,0,0.12);min-width:280px;max-width:340px;text-align:center;">
    <h3 style="margin-bottom:20px;font-size:16px;font-weight:600;color:#1a2a25;">Apa anda ingin keluar?</h3>
    <div class="logout-buttons" style="display:flex;gap:16px;justify-content:center;">
      <form id="logoutForm" method="POST" action="<?php echo e(route('logout')); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit" style="background:#00B5A0;color:#fff;padding:10px 28px;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Ya, Keluar</button>
      </form>
      <button type="button" id="noBtnLogout" style="background:#eef0f3;color:#333;padding:10px 28px;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;">Batal</button>
    </div>
  </div>
</div>
<script>
  function showLogoutModal() {
    document.getElementById('logoutModal').style.display = 'flex';
  }
  function hideLogoutModal() {
    document.getElementById('logoutModal').style.display = 'none';
  }
  document.addEventListener('DOMContentLoaded', function() {
    var noBtn = document.getElementById('noBtnLogout');
    if(noBtn) noBtn.onclick = hideLogoutModal;
    // Optional: close modal on ESC
    document.addEventListener('keydown', function(e) {
      if(e.key === 'Escape') hideLogoutModal();
    });
    // Optional: close modal if click outside box
    document.getElementById('logoutModal').addEventListener('click', function(e) {
      if(e.target === this) hideLogoutModal();
    });

    // Notifikasi alert setelah logout dihilangkan agar tidak muncul popup browser
  });
</script>
<style>
  .logout-modal { display: none; }
  .logout-modal[style*="display: flex"] { display: flex !important; }
</style>
<?php /**PATH E:\Kuliah\Semester 7\Magang\Perjanjian Kinerja\resources\views/components/logout-modal.blade.php ENDPATH**/ ?>