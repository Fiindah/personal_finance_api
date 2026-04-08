# ⚡ Personal Finance API (Laravel)

REST API untuk aplikasi manajemen keuangan pribadi yang dibangun menggunakan **Laravel**.  
API ini menangani data seperti transaksi, budget, dan tabungan (pots).

Backend ini dirancang ringan untuk development lokal menggunakan **SQLite**, serta dapat di-deploy ke production menggunakan **MySQL**.

---

## 🚀 Live API

Base URL:
https://personal-financeapp.up.railway.app/api

Contoh endpoint:
GET /overview

---

## 🧰 Teknologi yang Digunakan

- Laravel
- PHP 8.2+
- SQLite (Development)
- MySQL (Production - Railway)
- REST API (JSON)

---

## ⚙️ Cara Menjalankan di Lokal

Ikuti langkah berikut untuk menjalankan project di lokal:

### 1. Persiapan
- PHP 8.2+ (disarankan pakai Laragon)
- Composer
- SQLite extension aktif di `php.ini`

---

### 2. Install Dependency
```bash
composer install
````

---

### 3. Setup Environment

```bash
cp .env.example .env
php artisan key:generate
```

---

### 4. Setup Database (SQLite)

```bash
# buat file database
touch database/database.sqlite

# jalankan migration + seeder
php artisan migrate --seed
```

---

### 5. Jalankan Server

```bash
php artisan serve
```

Akses API di:
[http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🗄️ Konfigurasi Database

Project ini menggunakan:

* SQLite → untuk development lokal (ringan & hemat RAM)
* MySQL → untuk production di Railway

---

## 📡 Dokumentasi API

Semua response menggunakan format **JSON**

| Method | Endpoint               | Deskripsi             |
| ------ | ---------------------- | --------------------- |
| GET    | /api/overview          | Ringkasan keuangan    |
| GET    | /api/transactions      | Daftar transaksi      |
| GET    | /api/pots              | Daftar tabungan       |
| POST   | /api/pots              | Tambah tabungan       |
| PATCH  | /api/pots/{id}/balance | Update saldo tabungan |
| DELETE | /api/pots/{id}         | Hapus tabungan        |
| GET    | /api/recurring-bills   | Daftar tagihan rutin  |

---

### Contoh Request

```bash
GET /api/transactions?search=dining&sort=latest
```

---

## 🧪 Contoh Response

```json
{
  "status": "success",
  "data": {
    "balance": {
      "current": 32.7,
      "income": 195.5,
      "expenses": 162.8
    }
  }
}
```

---

## ⚙️ Fitur Utama

* ✅ REST API dengan struktur yang rapi
* ✅ Menggunakan Eloquent ORM
* ✅ Validasi dan keamanan (Mass Assignment Protection)
* ✅ Relasi antar model (Budget & Transactions)
* ✅ CORS aktif (siap untuk Flutter)

---

## 🧪 Testing API

Gunakan tools seperti:

* Postman
* Insomnia
* Browser

Contoh:
[http://127.0.0.1:8000/api/overview](http://127.0.0.1:8000/api/overview)

---

## 📌 Catatan

* Project ini hanya backend API (tanpa frontend)
* Data awal diisi menggunakan seeder
* Digunakan untuk integrasi dengan aplikasi Flutter

---

## 👨‍💻 Author

Dibuat untuk pembelajaran dan pengembangan aplikasi fullstack (Laravel + Flutter)

```