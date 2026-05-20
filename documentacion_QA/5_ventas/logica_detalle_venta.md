# Logica: Ver Detalle de Venta

## Vista principal
- Administrador: `views/dashboard/adminventas.php`
- Vendedor: `views/dashboard/vendedorventas.php`

## Acceso requerido
- `administrador` → usa `controllers/adminventacontroller.php`
- `vendedor` → usa `controllers/vendedorventascontroller.php`

## Flujo de ejecucion

El usuario hace clic en el boton de ver detalle de una venta. El JavaScript de la pagina hace una peticion AJAX (fetch) a `controllers/adminventacontroller.php?accion=detalle&id=X` (o el equivalente del vendedor).

### Datos recibidos por GET
- `id` — ID de la venta a consultar

### Proceso
1. Obtiene el encabezado de la venta con `obtenerPorId($id)`:
   - Para el administrador: busca por `id_venta` sin restriccion de usuario.
   - Para el vendedor: busca por `id_venta` y verifica que `id_usuario` coincida con el vendedor en sesion.
2. Obtiene las lineas del detalle con `obtenerDetalle($id)`:
   ```sql
   SELECT dv.*, p.nombre AS producto_nombre FROM detalle_venta dv
   JOIN productos p ON dv.id_producto = p.id_producto
   WHERE dv.id_venta = :id
   ```
3. Retorna ambos resultados como JSON:
   ```json
   { "venta": {...}, "detalle": [...] }
   ```

### Respuesta
El controlador establece el header `Content-Type: application/json` y hace `echo json_encode(...)` seguido de `exit`.

El JavaScript de la vista recibe el JSON y renderiza el contenido en un modal de detalle mostrando: cliente, vendedor, fecha, productos vendidos, cantidades, precios unitarios, subtotales y total.

## Archivos involucrados
- `controllers/adminventacontroller.php` o `vendedorventascontroller.php` — accion `detalle`
- `models/adminventa.php` o `models/vendedorventa.php` — metodos `obtenerPorId()` y `obtenerDetalle()`
- `config/database.php` — conexion PDO
- `views/dashboard/adminventas.php` o `vendedorventas.php` — modal de detalle y fetch JS

## Tablas consultadas
- `ventas` (SELECT)
- `detalle_venta` (SELECT)
- `clientes` (JOIN)
- `usuarios` (JOIN)
- `productos` (JOIN)
