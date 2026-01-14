Got it. You want **all your model files updated to have a single `use App\Models\{ ... }` statement** at the top for all the related models they reference, without touching anything else in the code. Here's the fully updated code for **all 16 models** with that structure:

---

### **Owner.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\{
    Restaurant, Employee, Waiter, DeliveryBoy,
    Customer, DeliveryAddress, Order, OrderItem, Item,
    MenuSection, Status, StatusDetail, RestaurantRating,
    ItemRating, Comment
};

class Owner extends Model
{
    use SoftDeletes;

    protected $table = 'owners';
    protected $primaryKey = 'owner_id';

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'phone_number'
    ];

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'owner_id', 'owner_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'owner_id', 'owner_id');
    }
}
```

---

### **Restaurant.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\{
    Owner, Employee, MenuSection, RestaurantRating, Comment
};

class Restaurant extends Model
{
    use SoftDeletes;

    protected $table = 'restaurants';
    protected $primaryKey = 'restaurant_id';

    protected $fillable = [
        'owner_id', 'name', 'address', 'city', 'zip_code'
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id', 'owner_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'restaurant_id', 'restaurant_id');
    }

    public function menuSections()
    {
        return $this->hasMany(MenuSection::class, 'restaurant_id', 'restaurant_id');
    }

    public function ratings()
    {
        return $this->hasMany(RestaurantRating::class, 'restaurant_id', 'restaurant_id');
    }
}
```

---

### **Employee.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\{ Owner, Restaurant, Waiter, DeliveryBoy };

class Employee extends Model
{
    use SoftDeletes;

    protected $table = 'employees';
    protected $primaryKey = 'employee_id';

    protected $fillable = [
        'owner_id', 'restaurant_id', 'first_name', 'last_name',
        'role', 'phone_number', 'is_active'
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id', 'owner_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurant_id');
    }

    public function waiter()
    {
        return $this->hasOne(Waiter::class, 'employee_id', 'employee_id');
    }

    public function deliveryBoy()
    {
        return $this->hasOne(DeliveryBoy::class, 'employee_id', 'employee_id');
    }
}
```

---

### **Waiter.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class Waiter extends Model
{
    protected $table = 'waiters';
    protected $primaryKey = 'waiter_id';

    protected $fillable = ['employee_id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
```

---

### **DeliveryBoy.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class DeliveryBoy extends Model
{
    protected $table = 'delivery_boys';
    protected $primaryKey = 'delivery_boy_id';

    protected $fillable = ['employee_id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
```

---

### **Customer.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\{ DeliveryAddress, Order, RestaurantRating, ItemRating, Comment };

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';
    protected $primaryKey = 'customer_id';

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
        'phone_number', 'is_active'
    ];

    public function addresses()
    {
        return $this->hasMany(DeliveryAddress::class, 'customer_id', 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'customer_id');
    }

    public function restaurantRatings()
    {
        return $this->hasMany(RestaurantRating::class, 'customer_id', 'customer_id');
    }

    public function itemRatings()
    {
        return $this->hasMany(ItemRating::class, 'customer_id', 'customer_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'customer_id', 'customer_id');
    }
}
```

---

### **DeliveryAddress.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Customer;

class DeliveryAddress extends Model
{
    use SoftDeletes;

    protected $table = 'delivery_addresses';
    protected $primaryKey = 'delivery_address_id';

    protected $fillable = [
        'customer_id', 'address', 'city', 'zip_code'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
```

---

### **Order.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\{ Customer, DeliveryAddress, OrderItem, StatusDetail };

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'customer_id', 'delivery_address_id', 'total_amount'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(DeliveryAddress::class, 'delivery_address_id', 'delivery_address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function statuses()
    {
        return $this->hasMany(StatusDetail::class, 'order_id', 'order_id');
    }
}
```

---

### **OrderItem.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{ Order, Item };

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'order_id', 'item_id', 'quantity', 'price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }
}
```

---

### **Item.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\{ MenuSection, ItemRating };

class Item extends Model
{
    use SoftDeletes;

    protected $table = 'items';
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'menu_section_id', 'name', 'description', 'price', 'is_available'
    ];

    public function menuSection()
    {
        return $this->belongsTo(MenuSection::class, 'menu_section_id', 'menu_section_id');
    }

    public function ratings()
    {
        return $this->hasMany(ItemRating::class, 'item_id', 'item_id');
    }
}
```

---

### **MenuSection.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\{ Restaurant, Item };

class MenuSection extends Model
{
    use SoftDeletes;

    protected $table = 'menu_sections';
    protected $primaryKey = 'menu_section_id';

    protected $fillable = [
        'restaurant_id', 'name', 'description'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurant_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class, 'menu_section_id', 'menu_section_id');
    }
}
```

---

### **Status.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StatusDetail;

class Status extends Model
{
    protected $table = 'statuses';
    protected $primaryKey = 'status_id';

    protected $fillable = [
        'name', 'description'
    ];

    public function details()
    {
        return $this->hasMany(StatusDetail::class, 'status_id', 'status_id');
    }
}
```

---

### **StatusDetail.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{ Order, Status };

class StatusDetail extends Model
{
    protected $table = 'status_details';
    protected $primaryKey = 'status_detail_id';

    protected $fillable = [
        'order_id', 'status_id', 'changed_at', 'comments'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id', 'status_id');
    }
}
```

---

### **RestaurantRating.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{ Restaurant, Customer };

class RestaurantRating extends Model
{
    protected $table = 'restaurant_ratings';
    protected $primaryKey = 'restaurant_rating_id';

    protected $fillable = [
        'restaurant_id', 'customer_id', 'rating_stars', 'review'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurant_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
```

---

### **ItemRating.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{ Item, Customer };

class ItemRating extends Model
{
    protected $table = 'item_ratings';
    protected $primaryKey = 'item_rating_id';

    protected $fillable = [
        'item_id', 'customer_id', 'rating_stars', 'review'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
```

---

### **Comment.php**

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\{ Customer, Restaurant };

class Comment extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'comment_id';

    protected $fillable = [
        'customer_id', 'restaurant_id', 'content'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_id', 'restaurant_id');
    }
}
```

---

This is the **complete updated set of 16 models** with the unified `use App\Models\{ ... }` style.

If you want, I can also **rewrite them to strictly fix all PSR-4 autoloading warnings**, in case some file names or namespaces are wrong after moving controllers.

Do you want me to do that next?
