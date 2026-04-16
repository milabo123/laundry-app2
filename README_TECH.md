# Technical Documentation: Laundry Application

This document provides a technical overview of the Laundry application, including its architecture, core components, and implementation logic.

## 1. Architecture Overview
The application is built using the **Laravel 11.x/12.x** framework following the **Model-View-Controller (MVC)** architectural pattern.

- **Models**: Located in `app/Models/`, representing the data structure and relationships.
- **Views**: Located in `resources/views/`, using the Blade templating engine.
- **Controllers**: Located in `app/Http/Controllers/`, containing the business logic.
- **Routing**: Defined in `routes/web.php` with middleware-based access control.

## 2. Controller Breakdown

### AuthController
Handles the authentication flow:
- `showLogin()`: Displays the custom Yellow/Green/White themed login page.
- `login()`: Validates credentials and creates a session.
- `logout()`: Terminates the session.

### DashboardController
- `index()`: Aggregates statistics for the dashboard (Total Customers, Orders, Revenue) based on the user's role.

### UserController (Admin only)
- Handles CRUD operations for system users.
- Manages user levels/roles using the `id_level` attribute.

### CustomerController (Admin & Operator)
- Manages customer information (Name, Phone, Address).

### TypeOfServiceController (Admin only)
- Manages laundry service types and pricing.
- Stores prices usually represented as "per kilogram" (calculated in grams in transactions).

### TransOrderController (Admin & Operator)
The core transaction engine:
- `store()`: 
    - Generates a unique `order_code` (e.g., ORD-XXXXXXXX).
    - Calculates subtotals based on `service_price * (weight_in_grams / 1000)`.
    - Handles payment and change calculation.
- `updateStatus()`: Transitions orders between "Baru" (0) and "Sudah Diambil" (1).

### ReportController (Admin & Pimpinan)
- `index()`: Filters finished transactions within a specific date range and calculates total revenue.

## 3. Database Schema & Relationships

- **User** ➔ belongsTo ➔ **Level**
- **TransOrder** ➔ belongsTo ➔ **Customer**
- **TransOrder** ➔ hasMany ➔ **TransOrderDetail**
- **TransOrderDetail** ➔ belongsTo ➔ **TypeOfService**

The system uses **Soft Deletes** on the `User` model to prevent accidental permanent data loss.

## 4. Key Logic Implementations

### Price Calculation
The laundry system calculates prices based on weight in grams. 
```php
$subtotal = (int) round($service->price * ($svc['qty'] / 1000));
```
This ensures that customers are billed accurately even for smaller weights.

### Access Control
Access is restricted via the `role` middleware:
- `admin`: Full access to everything.
- `operator`: Access to transactions and customer management.
- `pimpinan`: Access to dashboard and revenue reports.
