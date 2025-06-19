# 🚀 Laravel 12 + Inertia.js + Vue 3 + Naive UI Admin Panel

A fully featured multi-tenant admin dashboard built with:

- Laravel 12
- Inertia.js
- Vue 3 (Composition API)
- Naive UI
- Spatie Laravel Permission
- Multi-tenant support (Stancl tenancy)
- Dynamic Menu Management
- Role & Permission based access control
- Users, Roles, Permissions, Menus CRUD with modals

---

## 📦 Features

- Multi-auth tenants with isolated databases
- Role & permission support (Spatie)
- Responsive sidebar with permission-based visibility
- Fully dynamic, database-driven sidebar menus
- Modern UI with Naive UI components
- Reusable modal-based CRUD operations
- Clean and maintainable architecture

---

## ⚙️ Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18.x
- NPM or Yarn
- MySQL or PostgreSQL
- Laravel Herd / Valet / Homestead / Sail / Nginx / Apache

---

## 🧑‍💻 Setup Instructions (Local or Server)

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/your-project.git
cd your-project
2. Install PHP Dependencies

composer install

3. Copy .env and Generate Key

cp .env.example .env
php artisan key:generate

4. Configure .env

APP_NAME=MyAdmin
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_DATABASE=myadmin
DB_USERNAME=root
DB_PASSWORD=

5. Install Node Modules & Build Frontend

npm install
npm run dev     # for development
# or
npm run build   # for production

6. Run Migrations & Seeders

php artisan migrate --seed

🧪 Example Admin Login
Email: superadmin@example.com

Password: 12345678

Use this to explore user/role/permission management.

📄 License
This project is open-sourced software licensed under the MIT license.

