# Logica: Eliminar Venta

## Vista principal
- Administrador: `views/dashboard/adminventas.php`

## Acceso requerido
Solo `administrador` (el vendedor no puede eliminar ventas)

## Flujo de ejecucion

El administrador hace clic en el boton de eliminar de una venta. El sistema redirige a `controllers/adminventacontroller.php?accion=eliminar&id=X`.

### Datos recibidos por GET
- `id` — ID de la venta a eliminar

### Validaciones
1. Verifica que el `id` sea valido (mayor a 0). Si no, muestra alerta "ID invalido."

### Proceso de eliminacion
1. Llama a `restaurarStock($id)` que recorre todos los registros de `detalle_venta` para esa venta y suma de vuelta las cantidades al stock de cada producto:
   ```sql
   UPDATE productos SET stock_actual = stock_actual + :cantidad WHERE id_producto = :id
   ```
2. Llama a `eliminar($id)` que elimina el encabezado de la venta:
   ```sql
   DELETE FROM ventas WHERE id_venta = :id
   ```
   Los registros de `detalle_venta` se eliminan automaticamente por la restriccion `ON DELETE CASCADE` definida en la base de datos.

### Resultado
- Exito: alerta verde "Venta eliminada y stock restaurado."
- Error: alerta roja "No se pudo eliminar la venta."
- Redirige a `adminventacontroller.php?accion=index`

## Nota importante
Al eliminar una venta, el stock de todos los productos involucrados se restaura automaticamente. Esta operacion es permanente e irreversible.

## Archivos involucrados
- `controllers/adminventacontroller.php` — accion `eliminar`
- `models/adminventa.php` — metodos `restaurarStock()` y `eliminar()`
- `config/database.php` — conexion PDO
- `views/dashboard/adminventas.php` — boton de eliminar

## Tablas afectadas
- `ventas` (DELETE)
- `detalle_venta` (DELETE en cascada automatica)
- `productos` (UPDATE del campo `stock_actual` — restauracion)
