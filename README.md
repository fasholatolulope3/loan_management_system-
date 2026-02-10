# POWER DOVE EMPOWERMENT INITIATIVE (PDEI)

A robust, enterprise-grade solution for managing loan pipelines, client onboarding, and financial reporting. Built with Laravel 12, this system emphasizes security, auditability, and ease of use for financial institutions.

## 🚀 Key Features

- **KYC & Onboarding**: Seamless client profile completion with integrated KYC verification.
- **Loan Pipeline**: end-to-end management from proposal to review and approval.
- **Guarantor Registry**: Comprehensive assessment registry for loan guarantors (Form CF4).
- **Repayment Journal**: Track and verify payment receipts with authority verification.
- **Reporting & Compliance**: Global summary statistics, arrears search, and printable audit trails.
- **Role-Based Access Control**: Granular permissions for Admins, Officers, and Clients.
- **Audit Logging**: Full traceability of all executive actions and system changes.

## 🛠️ Technical Stack

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend**: Blade with Tailwind CSS & Alpine.js
- **Database**: PostgreSQL (Recommended for production) / SQLite (Local development)
- **Frontend Build Tool**: Vite
- **Auth**: Laravel Breeze

## 📦 Installation & Setup

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite or PostgreSQL

### Local Development Setup

1. **Clone the repository**:

    ```bash
    git clone <repository-url>
    cd loan_management_system
    ```

2. **Install PHP dependencies**:

    ```bash
    composer install
    ```

3. **Install JS dependencies**:

    ```bash
    npm install
    ```

4. **Environment Configuration**:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Database Migration**:

    ```bash
    php artisan migrate --seed
    ```

6. **Start the Development Servers**:

    ```bash
    # In terminal 1
    php artisan serve

    # In terminal 2
    npm run dev
    ```

## 🛡️ Security

If you discover any security-related issues, please email the development team. Security vulnerabilities will be addressed promptly.

## 📜 License

This project is licensed under the [MIT license](LICENSE).
