# Logica: Logout

## Vista principal
Cualquier dashboard (administrador o vendedor)

## Flujo de ejecucion

El usuario hace clic en el enlace "Cerrar sesion" que apunta a `controllers/Authcontroller.php?accion=logout`.

El controlador detecta el parametro GET `accion=logout` e instancia `Authcontroller`, llamando al metodo `logout()`.

### Proceso de cierre de sesion
1. Limpia el array `$_SESSION` asignandolo a `[]`.
2. Si las cookies de sesion estan activas, elimina la cookie del navegador con `setcookie()` usando tiempo negativo.
3. Destruye la sesion con `session_destroy()`.
4. Redirige al usuario a `public/index.php?logout=1`.

No hay validaciones adicionales ni mensajes de confirmacion. El logout es instantaneo.

## Archivos involucrados
- `controllers/Authcontroller.php` — metodo `logout()`
- `public/index.php` — pagina de destino tras el logout

## Tabla afectada
- Ninguna (solo manejo de sesion PHP)
