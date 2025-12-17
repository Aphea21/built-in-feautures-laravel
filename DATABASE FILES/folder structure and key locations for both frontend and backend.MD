

## 🟢 PHASE 1 — AUTHENTICATION & FOUNDATION (LOCK THIS FIRST)

> **Goal:** Make authentication boring, stable, and final.

### 1. Authentication (WEB – NOT API)

* Laravel Breeze (Blade)
* Login (email + password)
* Register
* Logout
* Session-based authentication

### 2. Email Verification (Gmail)

* Email verification after registration
* Gmail SMTP configuration
* Verified users only can access protected pages

### 3. Forgot Password / Reset Password

* Forgot password form
* Password reset email via Gmail
* Secure token-based reset

### 4. Roles & Access Control

* Roles: Owner, Cashier, Driver, Customer
* Role column or roles table
* Middleware:

  * `auth`
  * `role:owner`
  * `role:cashier`
  * `role:driver`

### 5. Base UI Structure (Blade)

* Layout (header, sidebar, footer)
* Role-based sidebar links
* Auth-protected routes

👉 **END OF PHASE 1 RULE:**
❌ Do NOT touch auth again
❌ Do NOT convert auth to API
✅ Everything must work in browser

---

## 🟡 PHASE 2 — DATABASE & DOMAIN MODELS (NO UI YET)

> **Goal:** Solid data structure before logic.

### 1. Core Tables

* Users
* Customers
* Employees (Cashier / Driver)
* Inventory (Gallons, Caps, Seals)
* Orders
* Order Items
* Statuses

### 2. Relationships

* Customer → Orders
* Order → Order Items
* Order → Status History
* Employee → Assigned Orders

### 3. Seeders (Optional but recommended)

* Default roles
* Default statuses
* Test users (Owner, Cashier, Driver)

👉 **No API yet. No controllers yet.**

---

## 🔵 PHASE 3 — CORE BUSINESS MODULES (API-FIRST)

> **Goal:** Build system logic as APIs (Postman-testable).

> ⚠️ These are **API routes**, but protected by **web session auth**.

---

### 3.1 Customer Management (API)

* Create customer
* Update customer info
* View customer history

**API**

```
GET    /api/customers
POST   /api/customers
PUT    /api/customers/{id}
```

---

### 3.2 Inventory Management (API)

* Track gallons
* Track caps
* Track seals
* Stock in / stock out
* Low stock logic

**API**

```
GET    /api/inventory
POST   /api/inventory/in
POST   /api/inventory/out
```

---

### 3.3 Order Management (API)

* Create order
* Add order items
* Compute totals
* Assign status

**API**

```
POST   /api/orders
GET    /api/orders
GET    /api/orders/{id}
```

👉 **At this phase:**

* Test everything in **Postman**
* UI can come later

---

## 🟣 PHASE 4 — OPERATIONS MODULES (ROLE-BASED API)

> **Goal:** Real-world workflow (Cashier → Driver).

---

### 4.1 Cashier Operations (API)

* Confirm order
* Record payment
* Select payment type (Cash / GCash QR)

**API**

```
POST /api/orders/{id}/confirm
POST /api/orders/{id}/payment
```

---

### 4.2 Driver Operations (API)

* Assign driver
* Update delivery status
* Mark delivered

**API**

```
POST /api/orders/{id}/assign-driver
POST /api/orders/{id}/out-for-delivery
POST /api/orders/{id}/delivered
```

---

### 4.3 Order Status History (API)

* Track every status change
* Timestamped logs

**API**

```
GET /api/orders/{id}/status-history
```

---

## 🟤 PHASE 5 — DASHBOARD & REPORTING (API + UI)

> **Goal:** Owner-level visibility and analytics.

---

### 5.1 Dashboard Metrics (API)

* Total sales
* Orders today
* Pending deliveries
* Inventory levels

**API**

```
GET /api/dashboard/summary
```

---

### 5.2 Sales & Payment Reports (API)

* Daily sales
* Monthly sales
* Payment breakdown

**API**

```
GET /api/reports/sales
```

---

### 5.3 High-Volume Customer Detection

* Analyze order history
* Flag frequent customers

**API**

```
GET /api/customers/high-volume
```

---

## 🔴 PHASE 6 — UX, LOCATION & FINAL POLISH

> **Goal:** Improve usability, not core logic.

---

### 6.1 Customer Location

* Address input
* Optional pin-drop
* Optional geolocation

### 6.2 Notifications (IN-APP ONLY)

* Order confirmed
* Order out for delivery
* Order delivered

⚠️ No SMS / push (per limitations)

---

### 6.3 UI Integration

* Blade pages consume APIs
* `fetch()` or controller-based calls
* Role-specific dashboards

---

### 6.4 Validation & Error Handling

* API validation responses
* User-friendly messages in UI

---

## 🧩 SUMMARY (VERY IMPORTANT)

### What is WEB?

✔ Login
✔ Register
✔ Forgot Password
✔ Email Verification
✔ Role-based access

### What is API?

✔ Customers
✔ Orders
✔ Inventory
✔ Payments
✔ Delivery
✔ Reports
✔ Dashboard

### What you test in Postman:

✔ All API modules
❌ NOT login/register UI

---

## 🟢 WHY THIS IS THE BEST FOR YOU

* Matches your skill level
* Keeps auth stable
* Lets you learn APIs safely
* Easy to test in Postman
* Perfect for capstone defense

---

If you want next, I can:

* Map **each phase → exact routes & controllers**
* Tell you **which phase to commit**
* Build **Postman collections per phase**
* Convert one module into **full API + Blade UI example**

Just tell me what you want to do next.


resources/
 └── views/
      ├── admin/
      │    ├── dashboard.blade.php
      │    ├── products/
      │    │     ├── index.blade.php     (list products)
      │    │     ├── create.blade.php    (add product)
      │    │     ├── edit.blade.php      (edit product)
      │    │
      │    ├── categories/
      │    │     ├── index.blade.php
      │    │     ├── create.blade.php
      │    │     ├── edit.blade.php
      │    │
      │    ├── customers/
      │    │     ├── index.blade.php
      │    │     ├── show.blade.php
      │    │
      │    ├── orders/
      │    │     ├── index.blade.php     (all orders)
      │    │     ├── show.blade.php      (order details)
      │    │     ├── reports.blade.php   (sales reports)
      │    │
      │    └── users/
      │          ├── index.blade.php     (manage staff & drivers)
      │          ├── create.blade.php
      │          ├── edit.blade.php
      │
      ├── staff/
      │    ├── dashboard.blade.php
      │    ├── orders/
      │    │     ├── index.blade.php     (list of assigned orders)
      │    │     ├── update.blade.php    (update status: preparing, ready, etc.)
      │    │
      │    ├── products/
      │    │     ├── index.blade.php     (view products & stock)
      │    │
      │    └── inventory/
      │          ├── index.blade.php     (view stocks, expiry)
      │          ├── low-stock.blade.php (alerts)
      │
      ├── driver/
      │    ├── dashboard.blade.php
      │    ├── deliveries/
      │    │     ├── index.blade.php     (list assigned deliveries)
      │    │     ├── update.blade.php    (mark as delivered/cancelled)
      │    │     ├── history.blade.php   (delivery history)
      │
      └── layouts/
           ├── admin.blade.php   (layout for admin panel)
           ├── staff.blade.php   (layout for staff panel)
           ├── driver.blade.php  (layout for driver panel)
           └── app.blade.php     (shared layout e.g. navbar, footer)









