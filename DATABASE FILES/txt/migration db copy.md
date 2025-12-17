https://grupoaranea8vccircuit.z21.web.core.windows.net/order-management-system-er-diagram.html

https://www.geeksforgeeks.org/dbms/how-to-design-er-diagrams-for-complex-financial-management-systems/
Perfect 👍 since you already have **migrations**, you don’t need `-m`.
We’ll generate for each entity with **Model + Controller (resource) + Factory + Seeder** using `-crfs`.

Here’s the **full command list for all 16 tables** (copy–paste friendly):

```bash
php artisan make:model Owner -crfs
php artisan make:model Restaurant -crfs
php artisan make:model Employee -crfs

php artisan make:model MenuSection -crfs
php artisan make:model Item -crfs
php artisan make:model ItemRating -crfs

php artisan make:model Customer -crfs
php artisan make:model DeliveryAddress -crfs
php artisan make:model Order -crfs
php artisan make:model OrderItem -crfs

php artisan make:model RestaurantRating -crfs

php artisan make:model Status -crfs
php artisan make:model StatusDetail -crfs
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
php artisan make:migration create_tbl_restaurants_table
php artisan make:migration create_tbl_employees_table
php artisan make:migration create_tbl_waiters_table
php artisan make:migration create_tbl_delivery_boys_table
php artisan make:migration create_tbl_customers_table
php artisan make:migration create_tbl_delivery_addresses_table
php artisan make:migration create_tbl_orders_table
php artisan make:migration create_tbl_menu_sections_table
php artisan make:migration create_tbl_items_table
php artisan make:migration create_tbl_order_items_table
php artisan make:migration create_tbl_statuses_table
php artisan make:migration create_tbl_status_details_table
php artisan make:migration create_tbl_restaurant_ratings_table
php artisan make:migration create_tbl_item_ratings_table
php artisan make:migration create_tbl_comments_table



🔹 Updated Flow Overview (ERD-like)
Customer → DeliveryAddress → Order → OrderItem → Item → ItemRating

Owner → Restaurant → MenuSection → Item
Owner → Employee → (Waiter | DeliveryBoy)

Restaurant → RestaurantRating ← Customer

Order → StatusDetails → Status

This follows all your standards:

✅ tbl_ prefix for table names

✅ Plural table names

✅ PascalCase for columns

✅ Primary Key = [SingularName]ID (10-digit, bigIncrements)

✅ Foreign keys consistently named (OwnerID, CustomerID, etc.)

✅ Soft deletes + timestamps

✅ Clear, descriptive column names (no cryptic ones)


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Owners
        Schema::create('tbl_owners', function (Blueprint $table) {
            $table->bigIncrements('OwnerID');
            $table->string('FirstName', 100);
            $table->string('LastName', 100);
            $table->string('Email', 150)->unique();
            $table->string('Password', 255);
            $table->string('PhoneNumber', 15)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Restaurants
        Schema::create('tbl_restaurants', function (Blueprint $table) {
            $table->bigIncrements('RestaurantID');
            $table->unsignedBigInteger('OwnerID');
            $table->string('Name', 150);
            $table->string('Address', 255);
            $table->string('City', 100);
            $table->string('ZipCode', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('OwnerID')->references('OwnerID')->on('tbl_owners')->onDelete('cascade');
        });

        // 3. Employees
        Schema::create('tbl_employees', function (Blueprint $table) {
            $table->bigIncrements('EmployeeID');
            $table->unsignedBigInteger('OwnerID');
            $table->unsignedBigInteger('RestaurantID');
            $table->string('FirstName', 100);
            $table->string('LastName', 100);
            $table->string('Role', 50); // waiter, delivery_boy, cashier, etc.
            $table->string('PhoneNumber', 15)->nullable();
            $table->boolean('IsActive')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('OwnerID')->references('OwnerID')->on('tbl_owners')->onDelete('cascade');
            $table->foreign('RestaurantID')->references('RestaurantID')->on('tbl_restaurants')->onDelete('cascade');
        });

        // 4. Waiters
        Schema::create('tbl_waiters', function (Blueprint $table) {
            $table->bigIncrements('WaiterID');
            $table->unsignedBigInteger('EmployeeID');
            $table->timestamps();

            $table->foreign('EmployeeID')->references('EmployeeID')->on('tbl_employees')->onDelete('cascade');
        });

        // 5. Delivery Boys
        Schema::create('tbl_delivery_boys', function (Blueprint $table) {
            $table->bigIncrements('DeliveryBoyID');
            $table->unsignedBigInteger('EmployeeID');
            $table->timestamps();

            $table->foreign('EmployeeID')->references('EmployeeID')->on('tbl_employees')->onDelete('cascade');
        });

        // 6. Customers
        Schema::create('tbl_customers', function (Blueprint $table) {
            $table->bigIncrements('CustomerID');
            $table->string('FirstName', 100);
            $table->string('LastName', 100);
            $table->string('Email', 150)->unique();
            $table->string('Password', 255);
            $table->string('PhoneNumber', 15)->nullable();
            $table->boolean('IsActive')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 7. Delivery Addresses
        Schema::create('tbl_delivery_addresses', function (Blueprint $table) {
            $table->bigIncrements('DeliveryAddressID');
            $table->unsignedBigInteger('CustomerID');
            $table->string('Address', 255);
            $table->string('City', 100);
            $table->string('ZipCode', 20)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('CustomerID')->references('CustomerID')->on('tbl_customers')->onDelete('cascade');
        });

        // 8. Orders
        Schema::create('tbl_orders', function (Blueprint $table) {
            $table->bigIncrements('OrderID');
            $table->unsignedBigInteger('CustomerID');
            $table->unsignedBigInteger('DeliveryAddressID');
            $table->decimal('TotalAmount', 10, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('CustomerID')->references('CustomerID')->on('tbl_customers')->onDelete('cascade');
            $table->foreign('DeliveryAddressID')->references('DeliveryAddressID')->on('tbl_delivery_addresses')->onDelete('cascade');
        });

        // 9. Order Items
        Schema::create('tbl_order_items', function (Blueprint $table) {
            $table->bigIncrements('OrderItemID');
            $table->unsignedBigInteger('OrderID');
            $table->unsignedBigInteger('ItemID');
            $table->integer('Quantity');
            $table->decimal('Price', 10, 2);
            $table->timestamps();

            $table->foreign('OrderID')->references('OrderID')->on('tbl_orders')->onDelete('cascade');
            $table->foreign('ItemID')->references('ItemID')->on('tbl_items')->onDelete('cascade');
        });

        // 10. Items
        Schema::create('tbl_items', function (Blueprint $table) {
            $table->bigIncrements('ItemID');
            $table->unsignedBigInteger('MenuSectionID');
            $table->string('Name', 150);
            $table->text('Description')->nullable();
            $table->decimal('Price', 10, 2);
            $table->boolean('IsAvailable')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('MenuSectionID')->references('MenuSectionID')->on('tbl_menu_sections')->onDelete('cascade');
        });

        // 11. Menu Sections
        Schema::create('tbl_menu_sections', function (Blueprint $table) {
            $table->bigIncrements('MenuSectionID');
            $table->unsignedBigInteger('RestaurantID');
            $table->string('Name', 150);
            $table->string('Description', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('RestaurantID')->references('RestaurantID')->on('tbl_restaurants')->onDelete('cascade');
        });

        // 12. Statuses
        Schema::create('tbl_statuses', function (Blueprint $table) {
            $table->bigIncrements('StatusID');
            $table->string('Name', 100);
            $table->string('Description', 255)->nullable();
            $table->timestamps();
        });

        // 13. Status Details
        Schema::create('tbl_status_details', function (Blueprint $table) {
            $table->bigIncrements('StatusDetailID');
            $table->unsignedBigInteger('OrderID');
            $table->unsignedBigInteger('StatusID');
            $table->timestamp('ChangedAt')->useCurrent();
            $table->string('Comments', 255)->nullable();
            $table->timestamps();

            $table->foreign('OrderID')->references('OrderID')->on('tbl_orders')->onDelete('cascade');
            $table->foreign('StatusID')->references('StatusID')->on('tbl_statuses')->onDelete('cascade');
        });

        // 14. Restaurant Ratings
        Schema::create('tbl_restaurant_ratings', function (Blueprint $table) {
            $table->bigIncrements('RestaurantRatingID');
            $table->unsignedBigInteger('RestaurantID');
            $table->unsignedBigInteger('CustomerID');
            $table->tinyInteger('RatingStars');
            $table->text('Review')->nullable();
            $table->timestamps();

            $table->foreign('RestaurantID')->references('RestaurantID')->on('tbl_restaurants')->onDelete('cascade');
            $table->foreign('CustomerID')->references('CustomerID')->on('tbl_customers')->onDelete('cascade');
        });

        // 15. Item Ratings
        Schema::create('tbl_item_ratings', function (Blueprint $table) {
            $table->bigIncrements('ItemRatingID');
            $table->unsignedBigInteger('ItemID');
            $table->unsignedBigInteger('CustomerID');
            $table->tinyInteger('RatingStars');
            $table->text('Review')->nullable();
            $table->timestamps();

            $table->foreign('ItemID')->references('ItemID')->on('tbl_items')->onDelete('cascade');
            $table->foreign('CustomerID')->references('CustomerID')->on('tbl_customers')->onDelete('cascade');
        });

        // 16. Comments
        Schema::create('tbl_comments', function (Blueprint $table) {
            $table->bigIncrements('CommentID');
            $table->unsignedBigInteger('CustomerID');
            $table->unsignedBigInteger('RestaurantID');
            $table->text('Content');
            $table->timestamps();

            $table->foreign('CustomerID')->references('CustomerID')->on('tbl_customers')->onDelete('cascade');
            $table->foreign('RestaurantID')->references('RestaurantID')->on('tbl_restaurants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_comments');
        Schema::dropIfExists('tbl_item_ratings');
        Schema::dropIfExists('tbl_restaurant_ratings');
        Schema::dropIfExists('tbl_status_details');
        Schema::dropIfExists('tbl_statuses');
        Schema::dropIfExists('tbl_menu_sections');
        Schema::dropIfExists('tbl_items');
        Schema::dropIfExists('tbl_order_items');
        Schema::dropIfExists('tbl_orders');
        Schema::dropIfExists('tbl_delivery_addresses');
        Schema::dropIfExists('tbl_customers');
        Schema::dropIfExists('tbl_delivery_boys');
        Schema::dropIfExists('tbl_waiters');
        Schema::dropIfExists('tbl_employees');
        Schema::dropIfExists('tbl_restaurants');
        Schema::dropIfExists('tbl_owners');
    }
};
