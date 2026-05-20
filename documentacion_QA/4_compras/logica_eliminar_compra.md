# Logica: Eliminar Compra

## Vista principal
- Administrador: `views/dashboard/admincompras.php`

## Acceso requerido
Solo `administrador` (el vendedor no puede eliminar compras)

## Flujo de ejecucion

El administrador hace clic en el boton de eliminar de una compra. El sistema redirige a `controllers/admincomprascontroller.php?accion=eliminar&id=X`.

### Datos recibidos por GET
- `id` — ID de la compra a eliminar

### Validaciones
1. Verifica que el `id` sea valido (mayor a 0). Si no, muestra alerta "ID invalido."

### Proceso de eliminacion
1. Llama a `revertirStock($id)` que recorre todos los registros de `detalle_compra` para esa compra y resta las cantidades del stock de cada producto:
   ```sql
   UPDATE productos SET stock_actual = stock_actual - :cantidad WHERE id_producto = :id
   ```
2. Llama a `eliminar($id)` que elimina el encabezado de la compra:
   ```sql
   DELETE FROM compras WHERE id_compra = :id
   ```
   Los registros de `detalle_compra` se eliminan automaticamente por la restriccion `ON DELETE CASCADE` definida en la base de datos.

### Resultado
- Exito: alerta verde "Compra eliminada y stock revertido."
- Error: alerta roja "No se pudo eliminar la compra."
- Redirige a `admincomprascontroller.php?accion=index`

## Nota importante
Al eliminar una compra, el stock de todos los productos involucrados se reduce automaticamente para mantener la consistencia del inventario. Esta operacion es permanente e irreversible.

## Archivos involucrados
- `controllers/admincomprascontroller.php` — accion `eliminar`
- `models/admincompra.php` — metodos `revertirStock()` y `eliminar()`
- `config/database.php` — conexion PDO
- `views/dashboard/admincompras.php` — boton de eliminar

## Tablas afectadas
- `compras` (DELETE)
- `detalle_compra` (DELETE en cascada automatica)
- `productos` (UPDATE del campo `stock_actual` — reduccion)
