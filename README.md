# BNPL (Buy Now, Pay Later) Backend API

A lightweight **Buy Now, Pay Later (BNPL)** backend built with Laravel 12 and PostgreSQL.  
The system handles purchases, installment scheduling, payment initiation, webhook-based payment confirmation, early settlement, overdue processing, and a simple financial ledger.

---

#  Features

- Create purchases with installment breakdown
- Automatic installment schedule generation (equal split + rounding safety)
- Payment initiation with idempotency protection
- Webhook-driven payment confirmation (single source of truth)
- Early settlement support
- Overdue installment detection via artisan command
- Basic ledger for financial tracking
- Fully Dockerized setup (optional but recommended)

---

#  Tech Stack

- PHP 8.3
- Laravel 12
- PostgreSQL 17
- Docker / Docker Compose

---

# Setup Instructions

## Option 1: Docker 

### 1. Clone the repository
```bash
git clone <repo-url>
cd bnpl
1. cp .env.example .env
2. Build and start containers
docker compose up --build
3. Run migrations
docker compose exec app php artisan migrate
4. Seed database 
docker compose exec app php artisan db:seed
5. Generate app key
docker compose exec app php artisan key:generate

4. Run migrations
php artisan migrate
Running the Server
php artisan serve

API base URL:

http://localhost:8000/api

#################### API Endpoints ##########################
Purchases
Create Purchase
POST /api/purchases

Get Purchase
GET /api/purchases/{id}

Payments
Initiate Payment
POST /api/payments/initiate

Webhook
Payment Webhook (Source of Truth)
POST /api/webhooks/payments

Early Settlement
POST /api/purchases/{id}/settle
Overdue Processing
php artisan bnpl:mark-overdue

############## Design Decisions #########################
1. Money Representation

All monetary values are stored as integers (minor units / cents) instead of floats.

Why:
Avoid floating-point precision issues
Ensures financial accuracy
Industry-standard practice

Example:

$10.50 → 1050 cents
2. Installment Scheduling Logic
Installments are evenly distributed
Rounding differences are adjusted in the last installment
Ensures total sum always equals purchase amount exactly
3. Idempotency Strategy
Payment Initiation
Uses idempotency_key per installment attempt
Prevents duplicate payment records on retries
Webhooks
Each webhook event is uniquely identified (event_id)
Duplicate webhook events are safely ignored
4. Concurrency Handling
Database transactions used for critical operations
lockForUpdate() applied during settlement logic
Prevents race conditions in payment creation
5. Ledger Approach

A simple ledger table tracks financial movement:

Payments confirmed via webhook
Each successful payment creates a ledger entry
Provides audit trail per purchase and merchant

##################### Trade-offs ############################

Due to the limited time scope (4–6 hours), the following were simplified:

No authentication system (assumes trusted API usage)
No payment gateway integration (simulated via webhook)
No partial payments support
No refund or chargeback flows
Minimal validation rules (kept lightweight)
No event queue system (sync processing only)
No advanced retry queues for webhook delivery

###################### What's Next (Future Improvements) ##################################

If this project were extended, I would add:

1. Authentication & Users
Customer and merchant accounts
Secure API access with tokens (Sanctum/OAuth)
2. Event-Driven Architecture
Queue-based webhook processing
Event sourcing for financial state changes
3. Payment Gateway Integration
Stripe / Adyen / Checkout.com integration
Real asynchronous payment lifecycle
4. Advanced Ledger System
Double-entry accounting system
Immutable financial records
5. Observability
Logging + tracing (Laravel Telescope / OpenTelemetry)
Metrics for payment success/failure rates
6. Retry & Failure Handling
Queue-based retry for failed webhooks
Dead-letter queue support
7. Multi-currency Support
FX conversion layer
Currency-aware installment handling
