# FinanceFlow - Aplikasi Manajemen Keuangan

FinanceFlow adalah aplikasi manajemen keuangan pribadi berbasis web yang dibangun menggunakan Framework **CodeIgniter 4** dan **Bootstrap 5**. Aplikasi ini membantu pengguna untuk mencatat transaksi harian, mengelola anggaran, menetapkan target tabungan, serta mencatat utang piutang.

Dilengkapi dengan sistem **Premium Membership** yang memberikan akses ke fitur-fitur eksklusif seperti laporan statistik mendalam, ekspor/impor data, dan batas kategori yang lebih tinggi.

## 🚀 Fitur Utama

### 1. 📊 Dashboard & Statistik
- Ringkasan pemasukan, pengeluaran, dan saldo bulanan.
- Grafik tren keuangan (Harian/Bulanan).
- Widget target tabungan dan status budget harian.
- **[Premium]** Laporan statistik mendalam dan fitur download laporan (PDF/Excel).

### 2. 💰 Manajemen Transaksi
- Pencatatan Pemasukan dan Pengeluaran.
- Kategori transaksi yang dapat disesuaikan.
- Filter riwayat transaksi berdasarkan tanggal, kategori, dan tipe.
- Upload bukti transaksi (struk/nota).

### 3. 📉 Budget & Perencanaan
- **Budgeting**: Atur batas pengeluaran harian agar keuangan tetap terkontrol.
- **Target Tabungan**: Tetapkan goal tabungan (misal: beli HP, liburan) dan pantau progresnya setiap hari.

### 4. 📒 Utang & Piutang (Debt Note)
- Catat utang atau piutang ke orang lain.
- Riwayat pembayaran cicilan utang.
- Tampilan sisa hutang yang jelas.

### 5. 💎 Sistem Premium Membership
Aplikasi ini memiliki sistem langganan berlangganan dengan tingkatan (Silver, Gold, Platinum).

| Fitur | Free User | Premium User |
| :--- | :---: | :---: |
| **Batas Kategori** | Max 5 Kategori | Max 20 Kategori |
| **Statistik Lanjutan** | 🔒 Terkunci | ✅ Akses Penuh |
| **Export Laporan (PDF/Excel)** | 🔒 Terkunci | ✅ Akses Penuh |
| **Import Transaksi (Excel)** | 🔒 Terkunci | ✅ Akses Penuh |
| **Custom Laporan** | Dasar | Detail & Lengkap |

### 6. 🛡️ Panel Admin
- Dashboard Admin untuk memantau aktivitas user.
- Manajemen Pengguna (User Management).
- Manajemen Langganan (Subscription Management) & Laporan Pendapatan.
- Kelola Kategori Default sistem.

## 🛠️ Teknologi yang Digunakan
- **Frontend**: HTML5, CSS3, Bootstrap 5, Javascript (jQuery), SweetAlert2.
- **Backend**: PHP 8.1+, CodeIgniter 4 Framework.
- **Database**: MySQL.
- **Icons**: Remix Icons.

## 📦 Instalasi

1. **Clone Repository**
   ```bash
   git clone https://github.com/RapidTest25/manajement-keuangan.git
   ```

2. **Setup Database**
   - Buat database baru di MySQL (misal: `financeflow_db`).
   - Import file `database.sql` (jika tersedia) atau jalankan migration (jika ada).

3. **Konfigurasi Environment**
   - Copy file `env` menjadi `.env`.
   - Sesuaikan konfigurasi database:
     ```ini
     database.default.hostname = localhost
     database.default.database = financeflow_db
     database.default.username = root
     database.default.password = 
     database.default.DBDriver = MySQLi
     ```
   - Set `CI_ENVIRONMENT` ke `development` untuk debugging.

4. **Jalankan Aplikasi**
   ```bash
   php spark serve
   ```
   Akses aplikasi di `http://localhost:8080`.

## 🔒 Akun Demo (Default)

**User:**
- Email: `user@demo.com`
- Password: `password`

**Admin:**
- Email: `admin@demo.com`
- Password: `password`

---
*Dibuat untuk memenuhi tugas Penulisan Ilmiah (PI) / Skripsi.*
