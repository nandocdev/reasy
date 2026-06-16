# Reasy Product Modules Architecture (Tenant Context)

This document describes the technical structure and responsibilities of the product-specific modules for **Reasy**, located under `app/Modules/Tenant/`. 

All modules follow the **Modular Monolith** pattern and adhere to the architectural standards defined in `GEMINI.md`, including **PostgreSQL Row-Level Security (RLS)** for strict tenant isolation.

---

## Directory Standard
Each module follows this internal structure:
```text
app/Modules/Tenant/Reasy/{Domain}/
├── Actions/        # Single-responsibility business logic units
├── DTOs/           # Data Transfer Objects for type-safe input
├── Models/         # Eloquent models (Tenant-aware via BelongsToTenant)
├── Providers/      # Service providers for module registration
├── Services/       # Orchestrators for complex workflows
├── UI/             # Blade components and layout fragments
├── Livewire/       # Interactive UI components (Flux UI)
├── Database/       # Migrations and Factories specific to the domain
└── Tests/          # Pest tests for the module
```

---

## 1. Locations (Sedes)
**Namespace:** `App\Modules\Tenant\Reasy\Locations`

Responsible for managing the physical or logical branches where services are provided.

- **Technical Scope:**
  - CRUD for locations with time zone and contact overrides.
  - Geographical data management (Coordinates for future maps integration).
  - Operating hours configuration (Base schedule for the location).
- **Key Actions:**
  - `CreateLocationAction`, `UpdateLocationAction`, `SwitchLocationStatusAction`.
- **Isolation:** Protected by RLS via `tenant_id`.

## 2. Services (Catálogo)
**Namespace:** `App\Modules\Tenant\Reasy\Services`

Manages the catalog of services, pricing, and duration logic.

- **Technical Scope:**
  - Service definitions (Duration, Buffers, Pricing).
  - Service categorization.
  - Deposit policy association (Percentage vs. Fixed).
  - Visibility logic for the booking widget.
- **Key Actions:**
  - `CreateServiceAction`, `UpdateServicePricingAction`, `ArchiveServiceAction`.
- **Integrations:** Links to `CancellationPolicies` in the Settings module.

## 3. Resources (Recursos/Personal)
**Namespace:** `App\Modules\Tenant\Reasy\Resources`

Manages the entities that can be booked: Staff, Rooms, or Equipment.

- **Technical Scope:**
  - Resource types (Staff, Room, Equipment).
  - Skill/Service mapping (Which resource can perform which service).
  - Integration with `Identity` module (Linking Staff to a User).
  - Resource capacity management.
- **Key Actions:**
  - `RegisterResourceAction`, `AssignServiceToResourceAction`.

## 4. Schedules (Motor de Disponibilidad)
**Namespace:** `App\Modules\Tenant\Reasy\Schedules`

The computational engine for slots and availability.

- **Technical Scope:**
  - Base schedule management (Day-of-week slots).
  - Exception management (Holidays, Sick leave).
  - Manual blocks (Administrative time blocking).
  - **Availability Engine:** Logic to calculate free slots by intersecting location hours, resource hours, existing bookings, and service buffers.
- **Key Actions:**
  - `GenerateSlotsAction` (Real-time calculation).
  - `CreateManualBlockAction`.

## exceleContinua con el
**Namespace:** `App\Modules\Tenant\Reasy\Bookings`

Core module managing the lifecycle of appointments.

- **Technical Scope:**
  - State Machine implementation (Draft -> Pending -> Confirmed -> Completed/No-Show).
  - Slot reservation logic with **Race Condition protection** (`SELECT FOR UPDATE`).
  - Booking expiration logic for unpaid deposits.
  - Unique token generation for guest management.
- **Key Actions:**
  - `ReserveSlotAction`, `ConfirmBookingAction`, `CancelBookingAction`, `MarkNoShowAction`.
- **Validation:** Integrates with `Schedules` to ensure slot integrity.

## 6. Customers (CRM)
**Namespace:** `App\Modules\Tenant\Reasy\Customers`

Manages the business-specific customer database.

- **Technical Scope:**
  - Separation of `Customer` entity from `Central\User`.
  - Profile tracking (Lifetime value, No-show rate).
  - Internal notes and tagging.
  - Consent management for notifications.
- **Key Actions:**
  - `SyncCustomerAction`, `BanCustomerAction`.

## 7. Payments (Pasarela Tenant)
**Namespace:** `App\Modules\Tenant\Reasy\Payments`

Handles the financial flow between the end-customer and the tenant.

- **Technical Scope:**
  - **Stripe Connect Integration:** Processing deposits.
  - Automatic refund logic based on cancellation policies.
  - Application fee calculation for the platform.
  - Idempotent webhook processing for transaction status.
- **Key Actions:**
  - `ProcessDepositAction`, `IssueRefundAction`, `ReconcilePaymentAction`.

## 8. Notifications (Comunicaciones)
**Namespace:** `App\Modules\Tenant\Reasy\Notifications`

Orchestrates communication with customers and staff.

- **Technical Scope:**
  - Template rendering with dynamic variables.
  - Multi-channel dispatch (Email, future SMS/WhatsApp).
  - Dispatch retry logic with exponential backoff.
- **Key Actions:**
  - `SendConfirmationEmailAction`, `ScheduleReminderAction`.

---

## Cross-Module Interaction Pattern
Modules interact via **Shared Events** or **Services**.
- Example: When a `BookingCancelled` event is fired (from Bookings), the `IssueRefundAction` (in Payments) is triggered via a Listener if a deposit exists.

---

## Technical Standards
1. **RLS Enforcement:** Every migration MUST include the `ALTER TABLE ... ENABLE ROW LEVEL SECURITY` block.
2. **UUIDs:** All primary keys MUST be UUIDs.
3. **Pest Testing:** 100% coverage for Actions within the `Bookings` and `Payments` modules.
4. **Flux UI:** All Tenant-side UI must use Flux components for consistency.