<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/productosadmin.php';
require_once __DIR__ . '/../models/categorias.php';

// Solo administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: " . BASE_URL . "/views/usuarios/login.php");
    exit;
}

$database       = new Database();
$db             = $database->conectar();
$productoModel  = new Producto($db);
$categoriaModel = new Categoria($db);

$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

    // ── INDEX ────────────────────────────────────────────────
    case 'index':
        $productos  = $productoModel->obtenerTodos();
        $categorias = $categoriaModel->obtenerTodas();
        $total      = count($productos);
        $stockBajo  = $productoModel->contarStockBajo();
        $alert      = $_SESSION['alert'] ?? null;
        unset($_SESSION['alert']);

        require_once __DIR__ . '/../views/dashboard/adminproductos.php';
        break;

    // ── CREAR ────────────────────────────────────────────────
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: adminproductoscontroller.php?accion=index"); exit;
        }

        $nombre      = trim($_POST['nombre']       ?? '');
        $idCategoria = (int)($_POST['id_categoria'] ?? 0);
        $precioVenta = (float)($_POST['precio_venta'] ?? 0);

        if (!$nombre || !$idCategoria || $precioVenta <= 0) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Completa los campos obligatorios.'];
            header("Location: adminproductoscontroller.php?accion=index"); exit;
        }

        if ($precioVenta > 99999999.99) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'El precio no puede superar $99,999,999.99.'];
            header("Location: adminproductoscontroller.php?accion=index"); exit;
        }

        // Manejo de imagen
        $nombreImagen = null;
        if (!empty($_FILES['imagen']['name'])) {
            $ext       = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            $permitidos = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            if (!in_array($ext, $permitidos)) {
                $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Formato de imagen no permitido. Usa JPG, PNG, WEBP o GIF.'];
                header("Location: adminproductoscontroller.php?accion=index"); exit;
            }

            if ($_FILES['imagen']['size'] > 2 * 1024 * 1024) {
                $_SESSION['alert'] = ['type' => 'danger', 'text' => 'La imagen no puede superar 2MB.'];
                header("Location: adminproductoscontroller.php?accion=index"); exit;
            }

            $nombreImagen = uniqid('prod_') . '.' . $ext;
            $destino      = __DIR__ . '/../img/productos/' . $nombreImagen;

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                $_SESSION['alert'] = ['type' => 'danger', 'text' => 'No se pudo guardar la imagen.'];
                header("Location: adminproductoscontroller.php?accion=index"); exit;
            }
        }

        $ok = $productoModel->crear([
            'id_categoria' => $idCategoria,
            'nombre'       => $nombre,
            'marca'        => trim($_POST['marca']        ?? ''),
            'stock_actual' => (int)($_POST['stock_actual'] ?? 0),
            'stock_minimo' => (int)($_POST['stock_minimo'] ?? 0),
            'precio_venta' => $precioVenta,
            'descripcion'  => trim($_POST['descripcion']  ?? ''),
            'imagen'       => $nombreImagen,
        ]);

        $_SESSION['alert'] = $ok
            ? ['type' => 'success', 'text' => 'Producto creado correctamente.']
            : ['type' => 'danger',  'text' => 'No se pudo crear el producto.'];

        header("Location: adminproductoscontroller.php?accion=index"); exit;

    // ── EDITAR ───────────────────────────────────────────────
    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: adminproductoscontroller.php?accion=index"); exit;
        }

        $id          = (int)($_POST['id_producto']   ?? 0);
        $nombre      = trim($_POST['nombre']          ?? '');
        $idCategoria = (int)($_POST['id_categoria']   ?? 0);
        $precioVenta = (float)($_POST['precio_venta'] ?? 0);

        if (!$id || !$nombre || !$idCategoria || $precioVenta <= 0) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Completa los campos obligatorios.'];
            header("Location: adminproductoscontroller.php?accion=index"); exit;
        }

        if ($precioVenta > 99999999.99) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'El precio no puede superar $99,999,999.99.'];
            header("Location: adminproductoscontroller.php?accion=index"); exit;
        }

        // Manejo de imagen — conservar la anterior si no se sube nueva
        $imagenActual = trim($_POST['imagen_actual'] ?? '');
        $nombreImagen = $imagenActual ?: null;

        if (!empty($_FILES['imagen']['name'])) {
            $ext        = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            $permitidos = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            if (!in_array($ext, $permitidos)) {
                $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Formato de imagen no permitido.'];
                header("Location: adminproductoscontroller.php?accion=index"); exit;
            }

            if ($_FILES['imagen']['size'] > 2 * 1024 * 1024) {
                $_SESSION['alert'] = ['type' => 'danger', 'text' => 'La imagen no puede superar 2MB.'];
                header("Location: adminproductoscontroller.php?accion=index"); exit;
            }

            $nombreImagen = uniqid('prod_') . '.' . $ext;
            $destino      = __DIR__ . '/../img/productos/' . $nombreImagen;

            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                $_SESSION['alert'] = ['type' => 'danger', 'text' => 'No se pudo guardar la imagen.'];
                header("Location: adminproductoscontroller.php?accion=index"); exit;
            }

            // Eliminar imagen anterior si existía
            if ($imagenActual) {
                $rutaAnterior = __DIR__ . '/../img/productos/' . $imagenActual;
                if (file_exists($rutaAnterior)) unlink($rutaAnterior);
            }
        }

        $ok = $productoModel->editar($id, [
            'id_categoria' => $idCategoria,
            'nombre'       => $nombre,
            'marca'        => trim($_POST['marca']        ?? ''),
            'stock_actual' => (int)($_POST['stock_actual'] ?? 0),
            'stock_minimo' => (int)($_POST['stock_minimo'] ?? 0),
            'precio_venta' => $precioVenta,
            'descripcion'  => trim($_POST['descripcion']  ?? ''),
            'imagen'       => $nombreImagen,
        ]);

        $_SESSION['alert'] = $ok
            ? ['type' => 'success', 'text' => 'Producto actualizado correctamente.']
            : ['type' => 'danger',  'text' => 'No se pudo actualizar el producto.'];

        header("Location: adminproductoscontroller.php?accion=index"); exit;

    // ── ELIMINAR ─────────────────────────────────────────────
    case 'eliminar':
        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'ID inválido.'];
            header("Location: adminproductoscontroller.php?accion=index"); exit;
        }

        // Bloquear si el producto tiene compras o ventas registradas
        if ($productoModel->tieneRegistrosAsociados($id)) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'No se puede eliminar el producto porque tiene compras o ventas registradas.'];
            header("Location: adminproductoscontroller.php?accion=index"); exit;
        }

        // Eliminar imagen del servidor si existe
        $producto = $productoModel->obtenerPorId($id);
        if ($producto && $producto['imagen']) {
            $ruta = __DIR__ . '/../img/productos/' . $producto['imagen'];
            if (file_exists($ruta)) unlink($ruta);
        }

        $ok = $productoModel->eliminar($id);

        $_SESSION['alert'] = $ok
            ? ['type' => 'success', 'text' => 'Producto eliminado correctamente.']
            : ['type' => 'danger',  'text' => 'No se pudo eliminar el producto.'];

        header("Location: adminproductoscontroller.php?accion=index"); exit;

    default:
        header("Location: adminproductoscontroller.php?accion=index"); exit;
}
?>
