# Logica: Editar Producto

## Vista principal
`views/dashboard/adminproductos.php`

## Acceso requerido
Solo `administrador`

## Flujo de ejecucion

El administrador hace clic en el boton de editar de un producto. El JavaScript carga los datos actuales del producto en el modal de edicion. Al enviar el formulario (con `enctype="multipart/form-data"`), hace POST hacia `controllers/adminproductoscontroller.php?accion=editar`.

### Datos recibidos por POST
- `id_producto` — ID del producto a editar
- `nombre` — nombre del producto (obligatorio)
- `id_categoria` — ID de la categoria (obligatorio)
- `precio_venta` — precio de venta (obligatorio, mayor a 0)
- `marca` — marca (opcional)
- `stock_actual` — stock actual (opcional)
- `stock_minimo` — stock minimo (opcional)
- `descripcion` — descripcion (opcional)
- `imagen_actual` — nombre del archivo de imagen actual (campo oculto)
- `imagen` — nuevo archivo de imagen (opcional, via `$_FILES`)

### Validaciones
1. Verifica que `id_producto`, `nombre`, `id_categoria` y `precio_venta` sean validos.
2. Verifica que `precio_venta` no supere `$99,999,999.99`.
3. Si se sube nueva imagen:
   - Verifica extension permitida: `jpg`, `jpeg`, `png`, `webp`, `gif`.
   - Verifica tamano maximo de 2MB.
   - Genera nombre unico con `uniqid('prod_')`.
   - Guarda en `img/productos/`.
   - Elimina la imagen anterior del servidor si existia.
4. Si no se sube nueva imagen, conserva el nombre de imagen actual (`imagen_actual`).

### Actualizacion en base de datos
Llama al metodo `editar()` del modelo `Producto` que ejecuta:
```sql
UPDATE productos
SET id_categoria=:id_categoria, nombre=:nombre, marca=:marca,
    stock_actual=:stock_actual, stock_minimo=:stock_minimo,
    precio_venta=:precio_venta, descripcion=:descripcion, imagen=:imagen
WHERE id_producto=:id
```

### Resultado
- Exito: alerta verde "Producto actualizado correctamente."
- Error: alerta roja con descripcion del problema.
- Redirige a `adminproductoscontroller.php?accion=index`

## Archivos involucrados
- `controllers/adminproductoscontroller.php` — accion `editar`
- `models/productosadmin.php` — metodo `editar()`
- `models/categorias.php` — para cargar el select de categorias
- `config/database.php` — conexion PDO
- `views/dashboard/adminproductos.php` — modal de edicion
- `img/productos/` — carpeta de imagenes

## Tabla afectada
- `productos` (UPDATE)
