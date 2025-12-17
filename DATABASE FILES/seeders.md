Perfect! I can rewrite **all your seeders** so that they:

1. Reference your updated **models** in `App\Models`.
2. Use the **correct table columns** and structure based on your last update.
3. Insert **demo/test data** with hashed passwords (`1Qwertyu`), randomized phone numbers, and emails based on first names.
4. Use proper casing and column names (`snake_case` as per your models).

Here’s the **fully updated seeders**:

---

### **CustomerSeeder.php**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['first_name' => 'Anna', 'last_name' => 'Reyes'],
            ['first_name' => 'John', 'last_name' => 'Doe'],
            ['first_name' => 'Maria', 'last_name' => 'Lopez'],
        ];

        foreach ($customers as $c) {
            Customer::create([
                'first_name'   => $c['first_name'],
                'last_name'    => $c['last_name'],
                'email'        => strtolower($c['first_name']) . '@gmail.com',
                'password'     => Hash::make('1Qwertyu'),
                'phone_number' => '09' . rand(200000000, 999999999),
                'is_active'    => true,
            ]);
        }
    }
}
```

---

### **EmployeeSeeder.php**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            ['first_name' => 'Alex', 'last_name' => 'Chan', 'role' => 'waiter'],
            ['first_name' => 'Jane', 'last_name' => 'Lim', 'role' => 'delivery_boy'],
            ['first_name' => 'Carl', 'last_name' => 'Tan', 'role' => 'cashier'],
        ];

        foreach ($employees as $e) {
            Employee::create([
                'owner_id'      => 1, // adjust as needed
                'restaurant_id' => 1, // adjust as needed
                'first_name'    => $e['first_name'],
                'last_name'     => $e['last_name'],
                'role'          => $e['role'],
                'phone_number'  => '09' . rand(200000000, 999999999),
                'is_active'     => true,
            ]);
        }
    }
}
```

---

### **OwnerSeeder.php**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Owner;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        $owners = [
            ['first_name' => 'Wen', 'last_name' => 'Lee'],
            ['first_name' => 'Mark', 'last_name' => 'Smith'],
            ['first_name' => 'Liza', 'last_name' => 'Jones'],
        ];

        foreach ($owners as $o) {
            Owner::create([
                'first_name'   => $o['first_name'],
                'last_name'    => $o['last_name'],
                'email'        => strtolower($o['first_name']) . '@gmail.com',
                'password'     => Hash::make('1Qwertyu'),
                'phone_number' => '09' . rand(200000000, 999999999),
            ]);
        }
    }
}
```

---

### **UserSeeder.php** (for system users)

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Owner;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['first_name' => 'System', 'last_name' => 'Administrator', 'email' => 'admin@gmail.com', 'role' => 'admin'],
            ['first_name' => 'Main', 'last_name' => 'Cashier', 'email' => 'cashier@gmail.com', 'role' => 'cashier'],
            ['first_name' => 'Delivery', 'last_name' => 'Driver', 'email' => 'driver@gmail.com', 'role' => 'driver'],
            ['first_name' => 'Regular', 'last_name' => 'Customer', 'email' => 'customer@gmail.com', 'role' => 'customer'],
        ];

        foreach ($users as $u) {
            Owner::create([
                'first_name' => $u['first_name'],
                'last_name'  => $u['last_name'],
                'email'      => $u['email'],
                'password'   => Hash::make('1Qwertyu'),
                'role'       => $u['role'],
                'phone_number' => '09' . rand(200000000, 999999999),
            ]);
        }
    }
}
```

---

### **RestaurantSeeder.php**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        $restaurants = [
            ['owner_id' => 1, 'name' => 'Sunrise Café', 'address' => '123 Morning Street', 'city' => 'Manila', 'zip_code' => '1001'],
            ['owner_id' => 2, 'name' => 'Ocean Breeze Diner', 'address' => '456 Seaside Ave', 'city' => 'Cebu City', 'zip_code' => '6000'],
            ['owner_id' => 3, 'name' => 'Mountain View Bistro', 'address' => '789 Highland Rd', 'city' => 'Baguio', 'zip_code' => '2600'],
        ];

        foreach ($restaurants as $r) {
            Restaurant::create([
                'owner_id'   => $r['owner_id'],
                'name'       => $r['name'],
                'address'    => $r['address'],
                'city'       => $r['city'],
                'zip_code'   => $r['zip_code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
```

---

### **MenuSectionSeeder.php**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuSection;
use Illuminate\Support\Facades\File;

class MenuSectionSeeder extends Seeder
{
    public function run(): void
    {
        $files = File::files(public_path('images/category'));

        $categories = [
            ['restaurant_id' => 1, 'name' => 'Appetizers',   'description' => 'Light bites to start your meal'],
            ['restaurant_id' => 1, 'name' => 'Main Courses', 'description' => 'Hearty and satisfying dishes'],
            ['restaurant_id' => 1, 'name' => 'Desserts',     'description' => 'Sweet treats to end your meal'],
            ['restaurant_id' => 1, 'name' => 'Beverages',    'description' => 'Refreshing drinks and cocktails'],
            ['restaurant_id' => 1, 'name' => 'Specials',     'description' => 'Chef’s special recommendations'],
        ];

        foreach ($categories as $category) {
            $randomImage = $files[array_rand($files)]->getFilename();

            MenuSection::create([
                'restaurant_id' => $category['restaurant_id'],
                'name'          => $category['name'],
                'description'   => $category['description'],
                'image'         => 'images/category/' . $randomImage,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}
```

---

### **ItemSeeder.php**

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use Illuminate\Support\Facades\File;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $files = File::files(public_path('images/food'));

        $items = [
            ['menu_section_id' => 1, 'name' => 'Spring Rolls', 'description' => 'Crispy vegetable rolls', 'price' => 150.00, 'is_available' => true],
            ['menu_section_id' => 1, 'name' => 'Garlic Bread', 'description' => 'Toasted bread with garlic butter', 'price' => 100.00, 'is_available' => true],
            ['menu_section_id' => 2, 'name' => 'Grilled Chicken', 'description' => 'Served with steamed vegetables', 'price' => 350.00, 'is_available' => true],
            ['menu_section_id' => 2, 'name' => 'Beef Steak', 'description' => 'Juicy steak with mushroom sauce', 'price' => 450.00, 'is_available' => true],
            ['menu_section_id' => 2, 'name' => 'Pasta Alfredo', 'description' => 'Creamy pasta with chicken', 'price' => 300.00, 'is_available' => true],
            ['menu_section_id' => 3, 'name' => 'Chocolate Cake', 'description' => 'Rich and moist chocolate cake', 'price' => 120.00, 'is_available' => true],
            ['menu_section_id' => 3, 'name' => 'Ice Cream Sundae', 'description' => 'Vanilla ice cream with toppings', 'price' => 100.00, 'is_available' => true],
            ['menu_section_id' => 4, 'name' => 'Iced Tea', 'description' => 'Refreshing lemon iced tea', 'price' => 80.00, 'is_available' => true],
            ['menu_section_id' => 4, 'name' => 'Cappuccino', 'description' => 'Hot coffee with milk foam', 'price' => 120.00, 'is_available' => true],
            ['menu_section_id' => 5, 'name' => "Chef's Special Pizza", 'description' => 'Topped with fresh ingredients', 'price' => 400.00, 'is_available' => true],
        ];

        foreach ($items as $item) {
            $randomImage = $files[array_rand($files)]->getFilename();

            Item::create([
                'menu_section_id' => $item['menu_section_id'],
                'name'           => $item['name'],
                'description'    => $item['description'],
                'price'          => $item['price'],
                'is_available'   => $item['is_available'],
                'image'          => 'images/food/' . $randomImage,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
```

---

✅ All seeders now:

* Reference the correct `App\Models\...`.
* Use the proper **snake_case column names** (`first_name`, `last_name`, `phone_number`, etc.).
* Include demo/test data with hashed passwords, randomized phone numbers, and email based on first name.

---

If you want, I can also **update your OrderSeeder and OrderItemSeeder** to generate **dummy orders with relationships** so your test database is fully populated for the bakery/café system.

Do you want me to do that next?
