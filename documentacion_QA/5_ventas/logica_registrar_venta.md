# Logica: Registrar Venta

## Vista principal
- Administrador: `views/dashboard/adminventas.php`
- Vendedor: `views/dashboard/vendedorventas.php`

## Acceso requerido
- `administrador` → usa `controllers/adminventacontroller.php`
- `vendedor` → usa `controllers/vendedorventascontroller.php`

## Flujo de ejecucion

El usuario selecciona un cliente y agrega uno o mas productos con sus cantidades. El precio de venta se toma automaticamente del campo `precio_venta` de la tabla `productos`. Al enviar, hace POST hacia el controlador correspondiente con `accion=crear`.

### Datos recibidos por POST
- `id_cliente` — ID del cliente (obligatorio)
- `productos[]` — array de IDs de productos seleccionados
- `cantidades[]` — array de cantidades por producto

### Validaciones
1. Verifica que `id_cliente` sea valido y que el array `productos` no este vacio. Si falla, muestra alerta "Selecciona un cliente y al menos un producto."
2. Por cada linea de producto: verifica que `id_producto > 0` y `cantidad > 0`. Las lineas invalidas se omiten.

### Proceso con transaccion PDO
El registro usa `$db->beginTransaction()` para garantizar integridad:

1. Crea el encabezado de la venta con total `0` usando `crear()`:
   ```sql
   INSERT INTO ventas (id_usuario, id_cliente, total) VALUES (:id_usuario, :id_cliente, 0)
   ```
   El `id_usuario` se toma de `$_SESSION['usuario']['id_usuario']`.
   Retorna el `id_venta` generado.

2. Por cada producto en el array:
   - Verifica y descuenta stock con `descontarStock($idProducto, $cantidad)`:
     ```sql
     UPDATE productos SET stock_actual = stock_actual - :cantidad
     WHERE id_producto = :id AND stock_actual >= :cantidad
     ```
     Si el UPDATE afecta 0 filas (stock insuficiente), lanza excepcion "Stock insuficiente para el producto #X."
   - Consulta el precio actual del producto:
     ```sql
     SELECT precio_venta FROM productos WHERE id_producto = :id
     ```
   - Calcula `subtotal = precio_unitario * cantidad`
   - Acumula en `totalVenta`
   - Inserta en `detalle_venta` con `insertarDetalle()`:
     ```sql
     INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal)
     VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario, :subtotal)
     ```

3. Actualiza el total de la venta con `actualizarTotal($idVenta, $totalVenta)`:
   ```sql
   UPDATE ventas SET total = :total WHERE id_venta = :id
   ```

4. Si todo sale bien: `$db->commit()`
5. Si ocurre algun error (incluyendo stock insuficiente): `$db->rollBack()` y muestra el mensaje de error.

### Resultado
- Exito: alerta verde "Venta #X registrada correctamente por $Y."
- Error: alerta roja con el mensaje de la excepcion.
- Redirige al index del controlador correspondiente.

## Archivos involucrados
- `controllers/adminventacontroller.php` o `vendedorventascontroller.php` — accion `crear`
- `models/adminventa.php` o `models/vendedorventa.php` — metodos `crear()`, `descontarStock()`, `insertarDetalle()`, `actualizarTotal()`
- `config/database.php` — conexion PDO con soporte de transacciones
- `views/dashboard/adminventas.php` o `vendedorventas.php` — formulario de venta

## Tablas afectadas
- `ventas` (INSERT + UPDATE del total)
- `detalle_venta` (INSERT por cada producto)
- `productos` (UPDATE del campo `stock_actual` — reduccion)
