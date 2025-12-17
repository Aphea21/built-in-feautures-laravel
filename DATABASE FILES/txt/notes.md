

C:\Users\HP\Documents\bakery2\bakery\app\Models\Owner.php

C:\Users\HP\Documents\bakery2\bakery\config\auth.php

C:\Users\HP\Documents\bakery2\bakery\app\Http\Controllers\Auth\AuthenticatedSessionController.php



C:\Users\HP\Documents\bakery2\bakery\app\Http\Middleware\Role.php



C:\Users\HP\Documents\bakery2\bakery\routes\web.php




C:\Users\HP\Documents\bakery2\bakery\app\Http\Controllers\Auth\UserLoginController.php



C:\Users\HP\Documents\bakery2\bakery\resources\views\auth\login.blade.php

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