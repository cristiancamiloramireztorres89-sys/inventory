# Logica: Editar Usuario

## Vista principal
`views/dashboard/adminusuarios.php`

## Acceso requerido
Solo `administrador`

## Flujo de ejecucion

El administrador hace clic en el boton de editar de un usuario en la tabla. El JavaScript de la pagina carga los datos actuales del usuario en el modal de edicion. Al enviar el formulario, hace POST hacia `controllers/adminusuariocontroller.php?accion=editar`.

### Datos recibidos por POST
- `id_usuario` — ID del usuario a editar
- `nombre` — nombre completo
- `correo` — correo electronico
- `rol` — `administrador` o `vendedor`
- `password` — nueva contrasena (opcional, si se deja vacio no se cambia)

### Validaciones
1. Verifica que `id_usuario`, `nombre`, `correo` y `rol` no esten vacios o sean invalidos.
2. Verifica con `existeCorreo($correo, $id)` que el correo no este en uso por otro usuario diferente al que se esta editando.

### Actualizacion en base de datos
Llama al metodo `editar()` del modelo `Usuario`:
- Si se proporciona nueva contrasena:
  ```sql
  UPDATE usuarios SET nombre=:nombre, correo=:correo, contrasena=:contrasena, rol=:rol WHERE id_usuario=:id
  ```
- Si no se proporciona contrasena:
  ```sql
  UPDATE usuarios SET nombre=:nombre, correo=:correo, rol=:rol WHERE id_usuario=:id
  ```

### Resultado
- Exito: alerta verde "Usuario actualizado correctamente."
- Error: alerta roja "No se pudo actualizar el usuario."
- Redirige a `adminusuariocontroller.php?accion=index`

## Archivos involucrados
- `controllers/adminusuariocontroller.php` — accion `editar`
- `models/usuario.php` — metodos `existeCorreo()` y `editar()`
- `config/database.php` — conexion PDO
- `views/dashboard/adminusuarios.php` — modal de edicion

## Tabla afectada
- `usuarios` (UPDATE)
