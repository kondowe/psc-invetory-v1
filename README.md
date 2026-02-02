# Inventory Management System (IMS)

A comprehensive inventory management system with dynamic workflow engine, fuel coupon management, and role-based access control.

## Features

- **5 User Roles**: Requester, Department Supervisor, Administration Manager, General Administration Manager, Stores Officer
- **Dynamic Workflow Engine**: Configurable approval workflows with system-mandatory steps
- **GRV System**: Mandatory entry point for all inventory via Goods Received Vouchers
- **Fuel Coupon Management**: Individual coupon tracking with serial numbers and expiry dates
- **Request Management**: Item and fuel requests with multi-step approvals
- **Stock Management**: Real-time inventory tracking with reservations and issuance
- **Audit Trail**: Immutable logging with soft deletes
- **Notifications**: In-app and email notifications (configurable)
- **Reporting**: Comprehensive reports and dashboards

## Technology Stack

- **Backend**: PHP 8.0+ (OOP/MVC)
- **Database**: MySQL 8.0+
- **Frontend**: HTML5, Tailwind CSS (CDN)
- **Server**: Apache (WAMP)

## Installation

### Prerequisites

- WAMP Server (Windows) or XAMPP
- PHP 8.0 or higher
- MySQL 8.0 or higher
- Composer (optional, for PHPMailer)

### Step 1: Database Setup

1. Open phpMyAdmin or MySQL command line
2. Run the schema file to create the database:

```bash
mysql -u root < database/schema.sql
```

Or manually:
- Create database: `CREATE DATABASE inventory_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`
- Import: `database/schema.sql`

3. Seed the database with initial data:

```bash
mysql -u root inventory_system < database/seeds/01_roles.sql
mysql -u root inventory_system < database/seeds/02_permissions.sql
mysql -u root inventory_system < database/seeds/03_departments.sql
mysql -u root inventory_system < database/seeds/04_users.sql
mysql -u root inventory_system < database/seeds/05_inventory_master.sql
mysql -u root inventory_system < database/seeds/06_workflows.sql
mysql -u root inventory_system < database/seeds/07_number_sequences.sql
mysql -u root inventory_system < database/seeds/08_system_config.sql
```

Or run all seeds at once:

```bash
cd database/seeds
for file in *.sql; do mysql -u root inventory_system < "$file"; done
```

### Step 2: Configure Application

1. Copy `.env.example` to `.env`:

```bash
copy .env.example .env
```

2. Update `.env` with your settings:

```env
DB_HOST=localhost
DB_DATABASE=inventory_system
DB_USERNAME=root
DB_PASSWORD=

MAIL_ENABLED=false
```

### Step 3: Install Dependencies (Optional)

If you want email notifications:

```bash
composer install
```

This will install PHPMailer for SMTP email support.

### Step 4: Configure Apache

1. Ensure `mod_rewrite` is enabled in Apache
2. Ensure `.htaccess` files are allowed (AllowOverride All)
3. Your WAMP `httpd.conf` should have:

```apache
<Directory "c:/wamp64/www/">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

4. Restart Apache

### Step 5: Access the System

Open your browser and navigate to:

```
http://localhost/int/public/
```

## Default Login Credentials

After seeding the database, you can login with:

| Role | Username | Password |
|------|----------|----------|
| General Admin | admin | Admin@123 |
| Admin Manager | admin_mgr | Admin@123 |
| Stores Officer | stores | Admin@123 |
| IT Supervisor | supervisor_it | Admin@123 |
| Operations Supervisor | supervisor_ops | Admin@123 |
| Requester | requester1 | Admin@123 |
| Requester | requester2 | Admin@123 |

**Important**: Change these passwords immediately after first login!

## Directory Structure

```
int/
├── config/              # Configuration files
├── core/                # Core classes (Database, Auth, RBAC, etc.)
├── models/              # Data models
├── controllers/         # Controllers
├── services/            # Business logic services
├── views/               # View templates
│   ├── layouts/         # Layout files (header, sidebar, footer)
│   ├── auth/            # Authentication views
│   ├── dashboard/       # Role-specific dashboards
│   └── ...
├── public/              # Public web root
│   ├── index.php        # Front controller
│   ├── .htaccess        # Apache config
│   └── assets/          # CSS, JS, images
├── database/            # Database files
│   ├── schema.sql       # Database schema
│   └── seeds/           # Seed data
├── logs/                # Application logs
└── uploads/             # User uploads (future)
```

## Phase 1 Complete ✅

The following components are now functional:

- ✅ Database schema (30+ tables)
- ✅ Database seeds (roles, users, permissions, departments, inventory, workflows)
- ✅ Core classes (Database, Session, Auth, RBAC, Security, Validator, Response, Logger)
- ✅ Base models (User, Role, Permission, Department)
- ✅ Authentication system (login/logout)
- ✅ Role-based dashboards (placeholder views)
- ✅ Front controller with routing
- ✅ Layouts (header, sidebar, footer)
- ✅ Public assets (CSS, JavaScript)

## Next Steps

To continue development, the following modules need to be implemented:

1. **Inventory Module**: Item management, stock levels, categories
2. **GRV Module**: Goods received vouchers with approval
3. **Workflow Engine**: Dynamic workflow processing (CRITICAL)
4. **Request Module**: Create and manage item/fuel requests
5. **Issuance Module**: Issue vouchers and stock deduction
6. **Fuel Coupon Module**: Coupon tracking and management
7. **Notification Module**: Email and in-app notifications
8. **Reporting Module**: Stock reports, request summaries, fuel consumption

## Workflow System Overview

The system uses a **dynamic, configurable workflow engine**:

### System-Mandatory Steps (Cannot be removed)

All requests MUST go through:

1. **Administration Manager** → Approval
2. **General Administration Manager** → Final approval
3. **Stores Officer** → Release instruction

### Department-Configurable Steps

Department Supervisors can add steps **before** the mandatory steps:

```
Request Submitted
  ↓
[Dept Supervisor] ← Configurable
  ↓
[Dept Manager] ← Configurable (optional)
  ↓
Administration Manager ← MANDATORY
  ↓
General Admin Manager ← MANDATORY
  ↓
Stores Officer ← MANDATORY
  ↓
Items Issued
```

## Security Features

- ✅ Prepared statements (SQL injection prevention)
- ✅ CSRF token protection
- ✅ XSS prevention (output escaping)
- ✅ Secure session management
- ✅ Password hashing (bcrypt, cost 12)
- ✅ Role-based access control (RBAC)
- ✅ Input validation and sanitization
- ✅ Security headers (.htaccess)
- ✅ Soft delete (no hard deletes)
- ✅ Audit logging

## Troubleshooting

### Database Connection Error

Check `config/database.php` settings match your MySQL configuration.

### Page Not Found (404)

Ensure `mod_rewrite` is enabled in Apache and `.htaccess` is working.

### CSS/JS Not Loading

Check that the `public/assets/` folder has proper permissions and files exist.

### Session Issues

Check that PHP session directory is writable. Clear browser cookies if needed.

## Support

For issues or questions, check:

- Application logs: `logs/app.log`, `logs/error.log`
- PHP error logs: Check WAMP error logs
- Database: Verify all seeds ran successfully

## License

Proprietary - All rights reserved

## Version

1.0.0 - Phase 1 Complete
