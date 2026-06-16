# Documento de Ingeniería de Requisitos: Casos de Uso (Metodología RUP)

**Proyecto:** Reasy (Booking SaaS Modular Monolith)  
**Autor:** Dirección de Arquitectura  
**Estado del Documento:** Aprobado para Línea Base  
**Fase RUP Actual:** Fin de Elaboración $\rightarrow$ Inicio de Construcción  

---

## 1. Introducción y Enfoque Metodológico (RUP)

Este documento define la **Ruta de Trabajo (Roadmap)** para el desarrollo de la plataforma Reasy, estructurada bajo los principios del **Proceso Unificado de Rational (RUP)**. El diseño está centrado en la arquitectura (Monolito Modular con PostgreSQL RLS) y guiado por **Casos de Uso (Use-Case Driven)**.

### 1.1 El Modelo de Vistas 4+1
Toda la lógica de este documento se rige bajo la **Vista de Casos de Uso**, la cual sirve de contrato para validar la **Vista Lógica** (módulos), la **Vista de Implementación** (estructura de archivos en Laravel) y la **Vista de Procesos** (aislamiento por RLS y colas asíncronas).

```
                 +-----------------------+
                 |  Vista de Casos de    |
                 |  Uso (User Scenarios) |
                 +-----------+-----------+
                             |
       +---------------------+---------------------+
       |                     |                     |
+------v------+       +------v------+       +------v------+
| Vista Lógica|       |  Vista de   |       | Vista de    |
| (Modular    |       | Proceso     |       | Implemen-   |
| Monolith)   |       | (Tenancy/RLS|       | tación (App)|
+-------------+       +-------------+       +-------------+
```

### 1.2 Estado de las Fases RUP en Reasy
1. **Incepción (Completada):** Definición de alcance, viabilidad técnica de Stripe / PagueloFacil Connect en LATAM y selección del stack (Laravel 12 + Livewire 4 + PostgreSQL).
2. **Elaboración (Hito Alcanzado):** Establecimiento de la **Línea Base de la Arquitectura**. Se mitigó el riesgo técnico más crítico: la arquitectura multi-tenant y el aislamiento de datos mediante Row-Level Security (RLS).
3. **Construcción (Fase Actual):** Implementación iterativa de las funcionalidades del Tenant y del Administrador Central.
4. **Transición (Planificada):** Pruebas beta, optimización de consultas PostgreSQL con carga simulada, puesta en producción y entrega.

---

## 2. Línea Base de Casos de Uso por Módulo

A continuación, se listan los Casos de Uso (UC) agrupados por los tres contextos del monolito modular. Se especifica el estado de cada uno, los actores involucrados, una breve descripción y los riesgos arquitectónicos asociados.

### Contexto 0: Shared (Arquitectura y Transversal)
*Este contexto provee los cimientos del sistema. Ha sido priorizado y completado durante la fase de Elaboración para asegurar la viabilidad técnica.*

| ID           | Caso de Uso                                             | Actores          | Estado             | Riesgos Mitigados / Pendientes                                                             |
| :----------- | :------------------------------------------------------ | :--------------- | :----------------- | :----------------------------------------------------------------------------------------- |
| **UC-SH-01** | Aislamiento de Datos por PostgreSQL RLS                 | Sistema / Tenant | **`[COMPLETADO]`** | **Mitigado:** Acceso cruzado accidental a datos de otros tenants a nivel de base de datos. |
| **UC-SH-02** | Configuración e Inicialización de Conexión de Inquilino | Sistema / Tenant | **`[COMPLETADO]`** | **Mitigado:** Latencia en la resolución del tenant activo por subdominio o cabecera.       |

---

### Contexto 1: Central (Administración del SaaS)
*Módulos dedicados a la gestión global de la plataforma, cobros del SaaS y soporte técnico general.*

```
+-------------------------------------------------------------------------+
| CENTRAL CONTEXT                                                         |
|   [COMPLETADO] UC-CE-01: Aprovisionamiento de Tenant                     |
|   [POR COMPLETAR] UC-CE-02: Gestión de Planes y Suscripciones (Stripe / PagueloFacil)  |
|   [POR COMPLETAR] UC-CE-03: Portal de Soporte y Log de Auditoría         |
+-------------------------------------------------------------------------+
```

| ID           | Caso de Uso                                  | Actores                         | Estado                | Riesgo Técnico Asociado                                                                                    |
| :----------- | :------------------------------------------- | :------------------------------ | :-------------------- | :--------------------------------------------------------------------------------------------------------- |
| **UC-CE-01** | Aprovisionamiento Automatizado de Tenant     | Negocio Nuevo / Admin Central   | **`[COMPLETADO]`**    | Fallo en la migración de esquemas iniciales o asignación del subdominio.                                   |
| **UC-CE-02** | Gestión de Planes y Suscripciones Globales   | Admin Central / Tenant          | **`[POR COMPLETAR]`** | Desincronización de webhooks de pago de Stripe / PagueloFacil que provoquen que un tenant activo no pague. |
| **UC-CE-03** | Portal de Soporte y Log de Auditoría Central | Soporte Técnico / Admin Central | **`[POR COMPLETAR]`** | Degradación del rendimiento de la base de datos central por volumen de logs históricos.                    |

---

### Contexto 2: Tenant (El Core del Negocio - Reasy Product)
*Funcionalidades de cara al cliente final y al comercio que contrata Reasy. Representa el grueso de la fase de Construcción.*

```
+-------------------------------------------------------------------------+
| TENANT CONTEXT (PRODUCT)                                                |
|   [POR COMPLETAR] UC-TE-LOC: Configuración de Sedes y Zonas Horarias   |
|   [POR COMPLETAR] UC-TE-RES: Gestión de Personal y Recursos            |
|   [POR COMPLETAR] UC-TE-SER: Catálogo de Servicios y Políticas          |
|   [POR COMPLETAR] UC-TE-SCH: Motor de Disponibilidad y Slots (Crítico) |
|   [POR COMPLETAR] UC-TE-BOK: Máquina de Estados de Reserva              |
|   [POR COMPLETAR] UC-TE-PAY: Stripe / PagueloFacil Connect (Escrow de Depósitos)       |
|   [POR COMPLETAR] UC-TE-CUS: CRM de Clientes Locales                    |
+-------------------------------------------------------------------------+
```

#### Módulo: Sedes y Recursos (Locations & Resources)
| ID               | Caso de Uso                                          | Actores                  | Estado                | Riesgo Técnico Asociado                                                                        |
| :--------------- | :--------------------------------------------------- | :----------------------- | :-------------------- | :--------------------------------------------------------------------------------------------- |
| **UC-TE-LOC-01** | Configuración de Sedes y Ajustes de Zona Horaria     | Admin del Tenant         | **`[POR COMPLETAR]`** | Cálculos erróneos de horas en citas si el cliente y la sede están en diferentes husos.         |
| **UC-TE-RES-01** | Gestión de Personal (Staff) y Asignación de Recursos | Admin del Tenant / Staff | **`[POR COMPLETAR]`** | Conflictos de asignación (ej. dos profesionales asignados a una misma cabina al mismo tiempo). |

#### Módulo: Servicios (Services)
| ID               | Caso de Uso                                                    | Actores          | Estado                | Riesgo Técnico Asociado                                                                   |
| :--------------- | :------------------------------------------------------------- | :--------------- | :-------------------- | :---------------------------------------------------------------------------------------- |
| **UC-TE-SER-01** | Creación de Servicios y Definición de Políticas de Cancelación | Admin del Tenant | **`[POR COMPLETAR]`** | Aplicación inconsistente de políticas de reembolso automático ante cancelaciones tardías. |

#### Módulo: Horarios e Intervalos (Schedules - *Núcleo Complejo*)
| ID               | Caso de Uso                                                          | Actores                  | Estado                | Riesgo Técnico Asociado                                                                                     |
| :--------------- | :------------------------------------------------------------------- | :----------------------- | :-------------------- | :---------------------------------------------------------------------------------------------------------- |
| **UC-TE-SCH-01** | Definición de Jornadas Laborales y Excepciones (Feriados/Vacaciones) | Staff / Admin del Tenant | **`[POR COMPLETAR]`** | Solapamiento de horarios excepcionales con horarios regulares del personal.                                 |
| **UC-TE-SCH-02** | Motor de Cálculo de Slots Disponibles en Tiempo Real                 | Cliente Final / Sistema  | **`[POR COMPLETAR]`** | **Alto Riesgo:** Alta latencia de carga en frontend al calcular disponibilidad en rangos de fechas amplios. |

#### Módulo: Reservas (Bookings)
| ID               | Caso de Uso                                         | Actores                   | Estado                | Riesgo Técnico Asociado                                                                                  |
| :--------------- | :-------------------------------------------------- | :------------------------ | :-------------------- | :------------------------------------------------------------------------------------------------------- |
| **UC-TE-BOK-01** | Creación de Reserva con Bloqueo Temporal de Slot    | Cliente Final             | **`[POR COMPLETAR]`** | **Condición de Carrera:** Doble reserva del mismo slot en el mismo segundo por dos usuarios diferentes.  |
| **UC-TE-BOK-02** | Ciclo de Vida y Transición de Estados de la Reserva | Staff / Cliente / Sistema | **`[POR COMPLETAR]`** | Transición de estados inválida (ej. pasar una cita de 'Cancelada' a 'Completada' sin lógica de control). |

#### Módulo: Pagos (Payments)
| ID               | Caso de Uso                                                | Actores                         | Estado                | Riesgo Técnico Asociado                                                                                             |
| :--------------- | :--------------------------------------------------------- | :------------------------------ | :-------------------- | :------------------------------------------------------------------------------------------------------------------ |
| **UC-TE-PAY-01** | Vinculación con Stripe / PagueloFacil Connect (Onboarding) | Admin del Tenant                | **`[POR COMPLETAR]`** | Incompatibilidad de regulaciones fiscales locales en países específicos de LATAM durante el onboarding.             |
| **UC-TE-PAY-02** | Retención de Depósitos (Escrow) y Reembolsos Automáticos   | Sistema / Stripe / PagueloFacil | **`[POR COMPLETAR]`** | **Alto Riesgo:** Errores de API de Stripe / PagueloFacil que dejen transacciones en el limbo de cobro o devolución. |

#### Módulo: Clientes (Customers)
| ID               | Caso de Uso                                       | Actores                  | Estado                | Riesgo Técnico Asociado                                                                             |
| :--------------- | :------------------------------------------------ | :----------------------- | :-------------------- | :-------------------------------------------------------------------------------------------------- |
| **UC-TE-CUS-01** | CRM Interno y Registro de Historial de Asistencia | Staff / Admin del Tenant | **`[POR COMPLETAR]`** | Fuga de datos sensibles de clientes (debe garantizarse que el aislamiento RLS cubra el módulo CRM). |

---

## 3. Ruta de Trabajo e Iteraciones (Fase de Construcción)

Con la arquitectura base (`Shared` y aislamiento RLS) ya validada en la fase de Elaboración, la fase de Construcción se estructurará en **4 iteraciones incrementales** de 3 semanas cada una. El objetivo es mitigar los riesgos más altos primero.

```
       CONSTRUCCIÓN: CRONOGRAMA DE ITERACIONES
       
 [ Iteración C1 ] ----> [ Iteración C2 ] ----> [ Iteración C3 ] ----> [ Iteración C4 ]
  Estructura Base        Motor de Horarios      Pagos y Escrow         SaaS central
  Sedes y Recursos       y Reservas             Stripe / PagueloFacil Connect         Suscripciones
  (Riesgo: Bajo)         (Riesgo: Alto)         (Riesgo: Muy Alto)     (Riesgo: Medio)
```

### Iteración C1: Estructura del Negocio (Sedes, Recursos y Servicios)
* **Objetivo:** Permitir al Tenant configurar su entorno de operación básico.
* **Casos de Uso a Desarrollar:** 
  * `UC-TE-LOC-01` (Sedes y Horarios)
  * `UC-TE-RES-01` (Personal y Recursos)
  * `UC-TE-SER-01` (Servicios y Políticas)
* **Criterio de Aceptación:** El inquilino puede crear una sede con personal y servicios asociados sin que existan inconsistencias de zona horaria.

### Iteración C2: Motor de Disponibilidad y Reservas (El Núcleo Operativo)
* **Objetivo:** Implementar el motor de cálculo de disponibilidad y la máquina de estados de reservas sin pasarela de pago.
* **Casos de Uso a Desarrollar:**
  * `UC-TE-SCH-01` (Jornadas y Excepciones)
  * `UC-TE-SCH-02` (Motor de Slots en Tiempo Real) [Hito Crítico]
  * `UC-TE-BOK-01` y `UC-TE-BOK-02` (Flujo de Citas)
* **Criterio de Aceptación:** No se permiten reservas duplicadas bajo simulación de concurrencia concurrente (*stress testing* de 50 peticiones simultáneas sobre el mismo slot).

### Iteración C3: Integración de Finanzas (Stripe / PagueloFacil Connect y No-Shows)
* **Objetivo:** Introducir el flujo monetario para asegurar el valor de "Cero No-Shows".
* **Casos de Uso a Desarrollar:**
  * `UC-TE-PAY-01` (Onboarding Stripe / PagueloFacil Connect)
  * `UC-TE-PAY-02` (Cobros y Reembolsos Automáticos)
* **Criterio de Aceptación:** Si un cliente cancela dentro de las horas penalizadas por la política del tenant, el depósito es retenido y transferido correctamente a la cuenta conectada del comercio (simulado en Stripe / PagueloFacil Sandbox).

### Iteración C4: Escalabilidad del SaaS y Soporte (Habilitación Comercial)
* **Objetivo:** Completar el ecosistema administrativo para poder comercializar la plataforma.
* **Casos de Uso a Desarrollar:**
  * `UC-CE-02` (Facturación global del SaaS / Suscripción de Inquilinos)
  * `UC-CE-03` (Portal de Soporte e Historial de Auditoría)
  * `UC-TE-CUS-01` (CRM Local del Tenant)
* **Criterio de Aceptación:** Bloqueo automático del acceso de un tenant al expirar su suscripción global sin pérdida de sus datos locales en PostgreSQL.

---

## 4. Matriz de Trazabilidad Arquitectónica y Control de Calidad

Para asegurar la calidad durante la fase de Construcción, cada Caso de Uso del sistema deberá contar con los siguientes entregables técnicos antes de considerarse completado:

1. **Pruebas Automatizadas (Pest):**
   * **Unitarias:** Comprobación del motor de cálculo de disponibilidad (`UC-TE-SCH-02`) de manera aislada.
   * **Integración (Frontera):** Simulación de peticiones HTTP que validen que un Tenant A no puede consultar información de un Tenant B bajo ninguna circunstancia.
2. **Validación de Performance (Laravel Pulse / Telescope):**
   * Ninguna consulta de base de datos ejecutada por el motor de slots debe tardar más de **50ms** en promedio para rangos normales de consulta (7 días).
3. **Análisis de Riesgos Post-Implementación:**
   * Al finalizar cada Caso de Uso, el desarrollador responsable deberá agregar al bloque del código los riesgos detectados e identificados durante la codificación para su monitoreo durante la fase de Transición.