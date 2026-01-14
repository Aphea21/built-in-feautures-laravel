https://grupoaranea8vccircuit.z21.web.core.windows.net/order-management-system-er-diagram.html

https://www.geeksforgeeks.org/dbms/how-to-design-er-diagrams-for-complex-financial-management-systems/
Perfect 👍 since you already have **migrations**, you don’t need `-m`.
We’ll generate for each entity with **Model + Controller (resource) + Factory + Seeder** using `-crfs`.

Here’s the **full command list for all 16 tables** (copy–paste friendly):

```bash
php artisan make:model Owner -crfs
php artisan make:model Branch -crfs
php artisan make:model Employee -crfs
php artisan make:model Customer -crfs
php artisan make:model DeliveryAddress -crfs
php artisan make:model Category -crfs
php artisan make:model Item -crfs
php artisan make:model Order -crfs
php artisan make:model OrderItem -crfs
php artisan make:model Status -crfs
php artisan make:model StatusDetail -crfs
php artisan make:model BranchRating -crfs
php artisan make:model ItemRating -crfs
php artisan make:model Comment -crfs

```

---

⚡ Explanation:

* `Owner`, `Restaurant`, `Employee` → for management hierarchy.
* `MenuSection`, `Item`, `ItemRating` → for menu management.
* `Customer`, `DeliveryAddress`, `Order`, `OrderItem` → for order flow.
* `RestaurantRating` → feedback for restaurants.
* `Status`, `StatusDetail` → order tracking.

That’s **13 commands** because some of your 16 schema elements are **relationships/pivots** and don’t need their own model (like `OrderItem`, which does, but others are just FK relationships).

---

👉 After this, you’ll still need to **edit** the generated models:

* Add `fillable` fields.
* Define `belongsTo`, `hasMany`, etc.

---

Do you want me to also generate the **relationship code snippets** (like `Restaurant hasMany Items`, `Order belongsTo Customer`) so you just paste them into each model?




php artisan make:migration create_tbl_owners_table
php artisan make:migration create_tbl_branches_table
php artisan make:migration create_tbl_employees_table
php artisan make:migration create_tbl_customers_table
php artisan make:migration create_tbl_delivery_addresses_table
php artisan make:migration create_tbl_categories_table
php artisan make:migration create_tbl_items_table
php artisan make:migration create_tbl_orders_table
php artisan make:migration create_tbl_order_items_table
php artisan make:migration create_tbl_statuses_table
php artisan make:migration create_tbl_status_details_table
php artisan make:migration create_tbl_branch_ratings_table
php artisan make:migration create_tbl_item_ratings_table
php artisan make:migration create_tbl_comments_table




<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Owners
        Schema::create('owners', function (Blueprint $table) {
            $table->id(); // OwnerID
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('phone_number', 15)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Branches (was restaurants)
        Schema::create('branches', function (Blueprint $table) {
            $table->id(); // BranchID
            $table->foreignId('owner_id')->constrained()->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('address');
            $table->string('city', 100);
            $table->string('zip_code', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Optional future branch features:
            // $table->string('branch_code')->nullable();
            // $table->decimal('latitude', 10, 7)->nullable();
            // $table->decimal('longitude', 10, 7)->nullable();
        });

        // 3. Employees (Unified)
        Schema::create('employees', function (Blueprint $table) {
            $table->id(); // EmployeeID
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete(); // links to branch
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->enum('role', ['cashier', 'driver', 'waiter', 'manager']);
            $table->string('phone_number', 15)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Customers
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // CustomerID
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('phone_number', 15)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Delivery Addresses
        Schema::create('delivery_addresses', function (Blueprint $table) {
            $table->id(); // AddressID
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('address');
            $table->string('city', 100);
            $table->string('zip_code', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 6. Categories (was menu_sections)
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // CategoryID
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. Items
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // ItemID
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 8. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // OrderID
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('delivery_address_id')->constrained()->cascadeOnDelete();
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        // 9. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // OrderItemID
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        // 10. Statuses
        Schema::create('statuses', function (Blueprint $table) {
            $table->id(); // StatusID
            $table->string('name', 100);
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // 11. Status Details
        Schema::create('status_details', function (Blueprint $table) {
            $table->id(); // StatusDetailID
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('status_id')->constrained()->cascadeOnDelete();
            $table->timestamp('changed_at')->useCurrent();
            $table->string('comments')->nullable();
            $table->timestamps();
        });

        // 12. Branch Ratings (was restaurant_ratings)
        Schema::create('branch_ratings', function (Blueprint $table) {
            $table->id(); // BranchRatingID
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('rating_stars');
            $table->text('review')->nullable();
            $table->timestamps();
        });

        // 13. Item Ratings
        Schema::create('item_ratings', function (Blueprint $table) {
            $table->id(); // ItemRatingID
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('rating_stars');
            $table->text('review')->nullable();
            $table->timestamps();
        });

        // 14. Comments
        Schema::create('comments', function (Blueprint $table) {
            $table->id(); // CommentID
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('item_ratings');
        Schema::dropIfExists('branch_ratings');
        Schema::dropIfExists('status_details');
        Schema::dropIfExists('statuses');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('items');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('delivery_addresses');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('owners');
    }
};
