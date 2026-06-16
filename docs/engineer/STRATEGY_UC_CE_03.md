# Plan de Acción: Portal de Soporte y Log de Auditoría Central (UC-CE-03)

Este documento define la estrategia técnica para implementar el centro de control administrativo de la plataforma, permitiendo a los administradores de Reasy monitorear la actividad global y asistir a los inquilinos.

## 1. Log de Auditoría Central (Vista de Observabilidad)
**Objetivo:** Proveer transparencia sobre las acciones críticas realizadas en la capa de infraestructura y administración.

- [ ] **Crear Componente Livewire `CentralAuditLog`**:
    - Listado paginado de la tabla `activity_log`.
    - Filtros por: Actor (Admin), Sujeto (Tenant/Plan/User), Evento (Created/Updated/Deleted) y Fecha.
    - Visualización de cambios (Old vs New values) en un modal o detalle expandible.

## 2. Portal de Asistencia a Inquilinos (Tenant Support Portal)
**Objetivo:** Centralizar las herramientas de soporte para reducir el tiempo de respuesta a incidentes.

- [ ] **Crear Componente Livewire `TenantSupportPortal`**:
    - Buscador global de Tenants por Nombre, Slug o Email del dueño.
    - Acciones rápidas por Tenant:
        - **Impersonate**: Iniciar sesión como el dueño del tenant (integrar con `TenantImpersonationController`).
        - **Plan Change**: Ajuste manual de plan en caso de soporte.
        - **Status Toggle**: Suspender o activar acceso inmediatamente.
    - Integración de `TenantSupportBitacora` para ver y añadir notas internas de soporte.

## 3. Integración de Navegación
**Objetivo:** Hacer accesibles las nuevas herramientas en el panel central.

- [ ] **Actualizar `SupportServiceProvider`**: Registrar las nuevas rutas bajo el middleware `auth:central`.
- [ ] **Actualizar Sidebar Central**: Añadir los accesos a "Audit Log" y "Tenant Support" bajo el grupo de "Support".

---

## Matriz de Riesgos (RUP)
| Riesgo | Impacto | Mitigación |
| :--- | :--- | :--- |
| **Degradación de Performance** | Alto | Implementar índices en `activity_log` para `causer_id` y `subject_id`. Usar paginación estricta. |
| **Fuga de Privacidad** | Crítico | El Audit Log central solo debe mostrar cambios estructurales (metadatos), nunca datos sensibles del negocio del tenant (ej. nombres de sus clientes finales). |
| **Abuso de Impersonación** | Alto | Registrar cada sesión de impersonación en el Audit Log con el `user_id` del administrador responsable. |
