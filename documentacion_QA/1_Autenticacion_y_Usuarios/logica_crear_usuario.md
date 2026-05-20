# Logica: Crear Usuario

## Vista principal
`views/dashboard/adminusuarios.php`

## Acceso requerido
Solo `administrador`

## Flujo de ejecucion

El administrador abre el modal de creacion de usuario en la vista `adminusuarios.php`. Al enviar el formulario, hace POST hacia `controllers/adminusuariocontroller.php?accion=crear`.

### Datos recibidos por POST
- `nombre` — nombre completo del usuario
- `correo` — correo electronico (debe ser unico)
- `password` — contrasena en texto plano
- `rol` — puede ser `administrador` o `vendedor`

### Validaciones
1. Verifica que `nombre`, `correo`, `password` y `rol` no esten vacios. Si falta alguno, muestra alerta "Completa todos los campos obligatorios."
2. Verifica con `existeCorreo()` del modelo que el correo no este registrado. Si ya existe, muestra alerta "El correo ya esta registrado."

### Insercion en base de datos
Llama al metodo `crear()` del modelo `Usuario` que ejecuta:
```sql
INSERT INTO usuarios (nombre, correo, contrasena, rol, activo)
VALUES (:nombre, :correo, :contrasena, :rol, 1)
```
El campo `activo` siempre se inserta como `1` (activo).

### Resultado
- Exito: alerta verde "Usuario creado correctamente."
- Error: alerta roja "No se pudo crear el usuario."
- Redirige a `adminusuariocontroller.php?accion=index`

## Archivos involucrados
- `controllers/adminusuariocontroller.php` — accion `crear`
- `models/usuario.php` — metodos `existeCorreo()` y `crear()`
- `config/database.php` — conexion PDO
- `views/dashboard/adminusuarios.php` — modal de creacion

## Tabla afectada
- `usuarios` (INSERT)
