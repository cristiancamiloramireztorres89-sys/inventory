# Logica: Desactivar / Activar Usuario (Toggle Estado)

## Vista principal
`views/dashboard/adminusuarios.php`

## Acceso requerido
Solo `administrador`

## Flujo de ejecucion

El administrador hace clic en el boton de activar/desactivar de un usuario en la tabla. El boton apunta a `controllers/adminusuariocontroller.php?accion=toggleEstado&id=X`.

### Datos recibidos por GET
- `id` — ID del usuario a cambiar de estado

### Validaciones
1. Verifica que el `id` sea valido (mayor a 0).
2. Verifica que el administrador no este intentando desactivar su propia cuenta. Si el `id` coincide con `$_SESSION['usuario']['id_usuario']`, muestra alerta "No puedes desactivar tu propia cuenta."

### Logica de cambio de estado
1. Obtiene el usuario actual con `obtenerPorId($id)`.
2. Lee el valor actual del campo `activo`.
3. Si `activo` es `1`, el nuevo valor sera `0` (desactivar).
4. Si `activo` es `0`, el nuevo valor sera `1` (activar).
5. Llama al metodo `cambiarEstado($id, $nuevo)` del modelo que ejecuta:
   ```sql
   UPDATE usuarios SET activo=:activo WHERE id_usuario=:id
   ```

### Resultado
- Exito: alerta verde "Usuario activado/desactivado correctamente."
- Error: alerta roja "No se pudo cambiar el estado."
- Redirige a `adminusuariocontroller.php?accion=index`

## Nota importante
Un usuario con `activo = 0` no puede iniciar sesion. El sistema verifica este campo en el proceso de login.

## Archivos involucrados
- `controllers/adminusuariocontroller.php` — accion `toggleEstado`
- `models/usuario.php` — metodos `obtenerPorId()` y `cambiarEstado()`
- `config/database.php` — conexion PDO
- `views/dashboard/adminusuarios.php` — boton de toggle en la tabla

## Tabla afectada
- `usuarios` (UPDATE campo `activo`)
