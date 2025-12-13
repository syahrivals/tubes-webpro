# Animations Guide

Semua animasi telah ditambahkan ke seluruh aplikasi Laravel Anda! 🎉

## 🎬 Animasi yang Tersedia

### **Animasi Masuk (Entrance Animations)**
- `animate-slideInLeft` - Geser dari kiri
- `animate-slideInRight` - Geser dari kanan
- `animate-scaleInCenter` - Zoom dari tengah
- `animate-slideInTop` - Geser dari atas
- `animate-swingInRightFwd` - Ayun dari kanan
- `animate-fadeInUp` - Fade dari bawah
- `animate-bounceIn` - Bounce masuk
- `animate-zoomIn` - Zoom masuk

### **Animasi Khusus**
- `page-enter` - Animasi masuk halaman utama
- `card-enter` - Animasi masuk kartu
- `stagger-children` - Animasi bertahap untuk elemen anak

### **Delay Animasi**
- `animate-delay-100` - Delay 0.1s
- `animate-delay-200` - Delay 0.2s
- `animate-delay-300` - Delay 0.3s
- `animate-delay-400` - Delay 0.4s
- `animate-delay-500` - Delay 0.5s

## 🚀 Cara Penggunaan

### **1. Tambahkan Class Animasi**
```html
<div class="animate-slideInLeft">
    Konten yang akan dianimasikan
</div>
```

### **2. Dengan Delay**
```html
<div class="animate-fadeInUp animate-delay-300">
    Konten dengan delay
</div>
```

### **3. Animasi Bertahap**
```html
<div class="stagger-children">
    <div>Item 1</div>
    <div>Item 2</div>
    <div>Item 3</div>
</div>
```

## 📱 Halaman yang Sudah Menggunakan Animasi

### **Dashboard Dosen**
- Header dengan fade in
- Tombol dengan bounce effect
- Kartu notifikasi slide dari atas
- Section mata kuliah hari ini dengan stagger
- Statistik cards dengan slide dari kanan
- Tabel mata kuliah dengan scale in

### **Dashboard Mahasiswa**
- Header dengan fade in
- Tombol dengan bounce effect
- Section mata kuliah hari ini dengan slide kiri
- Cards statistik dengan stagger animation
- Tabel presensi dengan fade in

### **Layout Utama**
- Halaman masuk dengan fade in
- Kartu utama dengan zoom in
- Alert messages dengan staggered fade in

## 🎨 Kustomisasi

Untuk mengubah durasi atau easing, edit file `resources/views/layouts/app.blade.php`:

```css
.animate-slideInLeft {
    animation: slideInLeft 0.5s ease-out forwards;
}
```

## 🎯 Tips Penggunaan

1. **Gunakan animasi secukupnya** - Terlalu banyak animasi bisa membuat halaman terasa lambat
2. **Perhatikan hierarchy** - Gunakan delay untuk membuat flow yang natural
3. **Test di berbagai device** - Pastikan animasi smooth di mobile dan desktop
4. **Gunakan stagger** untuk list/grid items

## 🔧 Troubleshooting

Jika animasi tidak muncul:
1. Pastikan class ditulis dengan benar
2. Check browser console untuk error CSS
3. Pastikan view cache sudah di-clear: `php artisan view:clear`

---

**Demo Page**: Kunjungi `/test-dashboard` untuk melihat semua animasi dalam satu halaman!