-- ============================================================
-- Mueblería La Bendición - Schema SQL
-- ============================================================
-- Run this in MySQL after creating the database:
--   CREATE DATABASE muebleria_bendicion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- ============================================================

USE muebleria_bendicion;

-- Roles
CREATE TABLE roles (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(50) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL
);

-- Users (admins + cobradores)
CREATE TABLE users (
    id                 BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name               VARCHAR(100) NOT NULL,
    email              VARCHAR(150) NOT NULL UNIQUE,
    email_verified_at  TIMESTAMP NULL,
    password           VARCHAR(255) NOT NULL,
    role_id            BIGINT UNSIGNED NOT NULL,
    telefono           VARCHAR(20),
    activo             TINYINT(1) NOT NULL DEFAULT 1,
    remember_token     VARCHAR(100),
    created_at         TIMESTAMP NULL,
    updated_at         TIMESTAMP NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT
);

-- Proveedores
CREATE TABLE proveedores (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(150) NOT NULL,
    contacto    VARCHAR(150),
    telefono    VARCHAR(20),
    email       VARCHAR(150),
    direccion   VARCHAR(255),
    notas       TEXT,
    activo      TINYINT(1) NOT NULL DEFAULT 1,
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL,
    deleted_at  TIMESTAMP NULL
);

-- Categorias
CREATE TABLE categorias (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255),
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL
);

-- Productos
CREATE TABLE productos (
    id             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    codigo         VARCHAR(50) NOT NULL UNIQUE,
    nombre         VARCHAR(200) NOT NULL,
    descripcion    TEXT,
    categoria_id   BIGINT UNSIGNED,
    precio_costo   DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    precio_venta   DECIMAL(10,2) NOT NULL,
    stock          INT NOT NULL DEFAULT 0,
    stock_minimo   INT NOT NULL DEFAULT 5,
    imagen         VARCHAR(255),
    activo         TINYINT(1) NOT NULL DEFAULT 1,
    created_at     TIMESTAMP NULL,
    updated_at     TIMESTAMP NULL,
    deleted_at     TIMESTAMP NULL,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

-- Clientes (pertenecen a un cobrador)
CREATE TABLE clientes (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    apellido    VARCHAR(100) NOT NULL,
    telefono    VARCHAR(20),
    email       VARCHAR(150),
    direccion   VARCHAR(255),
    dui         VARCHAR(20),
    nit         VARCHAR(20),
    referencias TEXT,
    cobrador_id BIGINT UNSIGNED NOT NULL,
    activo      TINYINT(1) NOT NULL DEFAULT 1,
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL,
    deleted_at  TIMESTAMP NULL,
    FOREIGN KEY (cobrador_id) REFERENCES users(id) ON DELETE RESTRICT
);

-- Ventas
CREATE TABLE ventas (
    id                 BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    numero_venta       VARCHAR(20) NOT NULL UNIQUE,
    cliente_id         BIGINT UNSIGNED NOT NULL,
    cobrador_id        BIGINT UNSIGNED NOT NULL,
    fecha_venta        DATE NOT NULL,
    subtotal           DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    descuento          DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total              DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    enganche           DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    saldo_pendiente    DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    tipo_pago          ENUM('contado','credito') NOT NULL DEFAULT 'credito',
    num_pagos          INT NOT NULL DEFAULT 1,
    monto_cuota        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    frecuencia_pago    ENUM('semanal','quincenal','mensual') NOT NULL DEFAULT 'mensual',
    fecha_primer_pago  DATE,
    estado             ENUM('activa','liquidada','cancelada') NOT NULL DEFAULT 'activa',
    notas              TEXT,
    created_at         TIMESTAMP NULL,
    updated_at         TIMESTAMP NULL,
    deleted_at         TIMESTAMP NULL,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT,
    FOREIGN KEY (cobrador_id) REFERENCES users(id) ON DELETE RESTRICT
);

-- Detalle de ventas
CREATE TABLE venta_detalles (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    venta_id         BIGINT UNSIGNED NOT NULL,
    producto_id      BIGINT UNSIGNED NOT NULL,
    cantidad         INT NOT NULL,
    precio_unitario  DECIMAL(10,2) NOT NULL,
    descuento        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    subtotal         DECIMAL(10,2) NOT NULL,
    created_at       TIMESTAMP NULL,
    updated_at       TIMESTAMP NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT
);

-- Pagos de clientes
CREATE TABLE pagos (
    id              BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    numero_recibo   VARCHAR(20) NOT NULL UNIQUE,
    venta_id        BIGINT UNSIGNED NOT NULL,
    cobrador_id     BIGINT UNSIGNED NOT NULL,
    fecha_pago      DATE NOT NULL,
    monto           DECIMAL(10,2) NOT NULL,
    mora            DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total_cobrado   DECIMAL(10,2) NOT NULL,
    metodo_pago     ENUM('efectivo','transferencia','tarjeta','otro') NOT NULL DEFAULT 'efectivo',
    notas           TEXT,
    created_at      TIMESTAMP NULL,
    updated_at      TIMESTAMP NULL,
    deleted_at      TIMESTAMP NULL,
    FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE RESTRICT,
    FOREIGN KEY (cobrador_id) REFERENCES users(id) ON DELETE RESTRICT
);

-- Compras a proveedores
CREATE TABLE compras (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    numero_compra    VARCHAR(20) NOT NULL UNIQUE,
    proveedor_id     BIGINT UNSIGNED NOT NULL,
    fecha_compra     DATE NOT NULL,
    subtotal         DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    descuento        DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total            DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    monto_pagado     DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    saldo_pendiente  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estado           ENUM('pendiente','parcial','pagada','cancelada') NOT NULL DEFAULT 'pendiente',
    notas            TEXT,
    creado_por       BIGINT UNSIGNED NOT NULL,
    created_at       TIMESTAMP NULL,
    updated_at       TIMESTAMP NULL,
    deleted_at       TIMESTAMP NULL,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE RESTRICT,
    FOREIGN KEY (creado_por) REFERENCES users(id) ON DELETE RESTRICT
);

-- Detalle de compras
CREATE TABLE compra_detalles (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    compra_id        BIGINT UNSIGNED NOT NULL,
    producto_id      BIGINT UNSIGNED NOT NULL,
    cantidad         INT NOT NULL,
    precio_unitario  DECIMAL(10,2) NOT NULL,
    subtotal         DECIMAL(10,2) NOT NULL,
    created_at       TIMESTAMP NULL,
    updated_at       TIMESTAMP NULL,
    FOREIGN KEY (compra_id) REFERENCES compras(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE RESTRICT
);

-- Pagos a proveedores
CREATE TABLE pagos_compra (
    id               BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    compra_id        BIGINT UNSIGNED NOT NULL,
    fecha_pago       DATE NOT NULL,
    monto            DECIMAL(10,2) NOT NULL,
    metodo_pago      ENUM('efectivo','transferencia','tarjeta','otro') NOT NULL DEFAULT 'efectivo',
    notas            TEXT,
    registrado_por   BIGINT UNSIGNED NOT NULL,
    created_at       TIMESTAMP NULL,
    updated_at       TIMESTAMP NULL,
    FOREIGN KEY (compra_id) REFERENCES compras(id) ON DELETE RESTRICT,
    FOREIGN KEY (registrado_por) REFERENCES users(id) ON DELETE RESTRICT
);

-- Migrations tracking table
CREATE TABLE migrations (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    migration  VARCHAR(255) NOT NULL,
    batch      INT NOT NULL
);

-- ============================================================
-- SEED DATA
-- ============================================================
INSERT INTO roles (nombre, descripcion, created_at, updated_at) VALUES
    ('admin',    'Administrador del sistema', NOW(), NOW()),
    ('cobrador', 'Cobrador de clientes',      NOW(), NOW());

-- Admin: admin@bendicion.com / password
INSERT INTO users (name, email, password, role_id, telefono, activo, created_at, updated_at) VALUES
    ('Administrador', 'admin@bendicion.com',
     '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
     1, '7000-0001', 1, NOW(), NOW());

-- Cobrador 1: juan@bendicion.com / password
INSERT INTO users (name, email, password, role_id, telefono, activo, created_at, updated_at) VALUES
    ('Juan Pérez', 'juan@bendicion.com',
     '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
     2, '7100-0001', 1, NOW(), NOW());

-- Cobrador 2: maria@bendicion.com / password
INSERT INTO users (name, email, password, role_id, telefono, activo, created_at, updated_at) VALUES
    ('María López', 'maria@bendicion.com',
     '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
     2, '7100-0002', 1, NOW(), NOW());

INSERT INTO categorias (nombre, created_at, updated_at) VALUES
    ('Salas', NOW(), NOW()),
    ('Comedores', NOW(), NOW()),
    ('Dormitorios', NOW(), NOW()),
    ('Electrodomésticos', NOW(), NOW()),
    ('Colchones', NOW(), NOW()),
    ('Mesas y sillas', NOW(), NOW());

INSERT INTO proveedores (nombre, contacto, telefono, email, activo, created_at, updated_at) VALUES
    ('Muebles del Norte S.A.', 'Carlos Ramírez', '2222-1111', 'ventas@mueblesNorte.com', 1, NOW(), NOW()),
    ('Distribuidora El Sol',   'Ana González',   '2333-4444', NULL,                       1, NOW(), NOW());

INSERT INTO productos (codigo, nombre, descripcion, categoria_id, precio_costo, precio_venta, stock, stock_minimo, activo, created_at, updated_at) VALUES
    ('SAL-001', 'Sala 3 Piezas Moderna',     'Sala de 3 piezas tapizada en tela, color gris', 1, 350.00, 599.99, 10, 2, 1, NOW(), NOW()),
    ('COM-001', 'Comedor 6 Personas Madera', 'Mesa de madera sólida con 6 sillas',            2, 280.00, 450.00,  8, 2, 1, NOW(), NOW()),
    ('DOR-001', 'Juego de Dormitorio Completo','Cama matrimonial + cómoda + mesa de noche',   3, 420.00, 699.99,  5, 2, 1, NOW(), NOW()),
    ('COL-001', 'Colchón Spring Air Queen',  'Colchón de resortes calibrados doble cara',     5, 150.00, 249.99, 12, 3, 1, NOW(), NOW()),
    ('SAL-002', 'Sala L Moderna',            'Sala esquinera tapizada en microfibra',         1, 480.00, 799.99,  4, 2, 1, NOW(), NOW());
