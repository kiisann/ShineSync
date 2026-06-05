<?php
include __DIR__ . '/../../layouts/admin_header.php';
function fRpAd($n){return 'Rp '.number_format($n,0,',','.');}
?>
<div class="admin-page-header">
  <div>
    <h2>Detail Pesanan #<?= htmlspecialchars($order['order_number']) ?></h2>
    <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?= APP_URL ?>/admin/orders">Pesanan</a></li><li class="breadcrumb-item active">Detail</li></ol></nav>
  </div>
  <a href="<?= APP_URL ?>/admin/orders" class="btn-admin-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="admin-card mb-4">
      <div class="card-head"><h5>Item Pesanan</h5></div>
      <table class="admin-table">
        <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
          <?php foreach($order['details'] as $item):
            $img = ($item['image']&&file_exists(UPLOAD_PATH.'products/'.$item['image'])) ? APP_URL.'/uploads/products/'.$item['image'] : APP_URL.'/public/images/no-image.svg';
          ?>
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:12px;">
                <img src="<?= $img ?>" style="width:44px;height:44px;border-radius:8px;object-fit:cover;">
                <span style="font-weight:600;font-size:.88rem;"><?= htmlspecialchars($item['product_name']) ?></span>
              </div>
            </td>
            <td><?= fRpAd($item['price']) ?></td>
            <td><?= $item['quantity'] ?></td>
            <td style="font-weight:700;color:#D4AF37;"><?= fRpAd($item['subtotal']) ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="admin-card mb-4">
      <div class="card-head"><h5>Info Customer</h5></div>
      <div class="card-body">
        <?php foreach([['Customer',$order['customer_name']],['Penerima',$order['shipping_name']],['Telepon',$order['shipping_phone']],['Alamat',$order['shipping_address']]] as [$k,$v]): ?>
        <div class="mb-2" style="font-size:.88rem;"><span style="color:#777;"><?= $k ?>:</span> <strong><?= htmlspecialchars($v) ?></strong></div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="admin-card">
      <div class="card-head"><h5>Ringkasan</h5></div>
      <div class="card-body">
        <?php foreach([['Subtotal',fRpAd($order['total_amount'])],['Diskon','−'.fRpAd($order['discount'])],['Grand Total',fRpAd($order['grand_total'])]] as [$k,$v]): ?>
        <div class="d-flex justify-content-between mb-2" style="font-size:.88rem;"><span style="color:#777;"><?= $k ?></span><strong><?= $v ?></strong></div>
        <?php endforeach; ?>
        <?php if($order['payment']): ?>
        <hr>
        <div style="font-size:.85rem;">
          Status Bayar: <span class="status-badge status-<?= $order['payment']['status'] ?>"><?= ucfirst($order['payment']['status']) ?></span>
          <?php if($order['payment']['proof_image']): ?>
          <div class="mt-2"><a href="<?= APP_URL ?>/uploads/payments/<?= $order['payment']['proof_image'] ?>" target="_blank" style="color:#D4AF37;font-size:.82rem;"><i class="fas fa-image me-1"></i>Lihat Bukti</a></div>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../../layouts/admin_footer.php'; ?>
