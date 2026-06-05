// ShineSync — main.js
const APP_URL = document.querySelector('meta[name="app-url"]')?.content || '';

// ── Toast Notification ─────────────────────────────────────
function showToast(message, type = 'success') {
  const container = document.getElementById('toast-container') || (() => {
    const c = document.createElement('div');
    c.id = 'toast-container';
    c.className = 'toast-container';
    document.body.appendChild(c);
    return c;
  })();

  const icons = { success: 'fa-check-circle', error: 'fa-times-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
  const colors = { success: '#28A745', error: '#DC3545', warning: '#FFC107', info: '#17A2B8' };

  const toast = document.createElement('div');
  toast.className = `toast-custom toast-${type}`;
  toast.innerHTML = `
    <i class="fas ${icons[type] || icons.info}" style="color:${colors[type]};font-size:1.1rem;"></i>
    <span style="font-size:0.88rem;font-weight:500;flex:1;">${message}</span>
    <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#999;font-size:1rem;">&times;</button>
  `;
  container.appendChild(toast);
  setTimeout(() => toast.style.cssText += 'opacity:0;transform:translateX(100%);transition:all 0.3s ease;', 3500);
  setTimeout(() => toast.remove(), 3800);
}

// ── Cart Utilities ─────────────────────────────────────────
function updateCartBadge(count) {
  document.querySelectorAll('.cart-count').forEach(el => {
    el.textContent = count;
    el.style.display = count > 0 ? 'flex' : 'none';
  });
}

function refreshCartCount() {
  fetch(APP_URL + '/cart/count')
    .then(r => r.json())
    .then(d => updateCartBadge(d.count))
    .catch(() => {});
}

// ── Add to Cart (AJAX) ─────────────────────────────────────
document.querySelectorAll('.btn-add-cart').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    const productId = this.dataset.id;
    const qty = parseInt(document.getElementById('qty-input')?.value || 1);
    const originalText = this.innerHTML;

    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    this.disabled = true;

    const form = new FormData();
    form.append('product_id', productId);
    form.append('quantity', qty);

    fetch(APP_URL + '/cart/add', { method: 'POST', body: form,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
      this.innerHTML = originalText;
      this.disabled = false;
      if (d.success) {
        updateCartBadge(d.count);
        showToast(d.message || 'Produk ditambahkan ke keranjang!', 'success');
      } else {
        showToast(d.message || 'Gagal menambahkan produk.', 'error');
      }
    })
    .catch(() => {
      this.innerHTML = originalText;
      this.disabled = false;
      showToast('Gagal terhubung ke server.', 'error');
    });
  });
});

// ── Wishlist Toggle (AJAX) ─────────────────────────────────
document.querySelectorAll('.btn-wishlist').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    const productId = this.dataset.id;
    const isActive  = this.classList.contains('active');
    const endpoint  = isActive ? '/wishlist/remove' : '/wishlist/add';
    const form = new FormData();
    form.append('product_id', productId);

    fetch(APP_URL + endpoint, { method: 'POST', body: form,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        this.classList.toggle('active');
        const icon = this.querySelector('i');
        if (icon) icon.className = this.classList.contains('active') ? 'fas fa-heart' : 'far fa-heart';
        showToast(d.message, 'success');
      }
    });
  });
});

// ── Cart Quantity Controls ─────────────────────────────────
document.querySelectorAll('.qty-dec').forEach(btn => {
  btn.addEventListener('click', function() {
    const input = this.parentElement.querySelector('.qty-input');
    const min = parseInt(input.min || 1);
    if (parseInt(input.value) > min) {
      input.value = parseInt(input.value) - 1;
      input.dispatchEvent(new Event('change'));
    }
  });
});

document.querySelectorAll('.qty-inc').forEach(btn => {
  btn.addEventListener('click', function() {
    const input = this.parentElement.querySelector('.qty-input');
    const max = parseInt(input.max || 999);
    if (parseInt(input.value) < max) {
      input.value = parseInt(input.value) + 1;
      input.dispatchEvent(new Event('change'));
    }
  });
});

// Cart update on change
document.querySelectorAll('.cart-qty-input').forEach(input => {
  input.addEventListener('change', function() {
    const detailId = this.dataset.id;
    const min = parseInt(this.min || 1);
    const max = parseInt(this.max || 999);
    let qty = parseInt(this.value) || min;
    qty = Math.max(min, Math.min(max, qty));
    this.value = qty;
    const form = new FormData();
    form.append('detail_id', detailId);
    form.append('quantity', qty);

    fetch(APP_URL + '/cart/update', { method: 'POST', body: form,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        updateCartBadge(d.count);
        const totalEl = document.getElementById('cart-total');
        if (totalEl) totalEl.textContent = 'Rp ' + formatNumber(d.total);
        // Update row subtotal
        const row = this.closest('[data-price]');
        if (row) {
          const price = parseFloat(row.dataset.price);
          const sub = row.querySelector('.item-subtotal');
          if (sub) sub.textContent = 'Rp ' + formatNumber(price * qty);
        }
      } else {
        showToast(d.message || 'Gagal memperbarui keranjang.', 'error');
        if (d.max) this.value = d.max;
      }
    })
    .catch(() => {
      showToast('Gagal memperbarui keranjang.', 'error');
    });
  });
});

// ── Remove from Cart ───────────────────────────────────────
document.querySelectorAll('.btn-remove-cart').forEach(btn => {
  btn.addEventListener('click', function(e) {
    e.preventDefault();
    const detailId = this.dataset.id;
    const row = this.closest('.cart-item');
    const form = new FormData();
    form.append('detail_id', detailId);

    fetch(APP_URL + '/cart/remove', { method: 'POST', body: form,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
      if (d.success) {
        row?.remove();
        updateCartBadge(d.count);
        const totalEl = document.getElementById('cart-total');
        if (totalEl) totalEl.textContent = 'Rp ' + formatNumber(d.total);
        if (d.count === 0) location.reload();
        showToast('Produk dihapus dari keranjang.', 'info');
      }
    });
  });
});

// ── Scroll Reveal ──────────────────────────────────────────
function initScrollReveal() {
  const observer = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
}

// ── Loading Spinner ────────────────────────────────────────
function showLoader() { document.getElementById('ss-loader')?.classList.add('show'); }
function hideLoader() { document.getElementById('ss-loader')?.classList.remove('show'); }

// ── Number Formatter ───────────────────────────────────────
function formatNumber(n) {
  return Math.round(parseFloat(n) || 0).toLocaleString('id-ID');
}

// ── Star Rating ────────────────────────────────────────────
function initStarRating() {
  const stars = document.querySelectorAll('.star-rating-input .star-btn');
  const input = document.getElementById('rating-value');
  stars.forEach((star, i) => {
    star.addEventListener('mouseenter', () => stars.forEach((s,j) => s.classList.toggle('hovered', j <= i)));
    star.addEventListener('mouseleave', () => stars.forEach(s => s.classList.remove('hovered')));
    star.addEventListener('click', () => {
      const val = i + 1;
      if (input) input.value = val;
      stars.forEach((s,j) => s.classList.toggle('selected', j < val));
    });
  });
}

// ── Admin: Delete confirm ──────────────────────────────────
document.querySelectorAll('.btn-confirm-delete').forEach(btn => {
  btn.addEventListener('click', function(e) {
    if (!confirm('Yakin ingin menghapus item ini?')) e.preventDefault();
  });
});

// ── Product Image Preview ──────────────────────────────────
const imageInput = document.getElementById('product-image-input');
if (imageInput) {
  imageInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
      const preview = document.getElementById('image-preview');
      if (preview) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
      }
    }
  });
}

// ── Admin Sidebar Mobile Toggle ────────────────────────────
const sidebarToggle = document.getElementById('sidebar-toggle');
const adminSidebar  = document.querySelector('.admin-sidebar');
sidebarToggle?.addEventListener('click', () => adminSidebar?.classList.toggle('show'));

// ── Init ───────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  initScrollReveal();
  initStarRating();
  refreshCartCount();

  // Auto dismiss alerts
  document.querySelectorAll('.auto-dismiss').forEach(el => {
    setTimeout(() => {
      el.style.cssText = 'opacity:0;transition:opacity 0.5s ease;';
      setTimeout(() => el.remove(), 500);
    }, 4000);
  });
});
