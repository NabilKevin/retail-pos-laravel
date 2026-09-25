# Retail POS Laravel

Sistem Point of Sale (POS) dan manajemen stok obat berbasis Laravel yang dirancang untuk membantu operasional toko obat/apotek, mulai dari pengelolaan produk, transaksi kasir, hingga laporan penjualan.

## Features

### Admin

- Dashboard admin
- Manajemen data obat
- CRUD user
- Monitoring transaksi
- Cetak struk transaksi
- Laporan penjualan
- Manajemen stok obat

### Kasir

- Dashboard kasir
- Sistem POS / transaksi penjualan
- Pencarian obat
- Riwayat transaksi
- Cetak struk pembayaran

---

## Tech Stack

- Laravel 12
- Laravel Blade
- Laravel Sanctum
- MySQL
- PHP 8.2.12
- Composer 2.8.9

---

## User Roles

### Admin

Memiliki akses penuh terhadap:

- Data obat
- Data user
- Laporan
- Seluruh transaksi

### Kasir

Memiliki akses terhadap:

- POS / transaksi
- Data obat
- Riwayat transaksi
- Cetak struk

---

## Installation

### Clone Repository

```bash
git clone https://github.com/NabilKevin/retail-pos-laravel.git
cd retail-pos-laravel
```

### Install Dependency

```bash
composer install
```

### Copy Environment

```bash
cp .env.example .env
```

### Generate Application Key

```bash
php artisan key:generate
```

### Configure Database

Edit file `.env`

```env
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Run Migration

```bash
php artisan migrate
```

### Run Seeder

```bash
php artisan db:seed
```

### Run Application

```bash
php artisan serve
```

---

## Route Overview

### Authentication

- Login
- Logout

### Admin Routes

- Dashboard
- Obat Management
- User Management
- Transaksi
- Laporan
- Cetak Struk

### Kasir Routes

- Dashboard
- POS
- Search Obat
- Transaksi
- Cetak Struk

---

## Project Structure

Project menggunakan:

- Laravel Blade untuk frontend
- Middleware authentication dan role authorization
- Sanctum authentication
- MVC architecture

---

## Security

- Authentication menggunakan Laravel Sanctum
- Role-based middleware:
    - `isAdmin`
    - `isKasir`

---

## Future Improvements

- Export laporan PDF/Excel
- Barcode scanner integration
- Stock alert system
- Dashboard analytics
- Multi branch support

---

## Repository

GitHub Repository:

https://github.com/NabilKevin/retail-pos-laravel
