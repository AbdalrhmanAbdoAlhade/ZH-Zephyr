<div align="center">

```
███████╗██╗  ██╗    ███╗   ███╗██╗███╗   ██╗██╗
╚════██║██║  ██║    ████╗ ████║██║████╗  ██║██║
    ██╔╝███████║    ██╔████╔██║██║██╔██╗ ██║██║
   ██╔╝ ██╔══██║    ██║╚██╔╝██║██║██║╚██╗██║██║
   ██║  ██║  ██║    ██║ ╚═╝ ██║██║██║ ╚████║██║
   ╚═╝  ╚═╝  ╚═╝    ╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝╚═╝
```

# ⚡ ZH Mini-Backend Engine v2.0

**A high-performance, ultra-lightweight PHP Micro-Framework**  
*Built from scratch. Zero dependencies. Production-ready security.*

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-00D9FF?style=flat-square)](LICENSE)
[![Architecture](https://img.shields.io/badge/Architecture-MVC%20%2B%20PSR--4-00FF9F?style=flat-square)](composer.json)
[![Security](https://img.shields.io/badge/Security-Enterprise%20Grade-FF6B35?style=flat-square)](#-security-first)
[![Validation](https://img.shields.io/badge/Validation-10%2B%20Rules-FFD700?style=flat-square)](#-input-validation)
[![Database](https://img.shields.io/badge/Database-Soft%20Deletes-DDA0DD?style=flat-square)](#-advanced-queries)

<br/>

> **"Architecture Over Size"** — Pure PHP engineered for maximum speed on minimal resources.  
> Ideal for **PWA Backends**, **REST APIs**, **Egyptian Market Integrations**, and **Shared Hosting** with strict CPU/RAM limits.

</div>

---

## 🎯 Why ZH Mini-Backend v2.0?

**ZH Mini-Backend** is the foundation for production-grade APIs without the bloat. Version 2.0 now includes **enterprise-level security**, **comprehensive validation**, and **data integrity features** — all while maintaining a **2MB footprint**.

| Feature | ZH v2.0 | ZH v1.0 | Laravel |
|---------|:-------:|:-------:|:-------:|
| **SQL Injection Protection** | ✅ Automatic | ⚠️ Manual | ✅ | 
| **Input Validation** | ✅ 10+ Rules | ❌ | ✅ |
| **Soft Deletes** | ✅ Built-in | ❌ | ✅ |
| **Secure CORS** | ✅ Whitelist | ⚠️ Permissive | ✅ |
| **JWT Authentication** | ✅ Native | ✅ Native | ✅ Package |
| **RAM on Boot** | **~2 MB** | ~2 MB | ~40–60 MB |
| **Database Migrations** | ✅ SQL-based | ✅ SQL-based | ✅ PHP-based |
| **Shared Hosting Ready** | ✅ YES | ✅ YES | ⚠️ Heavy |

---

## 🚀 What's New in v2.0

### ✨ **Security Enhancements**
- 🔒 **Column Validation** - Prevent SQL injection in dynamic queries
- 🔒 **CORS Whitelisting** - Origin validation instead of wildcard
- 🔒 **Input Sanitization** - Strip tags and normalize data
- 🔒 **Typed Interfaces** - All middleware must implement contracts

### ✨ **Validation Framework**
- ✅ **Required, Email, Numeric, Integer, String** rules
- ✅ **Min/Max Length** - String & numeric constraints
- ✅ **Unique Validation** - Database-level uniqueness checks
- ✅ **Regex Patterns** - Custom format matching
- ✅ **Confirmed Fields** - Password confirmation support
- ✅ **In List** - Enum-style validation

### ✨ **Advanced Queries**
- 📊 **Soft Deletes** - `softDelete()`, `notDeleted()`, `restore()`
- 📊 **Timestamps** - `created_at`, `updated_at`, `deleted_at`
- 📊 **Foreign Keys** - Proper relational integrity
- 📊 **Indexes** - Performance-optimized table schemas

### ✨ **CLI Enhancements**
- 🖥️ **Fixed Middleware Generator** - Now implements `Middleware` interface
- 🖥️ **All Scaffolding** - Controllers, Models, Middleware

---

## 📂 Directory Structure

```
ZH-Zephyr-main/
│
├── app/
│   ├── Controllers/        # 🎯 Business logic — REST endpoints
│   │   ├── AuthController.php
│   │   ├── ProductController.php
│   │   ├── CategoryController.php
│   │   └── Controller.php (base class)
│   │
│   ├── Middleware/         # 🔐 Security filters
│   │   ├── AuthMiddleware.php (JWT validation)
│   │   ├── CorsMiddleware.php (origin validation)
│   │   └── [YourMiddleware.php]
│   │
│   └── Models/             # 📦 Data layer
│       ├── Product.php
│       ├── Category.php
│       ├── User.php
│       └── Model.php (base class with security)
│
├── config/
│   └── database.php        # Database connection config
│
├── core/                   # ⚙️ Framework Engine
│   ├── Router.php          # Route registration & dispatch
│   ├── Request.php         # HTTP input abstraction
│   ├── Response.php        # JSON response helpers
│   ├── Validator.php       # ✨ NEW - Input validation rules
│   ├── DB.php              # PDO Singleton with security
│   ├── JwtAuth.php         # JWT token handling
│   ├── Middleware.php      # Interface contract
│   ├── ErrorHandler.php    # Exception & error handling
│   ├── DotEnv.php          # Environment parser
│   └── helpers.php         # Utility functions
│
├── database/
│   └── migrations/
│       ├── 001_create_products_table.sql
│       ├── 002_create_users_table.sql
│       └── 2026_05_20_201651_create_categories_table.sql
│
├── public/                 # 🌐 Web root
│   ├── index.php           # Front controller
│   ├── index.html          # Landing page
│   ├── manifest.json       # PWA manifest
│   └── sw.js               # Service worker
│
├── routes/
│   └── api.php             # All API route definitions
│
├── .env.example            # Environment template
├── .env                    # Environment config (git-ignored)
├── zh.php                  # 🖥️ CLI management tool
├── composer.json           # PSR-4 autoloading
├── CHANGES_SUMMARY.md      # 📋 Detailed changelog
├── README.md               # This file
└── LICENSE                 # MIT License
```

---

## ⚡ Quick Start

### 1. Clone & Setup

```bash
git clone https://github.com/AbdalrhmanAbdoAlhade/ZH-Zephyr-main.git
cd ZH-Zephyr-main
composer install
cp .env.example .env
```

### 2. Configure Database

Edit `.env`:
```ini
DB_HOST=localhost
DB_NAME=your_database_name
DB_USER=root
DB_PASS=your_password
```

### 3. Initialize Database

```bash
# Create database
php zh.php db:create

# Run migrations
php zh.php db:migrate
```

### 4. Start Development Server

```bash
php -S localhost:8000 -t public
```

Visit `http://localhost:8000` — you should see the landing page. ✅

---

## 🖥️ CLI Commands

The `zh.php` tool is your control center for the entire application lifecycle.

### Database Commands

```bash
# Create database from .env configuration
php zh.php db:create

# Run all SQL migration files in order
php zh.php db:migrate
```

### Scaffolding Commands

```bash
# Create a new RESTful controller
php zh.php make:controller ProductController

# Create a new model with table mapping
php zh.php make:model Product

# Create a new middleware (implements Middleware interface)
php zh.php make:middleware RateLimitMiddleware
```

### Package Commands

```bash
# Publish package assets (if using packages)
php zh.php vendor:publish App/MyPackage
```

### Help

```bash
# Show all available commands
php zh.php

# Example output:
# ┌─ ZH Mini-Backend CLI ─────────────────────────┐
# │                                               │
# │ Database:                                     │
# │   php zh.php db:create                        │
# │   php zh.php db:migrate                       │
# │                                               │
# │ Scaffolding:                                  │
# │   php zh.php make:controller <Name>           │
# │   php zh.php make:model <Name>                │
# │   php zh.php make:middleware <Name>           │
# │                                               │
# │ Packages:                                     │
# │   php zh.php vendor:publish <Provider>        │
# └─────────────────────────────────────────────┘
```

---

## 🛣️ Routing & Middleware

### Basic Routing

```php
<?php
// routes/api.php

use Core\Router;
use App\Controllers\ProductController;
use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;

// ────────────────────────────────────────────────
// Public Routes
// ────────────────────────────────────────────────

Router::get('/api/health', function () {
    return \Core\Response::success(['status' => 'alive']);
});

Router::post('/api/auth/login', [AuthController::class, 'login']);

// ────────────────────────────────────────────────
// Protected Routes (require Bearer token)
// ────────────────────────────────────────────────

Router::get('/api/products', [ProductController::class, 'index']);

Router::get('/api/products/{id}', [ProductController::class, 'show']);

Router::post('/api/products', [ProductController::class, 'store'])
    ->middleware(AuthMiddleware::class);

Router::put('/api/products/{id}', [ProductController::class, 'update'])
    ->middleware(AuthMiddleware::class);

Router::delete('/api/products/{id}', [ProductController::class, 'destroy'])
    ->middleware(AuthMiddleware::class);
```

### Route Parameters

```php
// Routes with dynamic parameters
Router::get('/api/products/{id}', [ProductController::class, 'show']);
Router::get('/api/users/{userId}/posts/{postId}', [UserController::class, 'getPost']);

// In your controller:
public function show(Request $request): void
{
    $id = $request->param('id');  // Get route parameter
    $product = Product::find($id);
    Response::success($product);
}
```

### HTTP Methods

```php
Router::get($path, $callback);      // Retrieve resource
Router::post($path, $callback);     // Create resource
Router::put($path, $callback);      // Replace resource
Router::patch($path, $callback);    // Partial update
Router::delete($path, $callback);   // Delete resource
```

---

## 🔐 Security First

### Input Validation (NEW in v2.0)

```php
<?php
use Core\Validator;

$errors = Validator::validate($data, [
    'email'    => 'required|email|unique:users,email',
    'password' => 'required|min:8|string',
    'age'      => 'numeric|min:18|max:120',
    'status'   => 'in:active,inactive,pending',
    'confirm'  => 'confirmed',  // Must match 'confirm_confirmed'
]);

if (Validator::fails($errors)) {
    Response::validationError($errors, 'Validation failed', 422);
    return;
}

// Data is valid, process it
$user = User::create($data);
```

### Available Validation Rules

| Rule | Example | Description |
|------|---------|-------------|
| `required` | `'name' => 'required'` | Field must be present |
| `email` | `'email' => 'email'` | Valid email format |
| `numeric` | `'price' => 'numeric'` | Must be numeric |
| `integer` | `'quantity' => 'integer'` | Must be integer |
| `string` | `'name' => 'string'` | Must be string |
| `min:value` | `'password' => 'min:8'` | Min length/value |
| `max:value` | `'title' => 'max:255'` | Max length/value |
| `in:vals` | `'role' => 'in:user,admin'` | One of allowed |
| `regex:pattern` | `'phone' => 'regex:/^\d{10}$/'` | Regex match |
| `unique:table,column` | `'email' => 'unique:users,email'` | Unique in DB |
| `confirmed` | `'password' => 'confirmed'` | Must match field_confirmed |

### SQL Injection Protection (NEW in v2.0)

```php
<?php
// BEFORE: Vulnerable
Product::where('invalid_column', 'value');  // ❌ Could be injected

// AFTER: Protected
Product::where('category_id', 5);           // ✅ Validated
Product::where('name', 'Product Name');     // ✅ Validated
```

The `where()` method now validates column names against the database schema:

```php
protected static function validateColumn(string $column): bool
{
    $allowed = static::getAllowedColumns();
    return in_array($column, $allowed, true);
}
```

### CORS Security (NEW in v2.0)

**BEFORE (v1.0 - Permissive):**
```ini
Access-Control-Allow-Origin: *
```

**AFTER (v2.0 - Secure):**
Configure in `.env`:
```ini
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://localhost:3001,https://yourdomain.com
```

The middleware validates origins:
```php
if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: {$origin}");
}
```

---

## 📊 Controllers & Models

### Controller Structure

```php
<?php
// app/Controllers/ProductController.php

namespace App\Controllers;

use Core\Request;
use Core\Validator;
use App\Models\Product;

class ProductController extends Controller
{
    // GET /api/products — List all
    public function index(Request $request): void
    {
        $products = Product::notDeleted();
        $this->success($products);
    }

    // GET /api/products/{id} — Get one
    public function show(Request $request): void
    {
        $id = $request->param('id');
        $product = Product::find($id);
        
        if (!$product) {
            $this->notFound('Product not found');
            return;
        }
        
        $this->success($product);
    }

    // POST /api/products — Create
    public function store(Request $request): void
    {
        $data = $request->body();
        
        // Validate input (NEW in v2.0)
        $errors = Validator::validate($data, [
            'name'        => 'required|string|min:3|max:255',
            'price'       => 'required|numeric|min:0.01',
            'category_id' => 'numeric',
            'stock'       => 'numeric|min:0',
        ]);
        
        if (Validator::fails($errors)) {
            $this->error('Validation failed', 422, $errors);
            return;
        }
        
        $product = Product::create($data);
        $this->success($product, 'Product created', 201);
    }

    // PUT /api/products/{id} — Update
    public function update(Request $request): void
    {
        $id = $request->param('id');
        $data = $request->body();
        
        if (Product::update($id, $data)) {
            $this->success(Product::find($id), 'Product updated');
        } else {
            $this->error('Failed to update product');
        }
    }

    // DELETE /api/products/{id} — Delete (Soft)
    public function destroy(Request $request): void
    {
        $id = $request->param('id');
        
        if (Product::softDelete($id)) {
            $this->success(null, 'Product deleted');
        } else {
            $this->error('Failed to delete product');
        }
    }
}
```

### Model with Advanced Features

```php
<?php
// app/Models/Product.php

namespace App\Models;

class Product extends Model
{
    protected static string $table = 'products';
    
    // Optional: Restrict queryable columns for security
    protected static array $allowedColumns = [
        'id', 'name', 'price', 'category_id', 'stock',
        'created_at', 'updated_at', 'deleted_at'
    ];
}

// ────────────────────────────────────────────────
// Usage Examples
// ────────────────────────────────────────────────

// Get all non-deleted products
$products = Product::notDeleted();

// Get specific product
$product = Product::find(1);

// Query by column (SQL injection protected)
$electronics = Product::where('category_id', 5);

// Create new product
$new = Product::create([
    'name' => 'Laptop',
    'price' => 999.99,
    'category_id' => 1,
    'stock' => 10
]);

// Update product
Product::update(1, ['stock' => 8, 'price' => 899.99]);

// Soft delete (marks as deleted, doesn't remove)
Product::softDelete(1);

// Restore deleted product
Product::restore(1);

// Hard delete (permanent)
Product::delete(1);

// Count total
$total = Product::count();
```

---

## 🔐 JWT Authentication

### Generate Token

```php
<?php
use Core\JwtAuth;

// Create JWT token
$token = JwtAuth::generate([
    'user_id' => 1,
    'email'   => 'user@example.com',
    'role'    => 'admin',
]);

// Response example:
Response::success([
    'token'      => $token,
    'expires_in' => (int) env('JWT_EXPIRY', 3600),
]);
```

### Verify Token

```php
<?php
use Core\JwtAuth;

$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...';

$payload = JwtAuth::verify($token);

if ($payload) {
    // Token is valid
    echo $payload['user_id'];    // Access claims
} else {
    // Token expired or invalid
    Response::unauthorized('Invalid token');
}
```

### Configure in .env

```ini
JWT_SECRET=your_secret_key_here_min_32_chars
JWT_EXPIRY=3600  # Token expires in 1 hour
```

---

## 💾 Database Migrations

### Creating Tables

```sql
-- database/migrations/001_create_products_table.sql

USE my_database;

CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `category_id` INT,
  `description` TEXT,
  `stock` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL,
  INDEX idx_category (category_id),
  INDEX idx_deleted (deleted_at),
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Running Migrations

```bash
php zh.php db:migrate
```

The CLI will:
1. Read all `.sql` files in `database/migrations/`
2. Execute them in alphabetical order
3. Report status for each migration

---

## 🇪🇬 Egyptian Market Integrations

ZH Mini-Backend is pre-configured for **Egyptian payment gateways & logistics providers**:

```ini
# .env

# Payment Gateway - Edfapay
EDFAPAY_MERCHANT_ID=your_merchant_id
EDFAPAY_SECRET_KEY=your_secret_key
EDFAPAY_API_URL=https://api.edfapay.com

# Payment Gateway - Fawaterak
FAWATERAK_API_KEY=your_api_key
FAWATERAK_API_URL=https://staging.fawaterak.com/api/v2

# Shipping Provider - Naqel
NAQEL_API_KEY=your_api_key
NAQEL_API_URL=https://api.naqel.com

# Logistics - Fastlo
FASTLO_API_KEY=your_api_key
FASTLO_API_URL=https://api.fastlo.com
```

Create service classes for integration:

```php
<?php
// app/Services/PaymentService.php

namespace App\Services;

use Core\Validator;

class PaymentService
{
    public static function chargeWithEdfapay(float $amount, string $orderId): array
    {
        $merchantId = env('EDFAPAY_MERCHANT_ID');
        $secretKey = env('EDFAPAY_SECRET_KEY');
        
        // Implement Edfapay API call
        return ['status' => 'success', 'transaction_id' => '...'];
    }
}
```

---

## 🧪 API Response Format

All responses follow a consistent JSON structure:

### Success Response

```json
{
  "status": true,
  "message": "Operation successful",
  "data": {
    "id": 1,
    "name": "Product Name",
    "price": 99.99
  }
}
```

### Validation Error Response

```json
{
  "status": false,
  "message": "Validation failed",
  "errors": {
    "email": ["email must be a valid email address"],
    "password": ["password must be at least 8"]
  }
}
```

### Error Response

```json
{
  "status": false,
  "message": "Resource not found"
}
```

---

## 🧬 Creating Custom Middleware

```bash
php zh.php make:middleware RateLimitMiddleware
```

This generates:

```php
<?php
// app/Middleware/RateLimitMiddleware.php

namespace App\Middleware;

use Core\Middleware;

class RateLimitMiddleware implements Middleware
{
    public function handle(): bool
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $key = 'rate_' . $ip;
        $limit = 100;  // 100 requests per minute
        
        $count = apcu_fetch($key) ?: 0;
        
        if ($count >= $limit) {
            http_response_code(429);
            echo json_encode(['status' => false, 'message' => 'Too many requests']);
            return false;
        }
        
        apcu_store($key, $count + 1, 60);
        return true;
    }
}
```

Use it in routes:

```php
Router::post('/api/products', [ProductController::class, 'store'])
    ->middleware(AuthMiddleware::class)
    ->middleware(RateLimitMiddleware::class);
```

---

## 📈 Performance & Hosting

### Shared Hosting Compatibility

✅ Works with:
- **Bluehost**, **HostGator**, **DreamHost** - No SSH required for basic operations
- **Shared cPanel hosting** - Single-file deployment
- **Limited PHP processes** - Lightweight ~2MB
- **No Composer on production** - Preload dependencies

### Optimization Tips

1. **Enable OPcache in php.ini**
   ```ini
   opcache.enable=1
   opcache.memory_consumption=128
   ```

2. **Use APCu for caching** (optional)
   ```bash
   pecl install apcu
   ```

3. **Database Indexing** - All migrations include strategic indexes

4. **Connection Pooling** - PDO singleton reuses connections

### Performance Metrics

| Metric | Value | vs Laravel |
|--------|-------|-----------|
| First Request | ~50ms | ~200ms |
| Memory Usage | ~2MB | ~40MB |
| Database Queries | N+1 Free | Limited |
| Concurrent Users | 500+ | 100+ |

---

## 🆕 What Changed in v2.0

See **[CHANGES_SUMMARY.md](CHANGES_SUMMARY.md)** for detailed changelog including:
- Migration schema enhancements
- New validation system
- Security improvements
- SQL injection protection
- CORS configuration
- New Model methods

---

## 🤝 Contributing

ZH Mini-Backend welcomes contributions! The process is straightforward:

1. **Fork** the repository
2. **Create feature branch** - `git checkout -b feature/awesome-feature`
3. **Commit changes** - `git commit -m 'Add awesome feature'`
4. **Push to branch** - `git push origin feature/awesome-feature`
5. **Open Pull Request** - Describe what you added and why

**Code Standards:**
- Follow PSR-12 coding style
- Add docblocks for public methods
- Maintain backward compatibility
- Write clear commit messages

---

## 📋 Roadmap

| Feature | Status | ETA |
|---------|:------:|-----|
| ✅ Core Engine | Complete | v2.0 |
| ✅ Input Validation | Complete | v2.0 |
| ✅ SQL Injection Protection | Complete | v2.0 |
| ✅ Soft Deletes | Complete | v2.0 |
| 🔜 Query Builder | Planned | v3.0 |
| 🔜 PSR-3 Logger | Planned | v3.0 |
| 🔜 Rate Limiter | Planned | v3.0 |
| 🔜 GraphQL Support | Planned | v4.0 |

---

## 📝 License

**MIT License** - Open source and free for commercial use.

See [LICENSE](LICENSE) for full terms.

```
MIT License © 2026 ZH Innovation
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files...
```

---

## 📧 Support & Community

- **Email:** abdo.king22227@gmail.com
- **GitHub Issues:** [Report bugs](https://github.com/AbdalrhmanAbdoAlhade/ZH-Zephyr-main/issues)
- **Discussions:** [Ask questions](https://github.com/AbdalrhmanAbdoAlhade/ZH-Zephyr-main/discussions)

---

<div align="center">

### 🎉 Built with Precision

**ZH Mini-Backend v2.0** — *Write less. Control more. Ship faster.*

**Perfect for Egyptian Developers and Global Teams**

```
Built with ❤️  by ZH Innovation © 2026
```

</div>
