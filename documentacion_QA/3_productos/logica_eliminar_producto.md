# Logica: Eliminar Producto

## Vista principal
`views/dashboard/adminproductos.php`

## Acceso requerido
Solo `administrador`

## Flujo de ejecucion

El administrador hace clic en el boton de eliminar de un producto. El sistema redirige a `controllers/adminproductoscontroller.php?accion=eliminar&id=X`.

### Datos recibidos por GET
- `id` — ID del producto a eliminar

### Validaciones
1. Verifica que el `id` sea valido (mayor a 0). Si no, muestra alerta "ID invalido."
2. Verifica con `tieneRegistrosAsociados($id)` si el producto tiene registros en `detalle_compra` o `detalle_venta`. Si tiene registros, muestra alerta "No se puede eliminar el producto porque tiene compras o ventas registradas." y cancela la operacion.

### Proceso de eliminacion
1. Obtiene el producto con `obtenerPorId($id)` para recuperar el nombre de la imagen.
2. Si el producto tiene imagen, elimina el archivo fisico de `img/productos/` con `unlink()`.
3. Llama al metodo `eliminar()` del modelo que ejecuta:
   ```sql
   DELETE FROM productos WHERE id_producto = :id
   ```

### Resultado
- Exito: alerta verde "Producto eliminado correctamente."
- Error: alerta roja "No se pudo eliminar el producto."
- Redirige a `adminproductoscontroller.php?accion=index`

## Nota importante
La eliminacion es permanente. El sistema bloquea la eliminacion si el producto tiene historial de compras o ventas para preservar la integridad de los registros contables. Si se necesita "ocultar" un producto sin eliminarlo, se podria implementar un campo `activo` en la tabla `productos`.

## Archivos involucrados
- `controllers/adminproductoscontroller.php` — accion `eliminar`
- `models/productosadmin.php` — metodos `tieneRegistrosAsociados()`, `obtenerPorId()` y `eliminar()`
- `config/database.php` — conexion PDO
- `views/dashboard/adminproductos.php` — boton de eliminar
- `img/productos/` — carpeta de imagenes (se elimina el archivo)

## Tablas afectadas
- `productos` (DELETE)
