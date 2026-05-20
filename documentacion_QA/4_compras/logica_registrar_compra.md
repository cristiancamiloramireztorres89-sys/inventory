# Logica: Registrar Compra

## Vista principal
- Administrador: `views/dashboard/admincompras.php`
- Vendedor: `views/dashboard/vendedorcompras.php`

## Acceso requerido
- `administrador` → usa `controllers/admincomprascontroller.php`
- `vendedor` → usa `controllers/vendedorcomprascontroller.php`

## Flujo de ejecucion

El usuario selecciona un proveedor y agrega uno o mas productos con sus cantidades y precios de compra en el formulario. Al enviar, hace POST hacia el controlador correspondiente con `accion=crear`.

### Datos recibidos por POST
- `id_proveedor` — ID del proveedor (obligatorio)
- `productos[]` — array de IDs de productos seleccionados
- `cantidades[]` — array de cantidades por producto
- `precios[]` — array de precios de compra por producto

### Validaciones
1. Verifica que `id_proveedor` sea valido y que el array `productos` no este vacio. Si falla, muestra alerta "Selecciona un proveedor y al menos un producto."
2. Por cada linea de producto: verifica que `id_producto > 0`, `cantidad > 0` y `precio_compra > 0`. Las lineas invalidas se omiten.

### Proceso con transaccion PDO
El registro usa `$db->beginTransaction()` para garantizar integridad:

1. Crea el encabezado de la compra con total `0` usando `crear(['id_proveedor' => ..., 'total' => 0])`:
   ```sql
   INSERT INTO compras (id_proveedor, total) VALUES (:id_proveedor, 0)
   ```
   Retorna el `id_compra` generado.

2. Por cada producto en el array:
   - Calcula `subtotal = precio_compra * cantidad`
   - Acumula en `totalCompra`
   - Inserta en `detalle_compra` con `insertarDetalle()`:
     ```sql
     INSERT INTO detalle_compra (id_compra, id_producto, cantidad, precio_compra, subtotal)
     VALUES (:id_compra, :id_producto, :cantidad, :precio_compra, :subtotal)
     ```
   - Suma el stock del producto con `sumarStock($idProducto, $cantidad)`:
     ```sql
     UPDATE productos SET stock_actual = stock_actual + :cantidad WHERE id_producto = :id
     ```

3. Actualiza el total de la compra con `actualizarTotal($idCompra, $totalCompra)`:
   ```sql
   UPDATE compras SET total = :total WHERE id_compra = :id
   ```

4. Si todo sale bien: `$db->commit()`
5. Si ocurre algun error: `$db->rollBack()` y muestra el mensaje de error.

### Resultado
- Exito: alerta verde "Compra #X registrada por $Y."
- Error: alerta roja con el mensaje de la excepcion.
- Redirige al index del controlador correspondiente.

## Archivos involucrados
- `controllers/admincomprascontroller.php` o `vendedorcomprascontroller.php` — accion `crear`
- `models/admincompra.php` o `models/vendedorcompra.php` — metodos `crear()`, `insertarDetalle()`, `sumarStock()`, `actualizarTotal()`
- `config/database.php` — conexion PDO con soporte de transacciones
- `views/dashboard/admincompras.php` o `vendedorcompras.php` — formulario de compra

## Tablas afectadas
- `compras` (INSERT + UPDATE del total)
- `detalle_compra` (INSERT por cada producto)
- `productos` (UPDATE del campo `stock_actual`)
