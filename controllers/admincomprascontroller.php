<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/admincompra.php';

// Solo administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../views/usuarios/login.php");
    exit;
}

$database    = new Database();
$db          = $database->conectar();
$compraModel = new Compra($db);

$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

    // ── INDEX ────────────────────────────────────────────────
    case 'index':
        $compras     = $compraModel->obtenerTodas();
        $proveedores = $compraModel->obtenerProveedores();
        $productos   = $compraModel->obtenerProductos();
        $total       = count($compras);
        $totalMes    = $compraModel->totalMes();
        $alert       = $_SESSION['alert'] ?? null;
        unset($_SESSION['alert']);

        require_once __DIR__ . '/../views/dashboard/admincompras.php';
        break;

    // ── CREAR COMPRA ─────────────────────────────────────────
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: admincomprascontroller.php?accion=index"); exit;
        }

        $idProveedor = (int)($_POST['id_proveedor'] ?? 0);
        $productos   = $_POST['productos']    ?? [];
        $cantidades  = $_POST['cantidades']   ?? [];
        $precios     = $_POST['precios']      ?? [];

        if (!$idProveedor || empty($productos)) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Selecciona un proveedor y al menos un producto.'];
            header("Location: admincomprascontroller.php?accion=index"); exit;
        }

        $db->beginTransaction();
        try {
            $idCompra   = $compraModel->crear(['id_proveedor' => $idProveedor, 'total' => 0]);
            $totalCompra = 0;

            foreach ($productos as $i => $idProducto) {
                $idProducto    = (int)$idProducto;
                $cantidad      = (int)($cantidades[$i] ?? 1);
                $precioCompra  = (float)($precios[$i]   ?? 0);

                if ($idProducto <= 0 || $cantidad <= 0 || $precioCompra <= 0) continue;

                $subtotal     = $precioCompra * $cantidad;
                $totalCompra += $subtotal;

                $compraModel->insertarDetalle([
                    'id_compra'     => $idCompra,
                    'id_producto'   => $idProducto,
                    'cantidad'      => $cantidad,
                    'precio_compra' => $precioCompra,
                    'subtotal'      => $subtotal,
                ]);

                // Sumar stock
                $compraModel->sumarStock($idProducto, $cantidad);
            }

            $compraModel->actualizarTotal($idCompra, $totalCompra);
            $db->commit();

            $_SESSION['alert'] = ['type' => 'success', 'text' => "Compra #$idCompra registrada por $" . number_format($totalCompra, 2) . "."];

        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Error: ' . $e->getMessage()];
        }

        header("Location: admincomprascontroller.php?accion=index"); exit;

    // ── ELIMINAR ─────────────────────────────────────────────
    case 'eliminar':
        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'ID inválido.'];
            header("Location: admincomprascontroller.php?accion=index"); exit;
        }

        $compraModel->revertirStock($id);
        $ok = $compraModel->eliminar($id);

        $_SESSION['alert'] = $ok
            ? ['type' => 'success', 'text' => 'Compra eliminada y stock revertido.']
            : ['type' => 'danger',  'text' => 'No se pudo eliminar la compra.'];

        header("Location: admincomprascontroller.php?accion=index"); exit;

    // ── DETALLE (JSON) ───────────────────────────────────────
    case 'detalle':
        $id      = (int)($_GET['id'] ?? 0);
        $compra  = $compraModel->obtenerPorId($id);
        $detalle = $compraModel->obtenerDetalle($id);

        header('Content-Type: application/json');
        echo json_encode(['compra' => $compra, 'detalle' => $detalle]);
        exit;

    // ── CREAR PROVEEDOR (JSON) ───────────────────────────────
    case 'crearProveedor':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: admincomprascontroller.php?accion=index"); exit;
        }

        $nombre   = trim($_POST['nombre']   ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $correo   = trim($_POST['correo']   ?? '');

        if (!$nombre) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'El nombre es obligatorio.']);
            exit;
        }

        $idProveedor = $compraModel->crearProveedor([
            'nombre'   => $nombre,
            'telefono' => $telefono ?: null,
            'correo'   => $correo   ?: null,
        ]);

        header('Content-Type: application/json');
        echo json_encode(['id' => $idProveedor, 'nombre' => $nombre]);
        exit;

    default:
        header("Location: admincomprascontroller.php?accion=index"); exit;
}
?>
