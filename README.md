<div align="center">

```
███████╗██╗  ██╗    ███╗   ███╗██╗███╗   ██╗██╗
╚════██║██║  ██║    ████╗ ████║██║████╗  ██║██║
    ██╔╝███████║    ██╔████╔██║██║██╔██╗ ██║██║
   ██╔╝ ██╔══██║    ██║╚██╔╝██║██║██║╚██╗██║██║
   ██║  ██║  ██║    ██║ ╚═╝ ██║██║██║ ╚████║██║
   ╚═╝  ╚═╝  ╚═╝    ╚═╝     ╚═╝╚═╝╚═╝  ╚═══╝╚═╝
```

# ⚡ ZH Mini-Backend Engine

**A high-performance, ultra-lightweight PHP Micro-Framework**  
*Built from scratch. Zero dependencies. Absolute control.*

[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-00D9FF?style=flat-square)](LICENSE)
[![Architecture](https://img.shields.io/badge/Architecture-MVC%20%2B%20PSR--4-00FF9F?style=flat-square)](composer.json)
[![RAM](https://img.shields.io/badge/RAM%20Usage-%3C5%25%20vs%20Laravel-FF6B35?style=flat-square)](#performance)
[![Hosting](https://img.shields.io/badge/Shared%20Hosting-Ready-success?style=flat-square)](#getting-started)

<br/>

> **"Architecture Over Size"** — Pure PHP engineered for maximum speed on minimal resources.  
> Ideal for **PWA Backends**, **REST APIs**, and **Shared Hosting** environments with strict CPU/RAM limits.

</div>

---

## 🎯 Why ZH Mini-Backend?

Most PHP frameworks carry **massive overhead** you never use. ZH Mini-Backend strips everything down to the **structural essentials** — a clean Router, a secure DB Driver, Middleware layers, and a custom CLI — giving you a **production-grade foundation** with a fraction of the resource cost.

| Metric | ZH Mini-Backend | Laravel |
|--------|:--------------:|:-------:|
| RAM on Boot | **~2 MB** | ~40–60 MB |
| Files Loaded | **< 20** | 400+ |
| Dependencies | **0** (pure PHP) | 30+ packages |
| Shared Hosting | ✅ Optimized | ⚠️ Heavy |
| PSR-4 Autoloading | ✅ | ✅ |

---

## 🚀 Core Features

### ⚙️ Custom CLI Engine (`zh.php`)
Manage your entire application lifecycle from the terminal — create databases, run migrations, scaffold modules — without touching a GUI.

### 🛣️ Dynamic Routing System
Clean, expressive route definitions mapped directly to MVC Controllers. Supports `GET`, `POST`, and Middleware chaining per route.

### 🔒 Layered Middleware
Plug-and-play security filters: **Bearer Token Auth**, **Global CORS**, and custom request validation — applied at the route level with zero coupling.

### 🗄️ Secure PDO Singleton Driver
A single-connection database lifecycle with built-in **SQL Injection protection**. No query goes raw.

### 🌐 Environment Manager
Built-in `.env` parser — no external packages. Keeps your API keys (Edfapay, Fawaterak, Naqel, Fastlo) away from version control.

### 📱 PWA-Ready
Native support for `manifest.json` and Service Worker patterns, served directly from the `public/` front controller.

---

## 📂 Directory Structure

```
mini-backend/
│
├── app/
│   ├── Controllers/        # Business logic — one class per resource
│   ├── Middleware/         # Auth, CORS, and custom filter layers
│   └── Models/             # Data structures and DB interaction
│
├── config/                 # Centralized PHP environment config
│
├── core/                   # 🔧 The Engine Room
│   ├── Router.php          #   Route registration & dispatching
│   ├── Request.php         #   HTTP input abstraction
│   ├── Response.php        #   JSON output helpers
│   ├── Database.php        #   PDO Singleton wrapper
│   └── DotEnv.php          #   .env file parser
│
├── database/
│   └── migrations/         # Pure .sql files — executed sequentially by CLI
│
├── public/                 # ⚠️ Only folder exposed to the web
│   ├── index.php           #   Front Controller (single entry point)
│   └── manifest.json       #   PWA manifest
│
├── routes/
│   └── api.php             # All API endpoint definitions
│
├── .env.example            # Secret keys boilerplate
├── composer.json           # PSR-4 autoloading registry
└── zh.php                  # 🖥️ Custom CLI management tool
```

---

## 🛠️ Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/AbdalrhmanAbdoAlhade/mini-backend.git your-app-name
cd your-app-name
```

### 2. Initialize PSR-4 Autoloading

```bash
composer install
```

### 3. Configure Your Environment

```bash
cp .env.example .env
```

Open `.env` and fill in your credentials:

```ini
APP_NAME="ZH Mini Backend"
APP_ENV=local

DB_HOST=localhost
DB_NAME=your_database_name
DB_USER=root
DB_PASS=""
```

### 4. Run CLI Setup Commands

```bash
# Create the database defined in your .env
php zh.php db:create

# Execute all sequential SQL migration files
php zh.php db:migrate
```

### 5. Start Development Server

```bash
php -S localhost:8000 -t public
```

Your API is now live at `http://localhost:8000` 🎉

---

## 🔒 Routing & Middleware Example

```php
<?php
// routes/api.php

use Core\Router;
use App\Controllers\ProductController;
use App\Middleware\AuthMiddleware;

// ── Public Endpoints ──────────────────────────────────────
Router::get('/api/v1/products', [ProductController::class, 'index']);

// ── Protected Endpoints (Bearer Token required) ───────────
Router::post('/api/v1/products', [ProductController::class, 'store'])
      ->middleware(AuthMiddleware::class);
```

---

## 🧩 Controller Anatomy

```php
<?php
// app/Controllers/ProductController.php

namespace App\Controllers;

use Core\Request;
use Core\Response;
use App\Models\Product;

class ProductController
{
    public function index(Request $request): void
    {
        $products = Product::all();
        Response::json($products);
    }

    public function store(Request $request): void
    {
        $data = $request->body();
        $product = Product::create($data);
        Response::json($product, 201);
    }
}
```

---

## 📈 Scalability Roadmap

The framework is built on **decoupled, swappable modules**. Future upgrades are isolated — no risk of breaking existing functionality.

| Module | Status | Description |
|--------|:------:|-------------|
| Core Engine | ✅ **Stable** | Router, DB, Middleware, CLI |
| PWA Support | ✅ **Stable** | manifest.json + Service Workers |
| **Validation Engine** | 🔜 Planned | Request input validation rules |
| **Query Builder** | 🔜 Planned | Fluent SQL abstraction layer |
| **PSR-3 Logger** | 🔜 Planned | File-system debug logging for shared hosts |
| **Rate Limiter** | 🔜 Planned | IP-based request throttling middleware |
| **JWT Auth Driver** | 🔜 Planned | Stateless token generation & verification |

---

## 🤝 Contributing

Want to add a new module? The process is clean:

1. Create your class under the appropriate namespace (`Core\`, `App\Middleware\`, etc.)
2. Register it in `composer.json` under `autoload.psr-4`
3. Wire it into `routes/api.php` or `core/` as needed
4. Open a PR with a clear description of what the module does and why

No black boxes. Every line of this framework is readable and intentional.

---

## 📄 License

Distributed under the **MIT License** — use it, fork it, ship it.  
See [`LICENSE`](LICENSE) for full terms.

---

<div align="center">

**Built with precision by [ZH Innovation](https://github.com/AbdalrhmanAbdoAlhade) © 2026**

*"Write less. Control more. Ship faster."*

</div>