# Logica: Eliminar Categoria

## Vista principal
`views/dashboard/admincategorias.php`

## Acceso requerido
Solo `administrador`

## Flujo de ejecucion

El administrador hace clic en el boton de eliminar de una categoria. El sistema redirige a `controllers/admincategoriascontroller.php?accion=eliminar&id=X`.

### Datos recibidos por GET
- `id` — ID de la categoria a eliminar

### Validaciones
1. Verifica que el `id` sea valido (mayor a 0). Si no, muestra alerta "ID invalido."
2. Verifica con `tieneProductos($id)` si la categoria tiene productos asociados en la tabla `productos`. Si tiene productos, muestra alerta "No se puede eliminar: la categoria tiene productos asociados." y cancela la operacion.

### Eliminacion en base de datos
Si pasa las validaciones, llama al metodo `eliminar()` del modelo `Categoria` que ejecuta:
```sql
DELETE FROM categorias WHERE id_categoria = :id
```

### Resultado
- Exito: alerta verde "Categoria eliminada correctamente."
- Error: alerta roja "No se pudo eliminar la categoria."
- Redirige a `admincategoriascontroller.php?accion=index`

## Nota importante
La eliminacion es permanente. No existe papelera de reciclaje. Si la categoria tiene productos asociados, la eliminacion es bloqueada para mantener la integridad referencial.

## Archivos involucrados
- `controllers/admincategoriascontroller.php` — accion `eliminar`
- `models/categorias.php` — metodos `tieneProductos()` y `eliminar()`
- `config/database.php` — conexion PDO
- `views/dashboard/admincategorias.php` — boton de eliminar

## Tabla afectada
- `categorias` (DELETE)
