# Logica: Crear Producto

## Vista principal
`views/dashboard/adminproductos.php`

## Acceso requerido
Solo `administrador`

## Flujo de ejecucion

El administrador abre el modal de creacion de producto. Al enviar el formulario (con `enctype="multipart/form-data"`), hace POST hacia `controllers/adminproductoscontroller.php?accion=crear`.

### Datos recibidos por POST
- `nombre` — nombre del producto (obligatorio)
- `id_categoria` — ID de la categoria (obligatorio)
- `precio_venta` — precio de venta en decimal (obligatorio, mayor a 0)
- `marca` — marca del producto (opcional)
- `stock_actual` — cantidad inicial en stock (opcional, default 0)
- `stock_minimo` — nivel minimo de alerta de stock (opcional, default 0)
- `descripcion` — descripcion del producto (opcional)
- `imagen` — archivo de imagen (opcional, via `$_FILES`)

### Validaciones
1. Verifica que `nombre`, `id_categoria` y `precio_venta` no esten vacios o sean invalidos.
2. Verifica que `precio_venta` no supere `$99,999,999.99`.
3. Si se sube imagen:
   - Verifica que la extension sea `jpg`, `jpeg`, `png`, `webp` o `gif`.
   - Verifica que el tamano no supere 2MB.
   - Genera nombre unico con `uniqid('prod_')` + extension.
   - Guarda el archivo en `img/productos/`.

### Insercion en base de datos
Llama al metodo `crear()` del modelo `Producto` que ejecuta:
```sql
INSERT INTO productos (id_categoria, nombre, marca, stock_actual, stock_minimo, precio_venta, descripcion, imagen)
VALUES (:id_categoria, :nombre, :marca, :stock_actual, :stock_minimo, :precio_venta, :descripcion, :imagen)
```

### Resultado
- Exito: alerta verde "Producto creado correctamente."
- Error: alerta roja con descripcion del problema.
- Redirige a `adminproductoscontroller.php?accion=index`

## Archivos involucrados
- `controllers/adminproductoscontroller.php` — accion `crear`
- `models/productosadmin.php` — metodo `crear()`
- `models/categorias.php` — para cargar el select de categorias
- `config/database.php` — conexion PDO
- `views/dashboard/adminproductos.php` — modal de creacion
- `img/productos/` — carpeta de imagenes

## Tabla afectada
- `productos` (INSERT)
