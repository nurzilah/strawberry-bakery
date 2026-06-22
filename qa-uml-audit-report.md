# Strict QA UML Feature Audit: Strawberry Bakery

Baseline used: the UML mappings supplied in the request. `UML ACC.docx` was not found anywhere under `C:\skripsi\strawberry-bakery`, so this audit does not claim to have parsed the original DOCX.

## 1. [COMPLIANT]

- Authentication routes use sequence-appropriate methods:
  - `routes/auth.php:15` `GET register` renders the form.
  - `routes/auth.php:18` `POST register` persists registration.
  - `routes/auth.php:20` `GET login` renders the form.
  - `routes/auth.php:23` `POST login` validates credentials and starts session.
- Registration validation exists in `app/Http/Controllers/Auth/RegisteredUserController.php:31`, including name, unique email, password confirmation, and password strength.
- Login credential validation and throttling exists in `app/Http/Requests/Auth/LoginRequest.php:27` and `authenticate()` at line 48.
- Customer product browsing exists:
  - Route: `routes/web.php:29` `GET /products`.
  - Category filtering: `app/Http/Controllers/ProductController.php:17`.
  - Price/rating filtering: `ProductController.php:24-32`.
- Admin/manager product and category management exists:
  - Routes: `routes/web.php:80-81`.
  - Product CRUD controller: `app/Http/Controllers/Admin/ProductController.php`.
  - Category CRUD controller: `app/Http/Controllers/Admin/CategoryController.php`.
- Admin dashboard prepares revenue arrays for Chart.js-style reporting:
  - Daily labels/data: `app/Http/Controllers/Admin/AdminDashboardController.php:35-50`.
  - Monthly labels/data: `AdminDashboardController.php:53-72`.
- Product model has correct local relationships for category/order items/carts:
  - `app/Models/Product.php:28`, `33`, `38`.
- Rating model has user/product relationships:
  - `app/Models/Rating.php:18`, `23`.
- Admin order status form buttons use POST plus Blade method spoofing to PATCH in the views:
  - `resources/views/admin/orders/index.blade.php`.
  - `resources/views/kasir/orders/index.blade.php`.
- Kasir offline transaction controller exists:
  - `app/Http/Controllers/Kasir/TransactionController.php:17` create.
  - `TransactionController.php:26` store.
  - `TransactionController.php:101` receipt.

## 2. [NON-COMPLIANT / MISSING / BUGGY]

### Database and Class Diagram Alignment

- Mandatory table prefix `strawberry_bakery_` is not used. Core migrations create unprefixed tables:
  - `users`: `database/migrations/2014_10_12_000000_create_users_table.php:16`.
  - `categories`: `2026_01_02_121410_create_categories_table.php:11`.
  - `products`: `2026_01_02_121420_create_products_table.php:11`.
  - `orders`: `2026_01_02_121432_create_orders_table.php:16`.
  - `order_items`: `2026_01_02_121439_create_order_items_table.php:16`.
  - `carts`: `2026_01_02_121446_create_carts_table.php:11`.
- Models do not set prefixed `$table` names. `User`, `Product`, `Order`, and `OrderItem` rely on Laravel defaults; `Rating` explicitly uses unprefixed `ratings`.
- `Order` and `OrderItem` are structurally incomplete:
  - `app/Models/Order.php` contains no `$fillable`, casts, `user()`, `items()`, or rating relationship.
  - `app/Models/OrderItem.php` contains no `$fillable`, casts, `order()`, or `product()`.
- Required UML models are missing:
  - `app/Models/Recommendation.php` is absent.
  - `app/Models/Setting.php` is absent.
- Required migrations are missing:
  - No `strawberry_bakery_ratings` table migration.
  - No `strawberry_bakery_recommendations` table migration.
  - No `strawberry_bakery_settings` table migration.
- User role support is inconsistent:
  - DB migration includes `owner`: `database/migrations/2026_06_21_185028_update_role_column_in_users_table.php:16`.
  - `User::isAdmin()` excludes owner: `app/Models/User.php:45`.
  - User management UI/controller excludes owner: `app/Http/Controllers/Admin/UserController.php:13` and line 22.
  - `ManagerMiddleware` allows only admin/superadmin, so owner cannot access owner UML features.
- Cart schema and model are internally inconsistent:
  - Migration `2026_02_25_000001_add_detail_carts_table_and_update_carts.php` drops `product_id` and `quantity` from `carts`.
  - `app/Models/Cart.php` still uses fillable `product_id`, `quantity` and has no `detailCart()` relationship.
  - `CartController` still validates `exists:products,id` and writes `product_id`/`quantity`, so it breaks after that migration.

### Use Case and Activity Flow Gaps

- Customer pre-order checkout is not implemented:
  - `CheckoutController@index()` does not load cart rows or totals.
  - `CheckoutController@process()` only redirects home and does not validate pickup date/payment proof, create an order, create order items, upload proof, clear cart, decrement stock, or set UML status.
- UML status `waiting_verification` is not supported. Current statuses use Indonesian strings such as `menunggu_konfirmasi`.
- Payment proof upload is present in Blade but not handled in the controller:
  - View input: `resources/views/checkout/index.blade.php:79`.
  - No storage call in `CheckoutController`.
- Customer rating flow is missing:
  - No route.
  - No controller method.
  - No view form.
  - No enforcement that only completed orders can be rated.
- Customer order list/detail routes point to empty methods:
  - `routes/web.php:64-65`.
  - `app/Http/Controllers/OrderController.php` has no `index()` or `show()`.
- Kasir feature is not routable:
  - `app/Http/Controllers/Kasir/TransactionController.php` exists, but `php artisan route:list` contains no `kasir.*` routes.
  - `resources/views/kasir/orders/index.blade.php` references route names that do not exist.
- Admin order confirmation route is broken:
  - View calls `route($confirmRoute, $order)` with `$confirmRoute = 'admin.orders.confirm'`.
  - `routes/web.php` does not define `admin.orders.confirm`.
- Kasir/admin payment verification transition does not match UML:
  - `Admin\OrderController@confirm()` changes `menunggu_konfirmasi` to `dikonfirmasi`.
  - UML requires verification logic transitioning to `processing`.
- Owner role has no route group, middleware, dashboard, or reports route. Existing dashboard is behind admin middleware and excludes `owner`.
- IT Developer system configuration is missing:
  - No `Setting` model, migration, controller, routes, or Blade views.
- IT Developer backup data is missing:
  - No controller utility for compressed JSON/ZIP table extraction.
  - No route or view.

### Recommendation Algorithm Compliance

- PHP-ML is not installed. `composer.json` has no `php-ai/php-ml` package.
- `app/Services/RecommendationService.php` implements manual cosine similarity, not PHP-ML User-Based Collaborative Filtering.
- K default is `5` in `forUser(int $userId, int $k = 5, ...)`, but because PHP-ML is not used, the exact library-backed KNN requirement is not met.
- Cold start rule is wrong:
  - UML says fallback only if user has zero transaction/rating logs.
  - Current code falls back when rating count is `< 2`.
  - Current fallback checks unprefixed `products` table and falls back to `latest()` if `purchase_count` is missing; UML requires `purchase_count DESC` from `strawberry_bakery_products`.

### Sequence Diagram HTTP Method Audit

- Most mutation forms use POST/PATCH/PUT/DELETE correctly.
- `routes/web.php:76` uses `POST admin/orders/{order}/status`, while Blade spoofs PATCH. Laravel will route this as POST, not PATCH. Define it as `Route::patch(...)`.
- `routes/web.php:87` uses `POST admin/users/{user}/role` for role mutation. UML allows POST/PATCH/PUT for state changes, but PATCH is semantically stricter.
- Missing state-changing routes for payment verification/rating/settings/backup cannot pass the sequence audit.

## 3. [PATCH SCRIPT]

The following snippets are the minimum coherent patch set to align the application with the supplied UML baseline. They are written as complete replacement/addition snippets. Before applying to a real database with data, prepare a data migration plan because table renaming from unprefixed to prefixed names is destructive if handled casually.

### composer.json dependency

Add this to `require` and run `composer update php-ai/php-ml`:

```json
"php-ai/php-ml": "^0.10"
```

### config/database.php table prefix

Laravel can enforce the mandatory prefix globally. Set the MySQL connection prefix:

```php
'mysql' => [
    // existing keys...
    'prefix' => 'strawberry_bakery_',
    'prefix_indexes' => true,
],
```

After this, model `$table` values below intentionally use unprefixed logical names, because Laravel will query physical tables such as `strawberry_bakery_users`. If you prefer explicit physical table names, remove the connection prefix and set every model `$table` to `strawberry_bakery_*`.

### routes/web.php additions/replacements

```php
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Kasir\TransactionController as KasirTransactionController;
use App\Http\Controllers\RecommendationController;

Route::middleware(['auth'])->group(function () {
    Route::get('/recommendations', [RecommendationController::class, 'index'])->name('recommendations.index');
    Route::post('/orders/{order}/ratings', [OrderController::class, 'storeRating'])->name('orders.ratings.store');
});

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::patch('orders/{order}/confirm', [\App\Http\Controllers\Admin\OrderController::class, 'confirm'])->name('orders.confirm');
        Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
        Route::resource('settings', SettingController::class)->only(['index', 'store']);
        Route::get('backup', [BackupController::class, 'index'])->name('backup.index');
        Route::post('backup', [BackupController::class, 'store'])->name('backup.store');
    });

Route::middleware(['auth', \App\Http\Middleware\KasirMiddleware::class])
    ->prefix('kasir')->name('kasir.')->group(function () {
        Route::get('transactions/create', [KasirTransactionController::class, 'create'])->name('transactions.create');
        Route::post('transactions', [KasirTransactionController::class, 'store'])->name('transactions.store');
        Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::patch('orders/{order}/confirm', [\App\Http\Controllers\Admin\OrderController::class, 'confirm'])->name('orders.confirm');
        Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
        Route::get('orders/{order}/receipt', [KasirTransactionController::class, 'receipt'])->name('orders.receipt');
    });
```

### app/Models/User.php

```php
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'phone', 'address', 'role', 'google_id', 'avatar'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    public function carts() { return $this->hasMany(Cart::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function ratings() { return $this->hasMany(Rating::class); }
    public function recommendations() { return $this->hasMany(Recommendation::class); }

    public function isSuperadmin(): bool { return $this->role === 'superadmin'; }
    public function isOwner(): bool { return $this->role === 'owner'; }
    public function isAdmin(): bool { return in_array($this->role, ['admin', 'superadmin', 'owner', 'kasir'], true); }
    public function isKasir(): bool { return $this->role === 'kasir'; }
    public function isCustomer(): bool { return $this->role === 'customer' || empty($this->role); }
}
```

### app/Models/Product.php

```php
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price', 'image', 'stock',
        'rating', 'reviews_count', 'purchase_count', 'is_featured', 'is_new',
        'is_promo', 'is_available', 'pickup_date',
    ];

    protected $casts = [
        'price' => 'integer',
        'rating' => 'float',
        'purchase_count' => 'integer',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_promo' => 'boolean',
        'is_available' => 'boolean',
        'pickup_date' => 'date',
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function orderItems() { return $this->hasMany(OrderItem::class); }
    public function carts() { return $this->hasMany(Cart::class); }
    public function ratings() { return $this->hasMany(Rating::class); }
    public function recommendations() { return $this->hasMany(Recommendation::class); }
}
```

### app/Models/Order.php

```php
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_number', 'total_amount', 'status', 'payment_method',
        'payment_proof_path', 'qris_expires_at', 'pickup_date',
    ];

    protected $casts = [
        'total_amount' => 'integer',
        'qris_expires_at' => 'datetime',
        'pickup_date' => 'date',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function ratings() { return $this->hasMany(Rating::class); }
}
```

### app/Models/OrderItem.php

```php
class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'product_name', 'quantity', 'price', 'subtotal'];
    protected $casts = ['quantity' => 'integer', 'price' => 'integer', 'subtotal' => 'integer'];

    public function order() { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
```

### app/Models/Rating.php

```php
class Rating extends Model
{
    protected $fillable = ['user_id', 'order_id', 'product_id', 'rating', 'review'];
    protected $casts = ['rating' => 'integer'];

    public function user() { return $this->belongsTo(User::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
```

### app/Models/Recommendation.php

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    protected $fillable = ['user_id', 'product_id', 'score', 'algorithm', 'generated_at'];
    protected $casts = ['score' => 'float', 'generated_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
```

### app/Models/Setting.php

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];
}
```

### migration for UML tables/columns

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_number')) $table->string('order_number')->nullable()->unique()->after('id');
            if (Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('pending')->change();
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'product_name')) $table->string('product_name')->nullable()->after('product_id');
            if (!Schema::hasColumn('order_items', 'subtotal')) $table->integer('subtotal')->default(0)->after('price');
        });

        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('review')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'order_id', 'product_id']);
        });

        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 8, 4)->nullable();
            $table->string('algorithm')->default('user_based_cf_knn_k5');
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('group')->default('system');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('recommendations');
        Schema::dropIfExists('ratings');
    }
};
```

### app/Http/Controllers/CheckoutController.php

```php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $carts = auth()->user()->carts()->with('product.category')->get();
        $total = $carts->sum(fn ($cart) => $cart->product->price * $cart->quantity);
        return view('checkout.index', compact('carts', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'pickup_date' => ['required', 'date', 'after_or_equal:' . now()->addDays(2)->toDateString()],
            'payment_method' => ['required', 'in:bca,dana,shopeepay,gopay'],
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $carts = auth()->user()->carts()->with('product')->get();
        abort_if($carts->isEmpty(), 422, 'Keranjang kosong.');

        $order = DB::transaction(function () use ($request, $carts) {
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');
            $total = $carts->sum(fn ($cart) => $cart->product->price * $cart->quantity);

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
                'total_amount' => $total,
                'status' => 'waiting_verification',
                'payment_method' => $request->payment_method,
                'payment_proof_path' => $path,
                'pickup_date' => Carbon::parse($request->pickup_date)->toDateString(),
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'product_name' => $cart->product->name,
                    'quantity' => $cart->quantity,
                    'price' => $cart->product->price,
                    'subtotal' => $cart->product->price * $cart->quantity,
                ]);
                $cart->product->increment('purchase_count', $cart->quantity);
                $cart->product->decrement('stock', $cart->quantity);
            }

            Cart::where('user_id', auth()->id())->delete();
            return $order;
        });

        return redirect()->route('orders.show', $order)->with('success', 'Pembayaran dikirim dan menunggu verifikasi.');
    }
}
```

### app/Http/Controllers/OrderController.php

```php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()->with('items.product')->latest()->paginate(10);
        return view('order.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        $order->load('items.product', 'ratings');
        return view('order.show', compact('order'));
    }

    public function storeRating(Request $request, Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        abort_unless($order->status === 'completed', 403, 'Rating hanya untuk pesanan selesai.');

        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ]);

        abort_unless($order->items()->where('product_id', $data['product_id'])->exists(), 403);

        DB::transaction(function () use ($data, $order) {
            Rating::updateOrCreate(
                ['user_id' => auth()->id(), 'order_id' => $order->id, 'product_id' => $data['product_id']],
                ['rating' => $data['rating'], 'review' => $data['review'] ?? null]
            );

            $product = $order->items()->where('product_id', $data['product_id'])->first()->product;
            $product->update([
                'rating' => $product->ratings()->avg('rating') ?: 0,
                'reviews_count' => $product->ratings()->count(),
            ]);
        });

        return back()->with('success', 'Rating berhasil disimpan.');
    }
}
```

### app/Http/Controllers/Admin/OrderController.php status patch

```php
public function confirm(Order $order)
{
    if ($order->status !== 'waiting_verification') {
        return redirect()->back()->withErrors(['status' => 'Pesanan ini tidak menunggu verifikasi.']);
    }

    $order->update(['status' => 'processing']);
    optional($order->user)->notify(new PaymentConfirmed($order));

    return redirect()->back()->with('success', 'Pembayaran diverifikasi dan pesanan diproses.');
}

public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        'status' => 'required|in:pending,waiting_verification,processing,completed,cancelled',
    ]);

    $allowedTransitions = [
        'pending' => ['waiting_verification', 'cancelled'],
        'waiting_verification' => ['processing', 'cancelled'],
        'processing' => ['completed', 'cancelled'],
        'completed' => [],
        'cancelled' => [],
    ];

    if (!in_array($request->status, $allowedTransitions[$order->status] ?? [], true)) {
        return back()->withErrors(['status' => 'Transisi status tidak valid.']);
    }

    $order->update(['status' => $request->status]);
    return back()->with('success', 'Status pesanan berhasil diperbarui.');
}
```

### app/Services/RecommendationService.php

```php
namespace App\Services;

use App\Models\Product;
use App\Models\Rating;
use App\Models\Recommendation;
use Phpml\Math\Distance\Euclidean;

class RecommendationService
{
    private const K = 5;

    public function forUser(int $userId, int $limit = 6): array
    {
        $userRatings = Rating::where('user_id', $userId)->pluck('rating', 'product_id');
        $hasTransactions = \App\Models\Order::where('user_id', $userId)->exists();

        if ($userRatings->isEmpty() && ! $hasTransactions) {
            return Product::with('category')
                ->orderByDesc('purchase_count')
                ->take($limit)
                ->get()
                ->map(fn ($product) => ['product' => $product, 'score' => null])
                ->all();
        }

        $productIds = Product::pluck('id')->values();
        $activeVector = $this->vectorFor($userRatings, $productIds);

        $neighbors = Rating::where('user_id', '!=', $userId)
            ->select('user_id')
            ->distinct()
            ->pluck('user_id')
            ->map(function ($otherId) use ($productIds, $activeVector) {
                $ratings = Rating::where('user_id', $otherId)->pluck('rating', 'product_id');
                $distance = (new Euclidean())->distance($activeVector, $this->vectorFor($ratings, $productIds));
                return ['user_id' => $otherId, 'similarity' => 1 / (1 + $distance)];
            })
            ->sortByDesc('similarity')
            ->take(self::K);

        $ratedIds = $userRatings->keys();
        $scored = [];

        foreach (Product::with('category')->whereNotIn('id', $ratedIds)->get() as $product) {
            $num = 0; $den = 0;
            foreach ($neighbors as $neighbor) {
                $rating = Rating::where('user_id', $neighbor['user_id'])->where('product_id', $product->id)->value('rating');
                if ($rating !== null) {
                    $num += $neighbor['similarity'] * $rating;
                    $den += $neighbor['similarity'];
                }
            }
            if ($den > 0) $scored[] = ['product' => $product, 'score' => $num / $den];
        }

        usort($scored, fn ($a, $b) => $b['score'] <=> $a['score']);

        foreach (array_slice($scored, 0, $limit) as $row) {
            Recommendation::create([
                'user_id' => $userId,
                'product_id' => $row['product']->id,
                'score' => $row['score'],
                'algorithm' => 'user_based_cf_knn_k5',
                'generated_at' => now(),
            ]);
        }

        return array_slice($scored, 0, $limit);
    }

    private function vectorFor($ratings, $productIds): array
    {
        return $productIds->map(fn ($id) => (float) ($ratings[$id] ?? 0))->all();
    }
}
```

### app/Http/Controllers/Admin/SettingController.php

```php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get();
        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*' => ['nullable', 'string', 'max:5000'],
        ]);

        foreach ($data['settings'] as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value, 'type' => 'string', 'group' => 'system']);
        }

        return back()->with('success', 'Konfigurasi sistem berhasil diperbarui.');
    }
}
```

### app/Http/Controllers/Admin/BackupController.php

```php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use ZipArchive;

class BackupController extends Controller
{
    public function index()
    {
        return view('admin.backup.index');
    }

    public function store()
    {
        $tables = ['users', 'categories', 'products', 'orders', 'order_items', 'ratings', 'recommendations', 'settings'];
        $dir = storage_path('app/backups');
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $zipPath = $dir . '/strawberry-bakery-backup-' . now()->format('Ymd-His') . '.zip';
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE);

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $zip->addFromString($table . '.json', DB::table($table)->get()->toJson(JSON_PRETTY_PRINT));
            }
        }

        $zip->close();
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
```

### Blade route fixes

In `resources/views/admin/orders/index.blade.php`, ensure the confirm route exists and uses PATCH:

```blade
<form action="{{ route('admin.orders.confirm', $order) }}" method="POST">
    @csrf
    @method('PATCH')
    <button type="submit">Konfirmasi</button>
</form>
```

In `resources/views/kasir/orders/index.blade.php`, keep the existing route names after adding the `kasir.*` route group above.

In `resources/views/order/show.blade.php`, add a completed-order rating form inside each item row:

```blade
@if($order->status === 'completed')
    <form action="{{ route('orders.ratings.store', $order) }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ $item->product_id }}">
        <select name="rating" required>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <textarea name="review" maxlength="1000"></textarea>
        <button type="submit">Kirim Rating</button>
    </form>
@endif
```

