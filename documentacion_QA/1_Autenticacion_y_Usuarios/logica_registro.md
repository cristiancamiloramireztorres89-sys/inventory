# Logica: Registro de Usuario (pendiente)

## Estado
Esta funcionalidad esta pendiente de implementacion en el sistema actual.

## Descripcion
El sistema actualmente no tiene un flujo de auto-registro publico. Los usuarios son creados directamente por el administrador desde el panel `adminusuarios.php`.

## Para implementarla se necesitaria
- Crear una vista `views/usuarios/registre.php` con el formulario de registro.
- Agregar la accion `registrar` en `controllers/Authcontroller.php` o en un controlador dedicado `registrecontroller.php` (el archivo ya existe en `controllers/`).
- El controlador deberia recibir por POST: `nombre`, `correo`, `password` y `rol`.
- Validar que los campos no esten vacios.
- Verificar con `existeCorreo()` que el correo no este registrado.
- Insertar en la tabla `usuarios` con `activo = 1`.
- Redirigir al login con mensaje de exito.

## Archivos relacionados
- `controllers/registrecontroller.php` — controlador existente (revisar implementacion)
- `models/usuario.php` — metodos `existeCorreo()` y `crear()`
- `views/usuarios/login.php` — destino tras registro exitoso
