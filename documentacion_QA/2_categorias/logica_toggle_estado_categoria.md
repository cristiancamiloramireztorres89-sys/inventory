# Logica: Toggle Estado Categoria

## Estado
Las categorias en este sistema **no tienen campo de estado activo/inactivo**. La tabla `categorias` solo tiene los campos `id_categoria`, `nombre` y `descripcion`.

## Alternativa disponible
Para controlar la visibilidad de una categoria, se puede:
- Eliminarla si no tiene productos asociados (ver `logica_eliminar_categoria.md`)
- Agregar un campo `activo TINYINT(1) DEFAULT 1` a la tabla `categorias` si se requiere esta funcionalidad en el futuro

## Para implementar toggle de estado en categorias se necesitaria
1. Agregar columna a la tabla:
   ```sql
   ALTER TABLE categorias ADD COLUMN activo TINYINT(1) DEFAULT 1;
   ```
2. Agregar metodo `cambiarEstado($id, $activo)` al modelo `Categoria`.
3. Agregar accion `toggleEstado` al controlador `admincategoriascontroller.php`.
4. Agregar boton de toggle en la vista `admincategorias.php`.
