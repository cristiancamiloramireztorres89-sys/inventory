# Logica: Ver Detalle de Compra

## Vista principal
- Administrador: `views/dashboard/admincompras.php`
- Vendedor: `views/dashboard/vendedorcompras.php`

## Acceso requerido
- `administrador` → usa `controllers/admincomprascontroller.php`
- `vendedor` → usa `controllers/vendedorcomprascontroller.php`

## Flujo de ejecucion

El usuario hace clic en el boton de ver detalle de una compra. El JavaScript de la pagina hace una peticion AJAX (fetch) a `controllers/admincomprascontroller.php?accion=detalle&id=X` (o el equivalente del vendedor).

### Datos recibidos por GET
- `id` — ID de la compra a consultar

### Proceso
1. Obtiene el encabezado de la compra con `obtenerPorId($id)`:
   ```sql
   SELECT c.*, p.nombre AS proveedor_nombre FROM compras c
   JOIN proveedores p ON c.id_proveedor = p.id_proveedor
   WHERE c.id_compra = :id
   ```
2. Obtiene las lineas del detalle con `obtenerDetalle($id)`:
   ```sql
   SELECT dc.*, pr.nombre AS producto_nombre FROM detalle_compra dc
   JOIN productos pr ON dc.id_producto = pr.id_producto
   WHERE dc.id_compra = :id
   ```
3. Retorna ambos resultados como JSON:
   ```json
   { "compra": {...}, "detalle": [...] }
   ```

### Respuesta
El controlador establece el header `Content-Type: application/json` y hace `echo json_encode(...)` seguido de `exit`.

El JavaScript de la vista recibe el JSON y renderiza el contenido en un modal de detalle mostrando: proveedor, fecha, productos comprados, cantidades, precios unitarios, subtotales y total.

## Archivos involucrados
- `controllers/admincomprascontroller.php` o `vendedorcomprascontroller.php` — accion `detalle`
- `models/admincompra.php` o `models/vendedorcompra.php` — metodos `obtenerPorId()` y `obtenerDetalle()`
- `config/database.php` — conexion PDO
- `views/dashboard/admincompras.php` o `vendedorcompras.php` — modal de detalle y fetch JS

## Tablas consultadas
- `compras` (SELECT)
- `detalle_compra` (SELECT)
- `proveedores` (JOIN)
- `productos` (JOIN)
