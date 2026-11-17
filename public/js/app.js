// Aktifkan/nonaktifkan input jumlah sesuai checkbox perlengkapan
document.querySelectorAll('.alat input[type=checkbox]').forEach(function (cb) {
  var jml = cb.closest('.alat').querySelector('.jml');
  function sinkron() { if (jml) { jml.disabled = !cb.checked; } }
  cb.addEventListener('change', sinkron);
  sinkron();
});

// Konfirmasi sebelum menghapus
document.querySelectorAll('form[data-konfirmasi]').forEach(function (f) {
  f.addEventListener('submit', function (e) {
    if (!window.confirm(f.dataset.konfirmasi)) { e.preventDefault(); }
  });
});

// Tutup notifikasi
document.querySelectorAll('.alert button').forEach(function (b) {
  b.addEventListener('click', function () { b.closest('.alert').remove(); });
});
