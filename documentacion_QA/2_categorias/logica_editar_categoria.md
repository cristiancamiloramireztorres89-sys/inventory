# Logica: Editar Categoria

## Vista principal
`views/dashboard/admincategorias.php`

## Acceso requerido
Solo `administrador`

## Flujo de ejecucion

El administrador hace clic en el boton de editar de una categoria. El JavaScript carga los datos actuales en el modal de edicion. Al enviar el formulario, hace POST hacia `controllers/admincategoriascontroller.php?accion=editar`.

### Datos recibidos por POST
- `id_categoria` — ID de la categoria a editar
- `nombre` — nuevo nombre (obligatorio)
- `descripcion` — nueva descripcion (opcional)

### Validaciones
1. Verifica que `id_categoria` sea valido y que `nombre` no este vacio.
2. Verifica con `existeNombre($nombre, $id)` que no exista otra categoria diferente con el mismo nombre.

### Actualizacion en base de datos
Llama al metodo `editar()` del modelo `Categoria` que ejecuta:
```sql
UPDATE categorias SET nombre = :nombre, descripcion = :descripcion WHERE id_categoria = :id
```

### Resultado
- Exito: alerta verde "Categoria actualizada correctamente."
- Error: alerta roja "No se pudo actualizar la categoria."
- Redirige a `admincategoriascontroller.php?accion=index`

## Archivos involucrados
- `controllers/admincategoriascontroller.php` — accion `editar`
- `models/categorias.php` — metodos `existeNombre()` y `editar()`
- `config/database.php` — conexion PDO
- `views/dashboard/admincategorias.php` — modal de edicion

## Tabla afectada
- `categorias` (UPDATE)
