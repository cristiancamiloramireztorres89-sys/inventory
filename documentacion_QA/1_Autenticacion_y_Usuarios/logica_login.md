# Logica: Login

## Vista principal
`views/usuarios/login.php`

## Flujo de ejecucion

El usuario ingresa su correo y contrasena en el formulario de login. Al enviar, el formulario hace POST hacia `controllers/Authcontroller.php`.

El controlador instancia la clase `Authcontroller` y llama al metodo `login()`.

### Validaciones
1. Verifica que los campos `correo` y `password` no esten vacios. Si alguno falta, guarda alerta de tipo `danger` en `$_SESSION['alert']` y redirige de vuelta al login.
2. Busca el usuario en la tabla `usuarios` por el campo `correo` usando el metodo `obtenerPorCorreo()` del modelo `Usuario`.
3. Si no encuentra el usuario, muestra error "Usuario no encontrado".
4. Compara la contrasena recibida con el campo `contrasena` de la base de datos (comparacion directa de texto plano).
5. Si la contrasena no coincide, muestra error "Contrasena incorrecta".
6. Verifica que el campo `activo` sea igual a 1. Si es 0, muestra error "Usuario desactivado".

### Login exitoso
- Guarda el registro completo del usuario en `$_SESSION['usuario']` (incluye id_usuario, nombre, correo, rol, activo).
- Segun el rol redirige:
  - `administrador` → `controllers/dashboardadmincontroller.php`
  - `vendedor` → `controllers/vendedorcontroller.php`

## Archivos involucrados
- `controllers/Authcontroller.php` — metodo `login()`
- `models/usuario.php` — metodo `obtenerPorCorreo($correo)`
- `config/database.php` — conexion PDO
- `views/usuarios/login.php` — formulario de acceso

## Tabla afectada
- `usuarios` (solo lectura: SELECT por correo)
