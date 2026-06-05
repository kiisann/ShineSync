<?php // app/views/layouts/footer.php ?>
<!-- Footer -->
<footer class="ss-footer">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-4 col-md-6">
        <div class="footer-brand">Shine<span>Sync</span></div>
        <p class="footer-text">Platform perhiasan mewah Indonesia. Setiap perhiasan adalah karya seni yang merangkai cerita keindahan.</p>
        <div class="mt-20" style="margin-top:20px;">
          <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
          <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social-icon"><i class="fab fa-tiktok"></i></a>
          <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
        </div>
      </div>
      <div class="col-lg-2 col-md-6">
        <h6>Produk</h6>
        <a href="<?= APP_URL ?>/products?category=1" class="footer-link">Cincin</a>
        <a href="<?= APP_URL ?>/products?category=2" class="footer-link">Kalung</a>
        <a href="<?= APP_URL ?>/products?category=3" class="footer-link">Gelang</a>
        <a href="<?= APP_URL ?>/products?category=4" class="footer-link">Anting</a>
        <a href="<?= APP_URL ?>/products?category=5" class="footer-link">Aksesoris</a>
      </div>
      <div class="col-lg-2 col-md-6">
        <h6>Akun</h6>
        <a href="<?= APP_URL ?>/auth/login"    class="footer-link">Login</a>
        <a href="<?= APP_URL ?>/auth/register" class="footer-link">Daftar</a>
        <a href="<?= APP_URL ?>/orders"        class="footer-link">Pesanan</a>
        <a href="<?= APP_URL ?>/wishlist"      class="footer-link">Wishlist</a>
        <a href="<?= APP_URL ?>/profile"       class="footer-link">Profil</a>
      </div>
      <div class="col-lg-4 col-md-6">
        <h6>Kontak</h6>
        <p class="footer-text"><i class="fas fa-map-marker-alt me-2" style="color:#D4AF37;"></i>Jakarta, Indonesia</p>
        <p class="footer-text"><i class="fas fa-phone me-2" style="color:#D4AF37;"></i>+62 812-3456-7890</p>
        <p class="footer-text"><i class="fas fa-envelope me-2" style="color:#D4AF37;"></i>hello@shinesync.id</p>
        <p class="footer-text mt-2"><i class="fas fa-clock me-2" style="color:#D4AF37;"></i>Senin–Sabtu, 09.00–18.00</p>
      </div>
    </div>
    <hr>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 footer-bottom">
      <span>&copy; <?= date('Y') ?> ShineSync. All rights reserved.</span>
      <span>Powered by PHP Native MVC + MySQL</span>
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<!-- Custom JS -->
<script src="<?= APP_URL ?>/public/js/main.js"></script>
</body>
</html>
