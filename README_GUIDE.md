# User Manual: Laundry Management System

Welcome to the Laundry Management System. This guide will walk you through the core workflows of the application from a user's perspective.

## 1. User Roles & Permissions

The system is designed with three distinct roles:
- **Admin**: Has full control over the system, including managing users and service types.
- **Operator**: Focuses on daily operations—managing customers and processing orders.
- **Pimpinan**: Focuses on monitoring—viewing the dashboard and specialized revenue reports.

## 2. Core Operational Flow

To run the laundry business efficiently, follow this standard workflow:

### Step 1: Manage Master Data
Before taking orders, ensure your master data is ready:
1.  **Layanan (Services)**: (Admin only) Add the types of services you offer (e.g., Cuci Kering, Setrika) and set the price per kilogram.
2.  **Pelanggan (Customers)**: Add your customers with their name and phone number so you can track who owns which laundry bag.

### Step 2: Create a New Order
When a customer drops off their laundry:
1.  Go to the **"Order Laundry"** menu.
2.  Click **"Buat Order"**.
3.  Select the **Customer** from the dropdown.
4.  Add one or more **Services**.
5.  Enter the **Weight (Qty)** in grams (e.g., enter 2000 for 2kg). The system will automatically calculate the subtotal.
6.  Enter the amount paid by the customer in **Uang Bayar** to see the change calculation.
7.  Click **"Simpan Order"**.

### Step 3: Update Order Status
You can track the progress of an order:
1.  View the list of orders to see which are **"Baru"** (Pending).
2.  Once the laundry is finished and picked up by the customer, click the **Detail** (Eye) icon.
3.  Click the **"Sudah Diambil"** button to complete the transaction.

### Step 4: Monitoring & Reporting
- **Dashboard**: Use the dashboard to see a quick summary of total customers and today's activity.
- **Laporan (Reports)**: (Pimpinan & Admin) Select a date range (Start Date to End Date) to see all completed transactions and total revenue for that period.

## 3. UI Overview
The application uses a clean **White, Green, and Yellow** theme designed for clarity:
- **Green**: Primary actions and "Successfully Completed" states.
- **Yellow**: Warning states or "Pending/New" indicators.
- **White**: Clean workspace for daily operations.
