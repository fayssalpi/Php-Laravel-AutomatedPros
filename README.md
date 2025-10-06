# 🎟️ Event Booking REST API (Laravel 12 + Sanctum)

A modern Laravel 12 REST API backend for managing events, tickets, bookings, and payments — built following clean architecture (Controllers → Services → Repositories) with Sanctum authentication, role-based access, caching, queues, and testing.

---

## 🚀 Features

### 🔐 Authentication & Authorization
- Laravel **Sanctum** for API token auth
- Role-based access control:
  - **Admin** → Manage all events, tickets, bookings
  - **Organizer** → Manage their own events & tickets
  - **Customer** → Browse events, book tickets, make payments

### 🎫 Core Modules
- **Events**: Create, list, update, delete events (with filters, pagination, caching)
- **Tickets**: Linked to events (VIP, Standard, etc.)
- **Bookings**: Customers can book tickets
- **Payments**: Mock payment simulation (success/failure)

### ⚙️ Architecture
- Repository & Service layer pattern
- Middleware:
  - `RoleMiddleware` → role-based route protection
  - `PreventDoubleBooking` → avoids duplicate bookings
- Trait: `CommonQueryScopes` → `filterByDate()`, `searchByTitle()`

### 📨 Notifications & Queues
- Sends booking confirmation emails
- Uses **queued notifications** for async handling

### 🧪 Testing
- Feature tests for:
  - Registration, Login, Event creation, Ticket booking, Payment
- Unit test for `PaymentService`
- Target coverage: **85%+**

---

## 🛠️ Setup Instructions

### 1️⃣ Clone Repository
```bash
git clone https://github.com/yourusername/laravel-api.git
cd laravel-api


2️⃣ Install Dependencies


cp .env.example .env


DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_api
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
CACHE_STORE=database
MAIL_MAILER=log

4️⃣ Generate App Key


php artisan key:generate


5️⃣ Run Migrations & Seed Data

php artisan migrate:fresh --seed

6️⃣ Start Local Server

php artisan serve
