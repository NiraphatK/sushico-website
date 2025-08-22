<h1 align="center">🍣 Sushico Queue Reservation System</h1>
<p align="center">
  Same-day reservation system for a sushi restaurant.  
  Built with <b>Laravel 12 (Blade)</b>, <b>PHP</b> & <b>Bootstrap</b>.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php"/>
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel"/>
  <img src="https://img.shields.io/badge/Blade-Template-orange"/>
  <img src="https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap"/>
  <img src="https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql"/>
</p>

---

## ✨ Overview
**Sushico Queue Reservation System** supports **same-day reservations** via web application.  
Customers can sign in with **email or phone number**, select **time, party size, and seat type (BAR or TABLE)**.  

The system confirms reservations only if active bookings do not exceed available seats.  
Table numbers are **not assigned at booking time** — instead, staff assign them during **check-in** to optimize usage and reduce no-shows.  

Admins can configure store policies like **Cut-off Time, Grace Period, Store Hours, and Table Settings**.  

---

## ✨ Features
### 👤 Customer
- Register & log in with email or phone  
- Book, cancel, or edit reservations before cut-off time  
- Choose time, party size, and seat type (BAR / TABLE)  
- Check booking status and confirm arrival (check-in)  

### 🧑‍🍳 Staff
- View daily reservations overview  
- Check in customers and assign actual tables  
- Update reservation status (Confirmed → Seated → Completed / No Show / Cancelled)  
- Assist customers in editing or cancelling after cut-off  

### 🔧 Admin
- Manage table information (add/edit/remove/enable/disable)  
- Configure cut-off, grace period, buffer, store hours, and slot granularity  
- View daily statistics, no-show rate, and peak hours reports  

---

## 🛠 Tech Stack
- **Language** → [PHP 8.2+](https://www.php.net/)  
- **Framework** → [Laravel 12](https://laravel.com/)  
- **View Engine** → [Blade Templates](https://laravel.com/docs/12.x/blade)  
- **Frontend** → [Bootstrap 5](https://getbootstrap.com/)  
- **Database** → [MySQL](https://www.mysql.com/)
---

## ⚡ Getting Started
### Requirements
- PHP 8.2+  
- Composer 2+  
- MySQL

### Installation
```bash
git clone https://github.com/NiraphatK/sushico-website.git
cd sushico-website

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```
Now open: http://localhost:8000 🎉

---

## 📄 Documentation
👉 [📘 Full Project Documentation](https://github.com/NiraphatK/sushico-website/blob/main/Document/%E0%B8%A7%E0%B8%B4%E0%B9%80%E0%B8%84%E0%B8%A3%E0%B8%B2%E0%B8%B0%E0%B8%AB%E0%B9%8C%E0%B9%81%E0%B8%A5%E0%B8%B0%E0%B8%AD%E0%B8%AD%E0%B8%81%E0%B9%81%E0%B8%9A%E0%B8%9A%E0%B8%A3%E0%B8%B0%E0%B8%9A%E0%B8%9A%E0%B8%AB%E0%B8%A3%E0%B8%B7%E0%B8%AD%E0%B9%80%E0%B8%A7%E0%B9%87%E0%B8%9A%E0%B9%84%E0%B8%8B%E0%B8%95%E0%B9%8C.pdf)  


