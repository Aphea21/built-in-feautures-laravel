
## ✅ OPTION 1 (RECOMMENDED): Use **Concurrent Command** in `package.json`

This is the **Laravel-standard modern way**.

### 1️⃣ Install concurrently

```bash
npm install concurrently --save-dev
```

---

### 2️⃣ Edit `package.json`

Open `package.json` and update the `scripts` section:

```json
"scripts": {
    "dev": "vite",
    "build": "vite build",
    "serve": "php artisan serve",
    "start": "concurrently \"php artisan serve\" \"npm run dev\""
}
```

---

### 3️⃣ Run EVERYTHING with one command

```bash
npm run start
```

✔ Starts Laravel
✔ Starts Vite
✔ Hot reload works
✔ One terminal
✔ One command



So, is it, like, is this standard? Because I'm used to mention each rols in the column, like , and then mention the four roles. This is what the others do in tutorial, so I'm just like used to it. But I don't know if it's standard. And is this your logic that you give it to me, you can also edit the owner rols, since they are default as customer. ?So what's really the standard, you and, or the tutorial? You what you recommend or the tutorial? I don't know. You should help me what's the best.

“The system uses a single role column with a default value of customer to prevent privilege escalation during self-registration. Higher roles such as owner, cashier, and driver are assigned manually or by authorized users. This approach simplifies access control while maintaining security and aligns with the scope of a single-branch operational system.”


dara logo login 
C:\xampp\htdocs\mood-journal\resources\js\components\app-logo-icon.tsx

If you already migrated and need to refresh it:

sh
Copy
Edit
php artisan migrate:refresh --path=database/migrations/xxxx_xx_xx_xxxxxx_create_accepted_books_table.php
(Replace xxxx_xx_xx_xxxxxx with the actual migration filename.)
php artisan migrate:refresh --path=database/migrations\2025_03_31_183534_create_returned_books.php

C:\xampp\htdocs\library\database\migrations\2024_08_31_151842_create_categories_table.php
1. Generate Migration File
Run this command in your terminal:

bash
Copy
Edit
php artisan make:migration create_returns_table



Best Practice
Do NOT put grand_total in tbl_order_items.
Each row is about one product, not the whole order.

Keep grand_total only in tbl_orders.
It’s the header-level total.

You can calculate it dynamically when needed, but storing it in tbl_orders makes reporting & queries faster.

### ✅ Best Practice

* **Do NOT put `grand_total` in `tbl_order_items`.**
  Each row is about *one product*, not the whole order.
* **Keep `grand_total` only in `tbl_orders`.**
  It’s the **header-level total**.

You can **calculate it dynamically** when needed, but storing it in `tbl_orders` makes reporting & queries faster.