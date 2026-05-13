# Documentación del Sistema — Inventory System

> Sistema de gestión de inventario desarrollado en PHP con arquitectura MVC (Modelo-Vista-Controlador), base de datos MySQL y Bootstrap 5 para la interfaz.

---

## Tabla de Contenidos

1. [Estructura General](#estructura-general)
2. [Base de Datos](#base-de-datos)
3. [Configuración](#configuración)
4. [Punto de Entrada Público](#punto-de-entrada-público)
5. [Controladores](#controladores)
6. [Modelos](#modelos)
7. [Vistas](#vistas)
8. [Flujo de Autenticación](#flujo-de-autenticación)
9. [Roles del Sistema](#roles-del-sistema)

---

## Estructura General

```
/
├── config/          → Configuración de base de datos
├── controllers/     → Lógica de negocio (controladores)
├── models/          → Acceso a datos (modelos)
├── views/           → Plantillas HTML/PHP
│   ├── dashboard/   → Vistas del panel de control
│   ├── layouts/     → Componentes reutilizables (header, sidebar, footer)
│   └── usuarios/    → Vista de login
├── public/          → Archivos accesibles directamente (index, desarrollador)
├── img/             → Imágenes del sistema y productos
└── slq/             → Script SQL de la base de datos
```

---

## Base de Datos

**Archivo:** `slq/inventario.sql`  
**Base de datos:** `inventory`  
**Motor:** MySQL 8.0 con InnoDB y charset utf8mb4

### Tablas

| Tabla | Descripción |
|---|---|
| `usuarios` | Usuarios del sistema con rol (`administrador` / `vendedor`) y estado activo/inactivo |
| `productos` | Catálogo de productos con stock, precio de venta, categoría e imagen |
| `categorias` | Categorías para clasificar productos |
| `ventas` | Encabezado de ventas (cliente, usuario, fecha, total) |
| `detalle_venta` | Líneas de cada venta (producto, cantidad, precio, subtotal) |
| `compras` | Encabezado de compras (proveedor, fecha, total) |
| `detalle_compra` | Líneas de cada compra (producto, cantidad, precio de compra, subtotal) |
| `clientes` | Clientes a quienes se les vende |
| `proveedores` | Proveedores de quienes se compra |

### Relaciones clave

- `productos` → `categorias` (FK `id_categoria`)
- `ventas` → `usuarios` (FK `id_usuario`) y `clientes` (FK `id_cliente`)
- `detalle_venta` → `ventas` y `productos` (CASCADE DELETE en ventas)
- `compras` → `proveedores` (FK `id_proveedor`)
- `detalle_compra` → `compras` y `productos` (CASCADE DELETE en compras)

### Usuarios de prueba incluidos

| Usuario | Correo | Contraseña | Rol |
|---|---|---|---|
| cristian | admin@gmail.com | admin123 | administrador |
| camilo | cristian@gmail.com | cris123 | vendedor |
| ramirez | ramirez@gmail.com | ramirez123 | vendedor |

> ⚠️ Las contraseñas se almacenan en texto plano. Se recomienda migrar a `password_hash()` en producción.

---

## Configuración

**Archivo:** `config/database.php`

Contiene la clase `Database` que gestiona la conexión a MySQL mediante PDO.

```
Host:     127.0.0.1
Puerto:   3306
BD:       inventory
Usuario:  root
Password: (vacío)
Charset:  utf8mb4
```

**Método principal:**
- `conectar()` — Crea y retorna una conexión PDO. Lanza excepción si falla. Configura `ERRMODE_EXCEPTION` para manejo de errores.

---

## Punto de Entrada Público

### `public/index.php`
Página de bienvenida del sistema. No requiere autenticación.

**Qué hace:**
- Muestra un navbar fijo con logo y botones de acceso (Login / Desarrollador).
- Presenta un carrusel de 3 imágenes con mensajes promocionales del sistema.
- Sección de características principales: Productos, Movimientos, Usuarios.
- Footer con información de contacto del desarrollador.
- Si el usuario acaba de cerrar sesión (`?logout=1`), muestra una alerta verde temporal de confirmación.
- Usa Bootstrap 5, Bootstrap Icons, AOS (animaciones al hacer scroll) y Google Fonts (Inter).

### `public/desarrollador.php`
Página de perfil del desarrollador. No requiere autenticación.

**Qué hace:**
- Muestra una tarjeta con la información del desarrollador: Cristian Camilo Ramírez Torres.
- Incluye avatar, nombre, rol, biografía y tecnologías usadas (PHP, JS, React, Python, MySQL, HTML/CSS).
- Botones de contacto: GitHub, Email, Instagram, WhatsApp.
- Fondo con imagen difuminada y overlay oscuro.

---

## Controladores

### `controllers/Authcontroller.php`
Maneja el inicio y cierre de sesión.

**Método `login()`:**
1. Solo acepta peticiones POST.
2. Valida que correo y contraseña no estén vacíos.
3. Busca el usuario en la BD por correo.
4. Compara la contraseña directamente (sin hash).
5. Verifica que el usuario esté activo (`activo = 1`).
6. Guarda el usuario en `$_SESSION['usuario']`.
7. Redirige según el rol:
   - `administrador` → `dashboardadmincontroller.php`
   - `vendedor` → `vendedorcontroller.php`
8. En caso de error, guarda un mensaje en `$_SESSION['alert']` y redirige al login.

**Método `logout()`:**
1. Limpia el array `$_SESSION`.
2. Elimina la cookie de sesión.
3. Destruye la sesión con `session_destroy()`.
4. Redirige a `public/index.php?logout=1`.

---

### `controllers/dashboardadmincontroller.php`
Dashboard principal del administrador.

**Qué hace:**
- Verifica que el usuario sea `administrador`, si no redirige al login.
- Carga datos de sesión en variables globales (`$nombre`, `$rol`, `$correo`).
- Consulta estadísticas del sistema:
  - Total de usuarios, admins y vendedores.
  - Últimos 5 usuarios registrados.
  - Total de productos, productos con stock bajo, total de categorías.
  - Total de ventas y compras del mes actual.
- Carga la vista `views/dashboard/administrador.php`.

---

### `controllers/admincategoriascontroller.php`
CRUD completo de categorías. Solo accesible para administradores.

| Acción (`?accion=`) | Método | Descripción |
|---|---|---|
| `index` | GET | Lista todas las categorías con conteo de productos |
| `crear` | POST | Crea una nueva categoría. Valida nombre único |
| `editar` | POST | Actualiza nombre y descripción. Valida nombre único excluyendo la propia |
| `eliminar` | GET | Elimina la categoría. Bloquea si tiene productos asociados |

---

### `controllers/adminproductoscontroller.php`
CRUD completo de productos. Solo accesible para administradores.

| Acción | Método | Descripción |
|---|---|---|
| `index` | GET | Lista todos los productos con su categoría |
| `crear` | POST | Crea producto con validación de campos, precio máximo ($99,999,999.99) y subida de imagen (JPG/PNG/WEBP/GIF, máx 2MB) |
| `editar` | POST | Actualiza producto. Si se sube nueva imagen, elimina la anterior del servidor |
| `eliminar` | GET | Elimina producto y su imagen del servidor si existe |

**Manejo de imágenes:**
- Se guardan en `img/productos/` con nombre único generado por `uniqid('prod_')`.
- Al editar, si no se sube nueva imagen se conserva la anterior.
- Al eliminar el producto, se borra también el archivo de imagen.

---

### `controllers/adminusuariocontroller.php`
CRUD de usuarios. Solo accesible para administradores.

| Acción | Método | Descripción |
|---|---|---|
| `index` | GET | Lista todos los usuarios con conteo por rol |
| `crear` | POST | Crea usuario validando correo único |
| `editar` | POST | Actualiza datos. La contraseña solo se actualiza si se envía un valor |
| `toggleEstado` | GET | Activa o desactiva un usuario. No permite desactivar la propia cuenta |

---

### `controllers/adminventacontroller.php`
Gestión de ventas. Accesible para administradores y vendedores.

| Acción | Método | Descripción |
|---|---|---|
| `index` | GET | Lista todas las ventas con cliente y usuario |
| `crear` | POST | Registra venta con múltiples productos usando transacción. Descuenta stock automáticamente. Lanza excepción si stock insuficiente |
| `eliminar` | GET | Elimina venta y restaura el stock de todos los productos del detalle |
| `detalle` | GET | Retorna JSON con encabezado y líneas de la venta |
| `crearCliente` | POST | Crea un cliente nuevo vía AJAX, retorna JSON con ID y nombre |

---

### `controllers/admincomprascontroller.php`
Gestión de compras. Solo accesible para administradores.

| Acción | Método | Descripción |
|---|---|---|
| `index` | GET | Lista todas las compras con proveedor |
| `crear` | POST | Registra compra con múltiples productos usando transacción. Suma stock automáticamente |
| `eliminar` | GET | Elimina compra y revierte el stock (descuenta lo que se había sumado) |
| `detalle` | GET | Retorna JSON con encabezado y líneas de la compra |
| `crearProveedor` | POST | Crea un proveedor nuevo vía AJAX, retorna JSON con ID y nombre |

---

### `controllers/vendedorcontroller.php`
Dashboard principal del vendedor.

**Qué hace:**
- Verifica que el usuario sea `vendedor`.
- Carga datos de sesión en variables globales.
- Obtiene todos los productos disponibles y el total vendido hoy por el vendedor.
- Carga la vista `views/dashboard/vendedor.php`.

---

### `controllers/vendedorproductoscontroller.php`
Vista de productos para el vendedor (solo lectura).

**Qué hace:**
- Verifica rol `vendedor`.
- Obtiene todos los productos con su categoría.
- Calcula totales: total de productos, disponibles (stock > 0) y con stock bajo.
- Carga la vista `views/dashboard/vendedorproductos.php`.
- El vendedor **no puede** crear, editar ni eliminar productos.

---

### `controllers/vendedorventascontroller.php`
Gestión de ventas del vendedor. Solo ve y registra sus propias ventas.

| Acción | Método | Descripción |
|---|---|---|
| `index` | GET | Lista solo las ventas del vendedor autenticado |
| `crear` | POST | Registra venta igual que el admin, pero asociada al `id_usuario` de la sesión |
| `detalle` | GET | Retorna JSON del detalle, verificando que la venta pertenezca al vendedor |
| `crearCliente` | POST | Crea cliente vía AJAX |

---

### `controllers/vendedorcomprascontroller.php`
Gestión de compras para el vendedor.

| Acción | Método | Descripción |
|---|---|---|
| `index` | GET | Lista todas las compras (visibilidad global) |
| `crear` | POST | Registra compra con transacción y suma stock |
| `detalle` | GET | Retorna JSON del detalle de la compra |
| `crearProveedor` | POST | Crea proveedor vía AJAX |

---

### `controllers/verificarcorreo.php`
Endpoint AJAX para verificar si un correo ya existe en la BD.

**Qué hace:**
- Solo accesible para administradores autenticados.
- Recibe `?correo=` y opcionalmente `?excluir=id` (para excluir el usuario que se está editando).
- Retorna JSON: `{ "existe": true/false }`.
- Usado en el formulario de usuarios para validación en tiempo real.

---

## Modelos

### `models/usuario.php` — Clase `Usuario`

| Método | Descripción |
|---|---|
| `obtenerPorCorreo($correo)` | Busca usuario por correo (usado en login) |
| `obtenerPorId($id)` | Obtiene un usuario por su ID |
| `obtenerTodos()` | Retorna todos los usuarios ordenados por ID DESC |
| `obtenerUltimos($limite)` | Retorna los últimos N usuarios registrados |
| `contarTotal()` | Cuenta el total de usuarios |
| `contarPorRol($rol)` | Cuenta usuarios por rol |
| `existeCorreo($correo, $excluirId)` | Verifica si un correo ya está en uso |
| `crear($datos)` | Inserta nuevo usuario con `activo = 1` |
| `editar($id, $datos)` | Actualiza usuario. Si `contrasena` está en `$datos`, la actualiza también |
| `cambiarEstado($id, $activo)` | Cambia el campo `activo` (0 o 1) |

---

### `models/Dashboard.php` — Clase `Dashboard`

Provee estadísticas para el dashboard del administrador.

| Método | Descripción |
|---|---|
| `contarProductos()` | Total de productos en el catálogo |
| `contarStockBajo()` | Productos donde `stock_actual <= stock_minimo` |
| `contarCategorias()` | Total de categorías |
| `totalVentasMes()` | Suma de ventas del mes y año actual |
| `totalComprasMes()` | Suma de compras del mes y año actual |

---

### `models/categorias.php` — Clase `Categoria`

| Método | Descripción |
|---|---|
| `obtenerTodas()` | Lista categorías con conteo de productos asociados (LEFT JOIN) |
| `obtenerPorId($id)` | Obtiene una categoría por ID |
| `contarTotal()` | Total de categorías |
| `existeNombre($nombre, $excluirId)` | Verifica nombre duplicado |
| `crear($datos)` | Inserta nueva categoría |
| `editar($id, $datos)` | Actualiza nombre y descripción |
| `eliminar($id)` | Elimina categoría por ID |
| `tieneProductos($id)` | Retorna `true` si la categoría tiene productos (para bloquear eliminación) |

---

### `models/productosadmin.php` — Clase `Producto`

| Método | Descripción |
|---|---|
| `obtenerTodos()` | Lista productos con nombre de categoría (LEFT JOIN) |
| `obtenerPorId($id)` | Obtiene producto con categoría por ID |
| `contarTotal()` | Total de productos |
| `contarStockBajo()` | Productos con stock bajo |
| `existeNombre($nombre, $excluirId)` | Verifica nombre duplicado |
| `crear($datos)` | Inserta nuevo producto |
| `editar($id, $datos)` | Actualiza todos los campos del producto |
| `eliminar($id)` | Elimina producto por ID |

---

### `models/adminventa.php` — Clase `Venta`

| Método | Descripción |
|---|---|
| `obtenerTodas()` | Lista ventas con nombre de cliente y usuario |
| `obtenerPorId($id)` | Venta con datos completos de cliente y usuario |
| `obtenerDetalle($idVenta)` | Líneas de la venta con nombre e imagen del producto |
| `contarTotal()` | Total de ventas |
| `totalMes()` | Suma de ventas del mes actual |
| `crear($datos)` | Inserta encabezado de venta, retorna el ID generado |
| `insertarDetalle($datos)` | Inserta una línea de detalle |
| `actualizarTotal($idVenta, $total)` | Actualiza el total de la venta |
| `eliminar($id)` | Elimina venta (el detalle se borra en cascada) |
| `descontarStock($idProducto, $cantidad)` | Descuenta stock solo si hay suficiente. Retorna `false` si no hay stock |
| `restaurarStock($idVenta)` | Suma de vuelta el stock de todos los productos del detalle |
| `obtenerClientes()` | Lista todos los clientes |
| `crearCliente($datos)` | Inserta nuevo cliente, retorna ID |
| `obtenerProductosDisponibles()` | Productos con `stock_actual > 0` |

---

### `models/admincompra.php` — Clase `Compra`

| Método | Descripción |
|---|---|
| `obtenerTodas()` | Lista compras con nombre de proveedor |
| `obtenerPorId($id)` | Compra con datos del proveedor |
| `obtenerDetalle($idCompra)` | Líneas de la compra con nombre del producto |
| `contarTotal()` | Total de compras |
| `totalMes()` | Suma de compras del mes actual |
| `crear($datos)` | Inserta encabezado de compra, retorna ID |
| `insertarDetalle($datos)` | Inserta línea de detalle de compra |
| `actualizarTotal($idCompra, $total)` | Actualiza el total de la compra |
| `eliminar($id)` | Elimina compra (detalle en cascada) |
| `revertirStock($idCompra)` | Descuenta el stock que se había sumado al registrar la compra |
| `sumarStock($idProducto, $cantidad)` | Suma stock al registrar una compra |
| `obtenerProveedores()` | Lista todos los proveedores |
| `crearProveedor($datos)` | Inserta nuevo proveedor, retorna ID |
| `obtenerProductos()` | Lista todos los productos (para seleccionar en compra) |

---

### `models/vendedorproducto.php` — Clase `VendedorProducto`

Versión de solo lectura del catálogo de productos para el vendedor.

| Método | Descripción |
|---|---|
| `obtenerTodos()` | Lista todos los productos con categoría, ordenados por nombre |
| `contarTotal()` | Total de productos |
| `contarDisponibles()` | Productos con `stock_actual > 0` |
| `contarStockBajo()` | Productos con stock bajo |

---

### `models/vendedorventa.php` — Clase `VendedorVenta`

Gestión de ventas filtrada por el vendedor autenticado.

| Método | Descripción |
|---|---|
| `obtenerPorVendedor($idUsuario)` | Solo las ventas del vendedor actual |
| `obtenerDetalle($idVenta)` | Líneas de la venta |
| `obtenerPorId($idVenta, $idUsuario)` | Venta por ID verificando que pertenezca al vendedor |
| `totalHoy($idUsuario)` | Total vendido hoy por el vendedor |
| `totalMes($idUsuario)` | Total vendido este mes por el vendedor |
| `contarPorVendedor($idUsuario)` | Número de ventas del vendedor |
| `crear($datos)` | Inserta venta, retorna ID |
| `insertarDetalle($datos)` | Inserta línea de detalle |
| `actualizarTotal($idVenta, $total)` | Actualiza total |
| `descontarStock($idProducto, $cantidad)` | Descuenta stock con validación |
| `obtenerClientes()` | Lista clientes |
| `crearCliente($datos)` | Crea cliente, retorna ID |
| `obtenerProductosDisponibles()` | Productos con stock disponible |

---

### `models/vendedorcompra.php` — Clase `VendedorCompra`

Gestión de compras para el rol vendedor (misma lógica que `admincompra.php` pero sin eliminación).

| Método | Descripción |
|---|---|
| `obtenerTodas()` | Lista todas las compras con proveedor |
| `obtenerDetalle($idCompra)` | Líneas de la compra |
| `obtenerPorId($id)` | Compra con datos del proveedor |
| `contarTotal()` | Total de compras |
| `totalMes()` | Suma del mes |
| `contarProveedores()` | Total de proveedores registrados |
| `crear($datos)` | Inserta compra, retorna ID |
| `insertarDetalle($datos)` | Inserta línea de detalle |
| `actualizarTotal($idCompra, $total)` | Actualiza total |
| `sumarStock($idProducto, $cantidad)` | Suma stock al registrar compra |
| `obtenerProveedores()` | Lista proveedores |
| `crearProveedor($datos)` | Crea proveedor, retorna ID |
| `obtenerProductos()` | Lista productos para seleccionar en compra |

---

## Vistas

### Layouts (componentes reutilizables)

| Archivo | Descripción |
|---|---|
| `views/layouts/header.php` | Barra superior del dashboard con nombre de usuario y botón de logout |
| `views/layouts/sidebar.php` | Menú lateral de navegación. Muestra opciones según el rol del usuario |
| `views/layouts/footer.php` | Pie de página del dashboard |
| `views/layouts/main.php` | Plantilla base que incluye header, sidebar y footer |

### Vistas de usuario

| Archivo | Descripción |
|---|---|
| `views/usuarios/login.php` | Formulario de inicio de sesión. Muestra alertas de error/éxito desde `$_SESSION['alert']` |

### Vistas del Dashboard — Administrador

| Archivo | Descripción |
|---|---|
| `views/dashboard/administrador.php` | Dashboard principal: tarjetas con estadísticas (usuarios, productos, ventas, compras) y tabla de últimos usuarios |
| `views/dashboard/adminusuarios.php` | Tabla de usuarios con botones de crear, editar, activar/desactivar |
| `views/dashboard/adminproductos.php` | Tabla de productos con imagen, stock, precio. Modales para crear y editar con subida de imagen |
| `views/dashboard/admincategorias.php` | Tabla de categorías con conteo de productos. Modales para crear y editar |
| `views/dashboard/adminventas.php` | Tabla de ventas. Modal para registrar venta con múltiples productos dinámicos. Modal de detalle vía AJAX |
| `views/dashboard/admincompras.php` | Tabla de compras. Modal para registrar compra con múltiples productos. Modal de detalle vía AJAX |

### Vistas del Dashboard — Vendedor

| Archivo | Descripción |
|---|---|
| `views/dashboard/vendedor.php` | Dashboard del vendedor: productos disponibles y total vendido hoy |
| `views/dashboard/vendedorproductos.php` | Vista de solo lectura del catálogo de productos |
| `views/dashboard/vendedorventas.php` | Tabla de ventas propias del vendedor. Puede registrar nuevas ventas |
| `views/dashboard/vendedorcompras.php` | Tabla de compras. El vendedor puede registrar compras y crear proveedores |

---

## Flujo de Autenticación

```
Usuario → public/index.php
         → views/usuarios/login.php (formulario)
         → POST → controllers/Authcontroller.php
                  ├── rol = administrador → controllers/dashboardadmincontroller.php
                  └── rol = vendedor      → controllers/vendedorcontroller.php

Logout → controllers/Authcontroller.php?accion=logout
       → Destruye sesión → public/index.php?logout=1
```

---

## Roles del Sistema

### Administrador
- Acceso completo al sistema.
- Puede gestionar usuarios (crear, editar, activar/desactivar).
- CRUD completo de productos, categorías, ventas y compras.
- Ve todas las ventas de todos los vendedores.

### Vendedor
- No puede gestionar usuarios ni categorías.
- Solo puede **ver** el catálogo de productos (sin editar ni eliminar).
- Puede registrar ventas (solo ve las suyas propias).
- Puede registrar compras y crear proveedores.
- Su dashboard muestra estadísticas personales (ventas del día).

---

*Documentación generada automáticamente — Inventory System*
