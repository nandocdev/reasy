# 🗓️ Reasy — Multi-Tenant Booking SaaS

> La solución más simple y robusta para la gestión de reservas y servicios en LATAM.

Reasy es una plataforma **SaaS de gestión de reservas** diseñada para negocios de servicios (belleza, bienestar, salud, consultoría) que permite a múltiples negocios operar de forma independiente bajo una arquitectura **Modular Monolith** con aislamiento total de datos.

---

## 🚀 Propuesta de Valor

- **Cero No-Shows**: Cobro de depósitos automáticos vía Stripe Connect.
- **Aislamiento Total**: Seguridad de grado bancario mediante **PostgreSQL Row-Level Security (RLS)**.
- **Motor de Disponibilidad**: Cálculo de slots en tiempo real evitando doble-bookings.
- **Onboarding Rápido**: De registro a primera reserva en menos de 30 minutos.

---

## 🛠️ Stack Tecnológico

- **Framework**: Laravel 12+ (PHP 8.4+)
- **Frontend**: Livewire 4 + Flux UI + TailwindCSS
- **Base de Datos**: PostgreSQL 16+ (RLS Nativo)
- **Multi-Tenancy**: Stancl/Tenancy + Lógica de aislamiento personalizada
- **Pagos**: Stripe Connect (Marketplace model)
- **Infraestructura**: Modular Monolith

---

## 🏗️ Arquitectura de Módulos

El proyecto está organizado en tres contextos principales:

### 1. Central Context (`app/Modules/Central`)
Gestiona la plataforma global:
- **Provisioning**: Creación y ciclo de vida de tenants.
- **Billing**: Planes, suscripciones y facturación global.
- **Auth**: Autenticación de administradores de plataforma.
- **Support**: Herramientas de soporte y bitácora de acciones.

### 2. Tenant Context (`app/Modules/Tenant/Reasy`)
El núcleo del producto **Reasy**:
- **Locations**: Gestión de sedes físicas y zonas horarias.
- **Services**: Catálogo de servicios, categorías y políticas de cancelación.
- **Resources**: Gestión de personal (staff), salas y equipamiento.
- **Schedules**: Motor de horarios, excepciones y slots.
- **Bookings**: Ciclo de vida de la reserva y máquina de estados.
- **Customers**: CRM interno del negocio.
- **Payments**: Gestión de depósitos y reembolsos automáticos.

### 3. Shared Context (`app/Modules/Shared`)
Funcionalidades transversales:
- **Tenancy**: Bootstrappers de RLS y lógica de aislamiento.
- **Infrastructure**: Clases base, contratos y helpers.

---

## 🛡️ Seguridad y Aislamiento

Reasy utiliza **Row-Level Security (RLS)** a nivel de base de datos. Cada consulta SQL está filtrada automáticamente por el `tenant_id` en la sesión de PostgreSQL, garantizando que:
1. Un tenant **nunca** pueda acceder a datos de otro.
2. Los desarrolladores no tengan que añadir `where('tenant_id', ...)` manualmente en cada query.
3. El aislamiento sea forzado incluso en queries raw de SQL.

---

## 📋 Requisitos del Sistema

- PHP 8.4+
- PostgreSQL 16+
- Redis 7+
- Node.js 20+

---

## 🛠️ Instalación

1. Clonar el repositorio:
   ```bash
   git clone https://github.com/nandocdev/reasy.git
   ```
2. Instalar dependencias:
   ```bash
   composer install
   npm install && npm run build
   ```
3. Configurar entorno:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Ejecutar migraciones y seeders:
   ```bash
   php artisan migrate:fresh --seed
   ```

---

## 📄 Documentación

- [Product Requirements Document (PRD)](docs/enginer/REASY.md)
- [Arquitectura Técnica de Módulos](docs/engineer/PRODUCT_MODULES.md)
- [Estándares de Ingeniería](GEMINI.md)

---

## ⚖️ Licencia

Propiedad de **Reasy / Plinth**. Todos los derechos reservados.
