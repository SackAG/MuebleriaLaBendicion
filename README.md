
# 🛋️ Mueblería La Bendición — Sistema de Inventario

Sistema de inventario, ventas a crédito y control de cobros desarrollado con **Laravel 10** + **Bootstrap 5** + **MySQL**.

---

## 🚀 Instalación Rápida

### Requisitos
- PHP 8.1+
- Composer
- MySQL 8.0+
- Node.js (opcional, para compilar assets adicionales)

### Pasos

```bash
# 1. Clonar / descomprimir el proyecto
cd MuebleriaBendicion

# 2. Instalar dependencias PHP
composer install

# 3. Copiar configuración
cp .env.example .env

# 4. Generar clave de aplicación
php artisan key:generate

# 5. Configurar base de datos en .env
DB_DATABASE=muebleria_bendicion
DB_USERNAME=root
DB_PASSWORD=tu_password

# 6. Crear base de datos
mysql -u root -p -e "CREATE DATABASE muebleria_bendicion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 7. Ejecutar migraciones y datos de prueba
php artisan migrate --seed

# 8. Crear link de almacenamiento de imágenes
php artisan storage:link

# 9. Iniciar servidor
php artisan serve
```

Abrir: **http://localhost:8000**

### Opción alternativa: SQL directo
Si no quieres usar migraciones, importa el esquema SQL:
```bash
mysql -u root -p muebleria_bendicion < database/schema.sql
```

---

## 👤 Usuarios por defecto

| Email                    | Contraseña | Rol      |
|--------------------------|------------|----------|
| admin@bendicion.com      | password   | Admin    |
| juan@bendicion.com       | password   | Cobrador |
| maria@bendicion.com      | password   | Cobrador |

---

## 🏗️ Arquitectura del Sistema

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/LoginController.php        ← Autenticación
│   │   ├── DashboardController.php         ← Dashboard por rol
│   │   ├── ClienteController.php           ← CRUD clientes (scoped por cobrador)
│   │   ├── VentaController.php             ← CRUD ventas + cálculo cuotas
│   │   ├── PagoController.php              ← Cobros + pendientes
│   │   ├── ProductoController.php          ← Inventario productos
│   │   ├── CompraController.php            ← Compras a proveedores
│   │   ├── ProveedorController.php         ← CRUD proveedores
│   │   └── UsuarioController.php           ← Gestión cobradores (solo admin)
│   └── Middleware/
│       ├── AdminMiddleware.php             ← Bloquea no-admins
│       └── CobradorScopeMiddleware.php     ← Scope de cobrador
├── Models/
│   ├── User.php, Role.php
│   ├── Cliente.php, Venta.php, VentaDetalle.php
│   ├── Pago.php
│   ├── Producto.php, Categoria.php
│   ├── Compra.php, CompraDetalle.php, PagoCompra.php
│   └── Proveedor.php
database/
├── migrations/            ← 8 archivos de migración ordenados
├── seeders/DatabaseSeeder.php
└── schema.sql             ← Schema SQL alternativo con datos de prueba
resources/views/
├── layouts/app.blade.php  ← Layout principal con sidebar
├── auth/login.blade.php
├── dashboard/{admin,cobrador}.blade.php
├── clientes/              ← index, create, edit, show
├── ventas/                ← index, create, show
├── pagos/                 ← index, create, show, pendientes
├── productos/             ← index, create, edit, show
├── compras/               ← index, create, show
├── proveedores/           ← index, create, edit
└── usuarios/              ← index, create, edit
```

---

## 🔐 Control de Acceso (Roles)

### Admin
- ✅ Acceso total al sistema
- ✅ Ver datos de TODOS los cobradores
- ✅ Gestión de usuarios/cobradores
- ✅ Inventario, compras, proveedores
- ✅ Eliminar pagos (con reversión de saldo)

### Cobrador
- ✅ Ver y gestionar SOLO sus propios clientes
- ✅ Ver solo sus propias ventas y cobros
- ✅ Registrar pagos de sus clientes
- ❌ No puede ver clientes de otros cobradores
- ❌ No puede acceder a inventario, compras ni usuarios

**Aislamiento implementado en:**
- `ClienteController::authorizeCliente()` → verifica `cobrador_id`
- `VentaController::authorizeVenta()` → verifica `cobrador_id`
- `PagoController::create/store()` → verifica `cobrador_id` de la venta
- `DashboardController` → queries filtradas por `cobrador_id`
- Middleware `admin` en rutas de inventario/usuarios

---

## 📦 Módulos del Sistema

### 1. Clientes
- Nombre, apellido, teléfono, DUI/NIT, dirección
- Asignados a un cobrador específico
- Historial de ventas y saldo total
- Búsqueda por nombre, teléfono o DUI

### 2. Ventas / Pedidos
- Selector de múltiples productos con cantidades y precios
- Tipo: **Contado** o **Crédito**
- Para crédito: enganche, N° cuotas, frecuencia (semanal/quincenal/mensual)
- Estados: Activa / Liquidada / Cancelada
- Cálculo automático de cuota mensual
- Control de stock (decrementa al vender)

### 3. Pagos / Cobros
- Registro por venta específica
- Campo de mora adicional
- Métodos: efectivo, transferencia, tarjeta
- Vista de **Pagos Pendientes** por cobrador
- Número de recibo autogenerado (R-000001)
- Admin puede anular pagos (revierte el saldo)

### 4. Inventario de Productos
- Código, categoría, precio costo/venta
- Control de stock con alerta de stock mínimo
- Historial de ventas del producto
- Imagen opcional

### 5. Compras a Proveedores
- Orden de compra con múltiples productos
- Incrementa stock automáticamente al registrar
- Control de pagos parciales al proveedor
- Estados: Pendiente / Parcial / Pagada

### 6. Proveedores
- Contacto, teléfono, email, dirección

### 7. Dashboard
- **Admin**: métricas globales, desempeño por cobrador, ventas recientes, alerta bajo stock
- **Cobrador**: sus cobros del día/mes, cuentas por cobrar, pagos recientes

---

## 🎨 UI/UX

- **Bootstrap 5.3** con Bootstrap Icons
- Sidebar morado corporativo (`#4a1942`)
- Responsive (sidebar colapsable en móvil)
- Alertas de éxito/error con Bootstrap
- Tablas con paginación Laravel/Bootstrap 5
- Formularios dinámicos con JavaScript vanilla para agregar/quitar productos

---

## 🗄️ Base de Datos (ERD simplificado)

```
roles ─────────── users
                    │
          ┌─────────┴──────────┐
          │                    │
       clientes              compras ── compra_detalles ── productos
          │                    │                              │
        ventas            pagos_compra              categorias
          │
    ┌─────┴──────────┐
    │                │
venta_detalles     pagos
```

---

## ⚙️ Comandos útiles

```bash
# Limpiar caché
php artisan cache:clear && php artisan config:clear && php artisan view:clear

# Re-ejecutar migraciones + seed
php artisan migrate:fresh --seed

# Ver rutas registradas
php artisan route:list
```