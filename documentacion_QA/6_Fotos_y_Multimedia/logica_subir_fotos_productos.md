# Logica: Subir Fotos de Productos

## Estado
Implementado como parte del flujo de creacion y edicion de productos.

## Descripcion
La subida de imagenes de productos esta integrada directamente en las acciones `crear` y `editar` del controlador `adminproductoscontroller.php`. No existe un controlador separado para esta funcionalidad.

## Flujo de ejecucion

El formulario de creacion/edicion de producto debe incluir `enctype="multipart/form-data"` para permitir la subida de archivos.

### Campo del formulario
```html
<input type="file" name="imagen" accept=".jpg,.jpeg,.png,.webp,.gif">
```

### Validaciones en el controlador
1. Verifica que `$_FILES['imagen']['name']` no este vacio (la imagen es opcional).
2. Extrae la extension con `pathinfo()` y la convierte a minusculas.
3. Verifica que la extension sea una de las permitidas: `jpg`, `jpeg`, `png`, `webp`, `gif`. Si no, muestra alerta "Formato de imagen no permitido."
4. Verifica que el tamano del archivo no supere 2MB (`2 * 1024 * 1024` bytes). Si supera, muestra alerta "La imagen no puede superar 2MB."
5. Genera un nombre unico con `uniqid('prod_')` + extension (ejemplo: `prod_69f76adf41714.png`).
6. Mueve el archivo temporal a `img/productos/` con `move_uploaded_file()`.

### Al editar
- Si se sube una nueva imagen, se elimina la imagen anterior del servidor con `unlink()` antes de guardar la nueva.
- Si no se sube nueva imagen, se conserva el nombre de imagen actual (campo oculto `imagen_actual` en el formulario).

### Almacenamiento en base de datos
El nombre del archivo generado se guarda en el campo `imagen` de la tabla `productos`:
```sql
UPDATE productos SET imagen = :imagen WHERE id_producto = :id
```

### Mostrar la imagen en las vistas
```php
<img src="<?= BASE_URL ?>/img/productos/<?= htmlspecialchars($producto['imagen']) ?>" alt="...">
```

## Carpeta de almacenamiento
`img/productos/` — relativa a la raiz del proyecto

## Archivos involucrados
- `controllers/adminproductoscontroller.php` — acciones `crear` y `editar`
- `models/productosadmin.php` — metodos `crear()` y `editar()` (campo `imagen`)
- `img/productos/` — carpeta fisica de almacenamiento

## Tabla afectada
- `productos` (campo `imagen` VARCHAR(255))
