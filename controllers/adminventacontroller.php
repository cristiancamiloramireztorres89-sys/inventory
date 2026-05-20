<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/adminventa.php';

// Solo administrador o vendedor
if (!isset($_SESSION['usuario']) ||
    !in_array($_SESSION['usuario']['rol'], ['administrador', 'vendedor'])) {
    header("Location: " . BASE_URL . "/views/usuarios/login.php");
    exit;
}

$database   = new Database();
$db         = $database->conectar();
$ventaModel = new Venta($db);

$accion = $_GET['accion'] ?? 'index';

switch ($accion) {

    // ── INDEX ────────────────────────────────────────────────
    case 'index':
        $ventas    = $ventaModel->obtenerTodas();
        $clientes  = $ventaModel->obtenerClientes();
        $productos = $ventaModel->obtenerProductosDisponibles();
        $total     = count($ventas);
        $totalMes  = $ventaModel->totalMes();
        $alert     = $_SESSION['alert'] ?? null;
        unset($_SESSION['alert']);

        require_once __DIR__ . '/../views/dashboard/adminventas.php';
        break;

    // ── CREAR VENTA ──────────────────────────────────────────
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: adminventacontroller.php?accion=index"); exit;
        }

        $idCliente  = (int)($_POST['id_cliente'] ?? 0);
        $productos  = $_POST['productos']  ?? [];   // array de id_producto
        $cantidades = $_POST['cantidades'] ?? [];   // array de cantidades

        if (!$idCliente || empty($productos)) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Selecciona un cliente y al menos un producto.'];
            header("Location: adminventacontroller.php?accion=index"); exit;
        }

        $idUsuario = (int)$_SESSION['usuario']['id_usuario'];

        // Iniciar transacción
        $db->beginTransaction();
        try {
            // Crear encabezado con total 0 (se actualiza al final)
            $idVenta = $ventaModel->crear([
                'id_usuario' => $idUsuario,
                'id_cliente' => $idCliente,
                'total'      => 0,
            ]);

            $totalVenta = 0;

            foreach ($productos as $i => $idProducto) {
                $idProducto = (int)$idProducto;
                $cantidad   = (int)($cantidades[$i] ?? 1);

                if ($idProducto <= 0 || $cantidad <= 0) continue;

                // Verificar y descontar stock
                if (!$ventaModel->descontarStock($idProducto, $cantidad)) {
                    throw new Exception("Stock insuficiente para el producto #$idProducto.");
                }

                // Obtener precio actual
                $stmtP = $db->prepare("SELECT precio_venta FROM productos WHERE id_producto = :id");
                $stmtP->execute([':id' => $idProducto]);
                $precioUnitario = (float)$stmtP->fetchColumn();
                $subtotal       = $precioUnitario * $cantidad;
                $totalVenta    += $subtotal;

                $ventaModel->insertarDetalle([
                    'id_venta'        => $idVenta,
                    'id_producto'     => $idProducto,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal'        => $subtotal,
                ]);
            }

            $ventaModel->actualizarTotal($idVenta, $totalVenta);
            $db->commit();

            $_SESSION['alert'] = ['type' => 'success', 'text' => "Venta #$idVenta registrada correctamente por $" . number_format($totalVenta, 2) . "."];

        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Error: ' . $e->getMessage()];
        }

        header("Location: adminventacontroller.php?accion=index"); exit;

    // ── ELIMINAR VENTA ───────────────────────────────────────
    case 'eliminar':
        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'ID inválido.'];
            header("Location: adminventacontroller.php?accion=index"); exit;
        }

        // Restaurar stock antes de eliminar
        $ventaModel->restaurarStock($id);
        $ok = $ventaModel->eliminar($id);

        $_SESSION['alert'] = $ok
            ? ['type' => 'success', 'text' => 'Venta eliminada y stock restaurado.']
            : ['type' => 'danger',  'text' => 'No se pudo eliminar la venta.'];

        header("Location: adminventacontroller.php?accion=index"); exit;

    // ── VER DETALLE ──────────────────────────────────────────
    case 'detalle':
        $id     = (int)($_GET['id'] ?? 0);
        $venta  = $ventaModel->obtenerPorId($id);
        $detalle = $ventaModel->obtenerDetalle($id);

        header('Content-Type: application/json');
        echo json_encode(['venta' => $venta, 'detalle' => $detalle]);
        exit;

    // ── CREAR CLIENTE (AJAX) ─────────────────────────────────
    case 'crearCliente':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: adminventacontroller.php?accion=index"); exit;
        }

        $nombre   = trim($_POST['nombre']   ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $correo   = trim($_POST['correo']   ?? '');

        if (!$nombre) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'El nombre es obligatorio.']);
            exit;
        }

        $idCliente = $ventaModel->crearCliente([
            'nombre'   => $nombre,
            'telefono' => $telefono ?: null,
            'correo'   => $correo   ?: null,
        ]);

        header('Content-Type: application/json');
        echo json_encode(['id' => $idCliente, 'nombre' => $nombre]);
        exit;

    default:
        header("Location: adminventacontroller.php?accion=index"); exit;
}
?>
