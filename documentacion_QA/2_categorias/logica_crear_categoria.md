# Logica: Crear Categoria

## Vista principal
`views/dashboard/admincategorias.php`

## Acceso requerido
Solo `administrador`

## Flujo de ejecucion

El administrador abre el modal de creacion de categoria en la vista `admincategorias.php`. Al enviar el formulario, hace POST hacia `controllers/admincategoriascontroller.php?accion=crear`.

### Datos recibidos por POST
- `nombre` — nombre de la categoria (obligatorio, debe ser unico)
- `descripcion` — descripcion opcional

### Validaciones
1. Verifica que `nombre` no este vacio. Si falta, muestra alerta "El nombre es obligatorio."
2. Verifica con `existeNombre($nombre)` del modelo que no exista otra categoria con el mismo nombre. Si ya existe, muestra alerta "Ya existe una categoria con ese nombre."

### Insercion en base de datos
Llama al metodo `crear()` del modelo `Categoria` que ejecuta:
```sql
INSERT INTO categorias (nombre, descripcion) VALUES (:nombre, :descripcion)
```

### Resultado
- Exito: alerta verde "Categoria creada correctamente."
- Error: alerta roja "No se pudo crear la categoria."
- Redirige a `admincategoriascontroller.php?accion=index`

## Archivos involucrados
- `controllers/admincategoriascontroller.php` — accion `crear`
- `models/categorias.php` — metodos `existeNombre()` y `crear()`
- `config/database.php` — conexion PDO
- `views/dashboard/admincategorias.php` — modal de creacion

## Tabla afectada
- `categorias` (INSERT)
