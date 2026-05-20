# Documentacion QA - Sistema de Inventario

Esta documentacion esta organizada por modulos y funcionalidades del sistema. Cada archivo describe la logica de una funcionalidad especifica, el flujo de ejecucion, validaciones y archivos involucrados.

## Estructura de la documentacion

### 1. Autenticacion y Usuarios
Gestion de usuarios, autenticacion y sesiones.
- logica_login.md
- logica_logout.md
- logica_registro.md (pendiente)
- logica_crear_usuario.md
- logica_editar_usuario.md
- logica_desactivar_usuario.md
- logica_toggle_estado_usuario.md

### 2. Categorias
Gestion de categorias de productos.
- logica_crear_categoria.md
- logica_editar_categoria.md
- logica_eliminar_categoria.md

### 3. Productos
Gestion del catalogo de productos e inventario.
- logica_crear_producto.md
- logica_editar_producto.md
- logica_eliminar_producto.md

### 4. Compras
Registro de compras a proveedores y control de stock.
- logica_registrar_compra.md
- logica_eliminar_compra.md
- logica_ver_detalle_compra.md

### 5. Ventas
Registro de ventas a clientes y descuento de stock.
- logica_registrar_venta.md
- logica_eliminar_venta.md
- logica_detalle_venta.md

### 6. Fotos y Multimedia
Gestion de imagenes de productos.
- logica_subir_fotos_productos.md

---

## Arquitectura del sistema

El sistema sigue el patron Modelo-Vista-Controlador (MVC):
- **Modelos** (`models/`) — Interaccion con la base de datos via PDO
- **Vistas** (`views/`) — Interfaz de usuario con Bootstrap y SweetAlert2
- **Controladores** (`controllers/`) — Logica de negocio y validaciones

## Base de datos
- Motor: MySQL 8.0 / MariaDB
- Configuracion: `config/database.php`
- Script SQL: `slq/inventario.sql`
- Base de datos: `inventory`

## Tablas principales
| Tabla | Descripcion |
|---|---|
| `usuarios` | Cuentas de acceso al sistema |
| `categorias` | Categorias de productos |
| `productos` | Catalogo de productos con stock |
| `proveedores` | Proveedores de compras |
| `clientes` | Clientes de ventas |
| `compras` | Encabezado de compras |
| `detalle_compra` | Lineas de cada compra |
| `ventas` | Encabezado de ventas |
| `detalle_venta` | Lineas de cada venta |

## Sesiones
- Gestion de sesiones PHP nativas
- Almacenamiento de alertas en `$_SESSION['alert']`
- Informacion del usuario en `$_SESSION['usuario']` (id_usuario, nombre, correo, rol, activo)

## Roles del sistema
| Rol | Acceso |
|---|---|
| `administrador` | Acceso completo: usuarios, categorias, productos, compras, ventas |
| `vendedor` | Acceso a: productos (lectura), compras propias, ventas propias |

## Notas importantes
- Las contrasenas se almacenan en texto plano actualmente (se recomienda migrar a bcrypt)
- Las alertas se muestran usando SweetAlert2
- Los formularios usan metodo POST para envio de datos sensibles
- Las eliminaciones son permanentes sin papelera de reciclaje
- Las compras y ventas usan transacciones PDO para garantizar integridad
- Al eliminar una compra se revierte el stock; al eliminar una venta se restaura el stock
- Los productos con compras o ventas registradas no pueden eliminarse directamente
