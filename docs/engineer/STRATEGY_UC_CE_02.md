# Plan de Acción: Gestión de Planes y Suscripciones (UC-CE-02)

Este documento define la estrategia técnica para unificar la facturación global del SaaS, soportando tanto Stripe (vía Laravel Cashier) como PagueLoFácil (vía motor custom) bajo una misma interfaz de dominio.

## 1. Fase de Abstracción (Shared Context)
**Objetivo:** Crear un lenguaje común para las suscripciones.

- [x] **Definir `SubscriptionInterface`**: Crear un contrato en `Shared\Contracts\Billing` que defina métodos como `isActive()`, `getPlan()`, `getExpiryDate()`.
- [x] **Crear `BillingManager`**: Un servicio en `Shared` que resuelva si un Tenant debe ser validado contra Stripe o contra la base de datos local (PagueLoFácil).

## 2. Fase de Implementación (Central context)
**Objetivo:** Sincronizar el estado del dinero con el acceso al sistema.

- [x] **Refactorizar `FulfillSubscription` Listener**: Asegurar que cuando un pago de PagueLoFácil sea `Approved`, se actualice la tabla `subscriptions` y el campo `plan_id` en `tenants`.
- [x] **Sincronización con Stripe Webhooks**: Crear un Listener para eventos de Cashier (`WebhookReceived`) que mantenga el campo `plan_id` del Tenant actualizado en tiempo real.
- [ ] **Lógica de Prorrateo**: Implementar una `Action` que calcule días restantes cuando un tenant cambia de un plan manual (PagueloFacil) a uno automático (Stripe) o viceversa.

## 3. Fase de Control (Enforcement)
**Objetivo:** Bloqueo automático por falta de pago.

- [ ] **Middleware `EnsureTenantHasActiveSubscription`**: 
    - Si el Tenant está en un plan de pago pero no tiene una suscripción válida/activa, redirigir a `/billing`.
    - Excepción: Permitir acceso a `Settings` y `Billing` para que puedan regularizar su situación.
- [ ] **Comando de Consola `billing:check-expirations`**: Un Job diario que marque como `past_due` o `suspended` a los tenants que pagan vía PagueLoFácil y cuyo periodo ha expirado.

## 4. Fase de UI (Admin & Tenant)
**Objetivo:** Visibilidad del estado.

- [ ] **Badge de Estado en Sidebar**: Mostrar visualmente si la suscripción está próxima a vencer.
- [ ] **Vista de Historial de Facturas Unificado**: Una tabla que liste tanto Invoices de Stripe como registros de PagueLoFácil.

---

## Matriz de Riesgos (RUP)
| Riesgo | Impacto | Mitigación |
| :--- | :--- | :--- |
| **Doble Cobro** | Crítico | Implementar idempotencia estricta en el `BillingManager` usando el `gateway_reference`. |
| **Falso Positivo de Acceso** | Alto | El Middleware siempre debe consultar la base de datos central, nunca confiar solo en la sesión del tenant. |
| **Latencia de Webhooks** | Medio | Implementar un "Periodo de Gracia" de 24 horas antes de bloquear el acceso tras un fallo de pago. |
