<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/vendedorventa.php';

// Solo vendedor
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'vendedor') {
    header("Location: " . BASE_URL . "/views/usuarios/login.php");
    exit;
}

$database    = new Database();
$db          = $database->conectar();
$ventaModel  = new VendedorVenta($db);

$idUsuario = (int)$_SESSION['usuario']['id_usuario'];
$accion    = $_GET['accion'] ?? 'index';

switch ($accion) {

    // ── INDEX ────────────────────────────────────────────────
    case 'index':
        $ventas    = $ventaModel->obtenerPorVendedor($idUsuario);
        $clientes  = $ventaModel->obtenerClientes();
        $productos = $ventaModel->obtenerProductosDisponibles();
        $total     = count($ventas);
        $totalMes  = $ventaModel->totalMes($idUsuario);
        $alert     = $_SESSION['alert'] ?? null;
        unset($_SESSION['alert']);

        require_once __DIR__ . '/../views/dashboard/vendedorventas.php';
        break;

    // ── CREAR VENTA ──────────────────────────────────────────
    case 'crear':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: vendedorventascontroller.php?accion=index"); exit;
        }

        $idCliente  = (int)($_POST['id_cliente'] ?? 0);
        $productos  = $_POST['productos']  ?? [];
        $cantidades = $_POST['cantidades'] ?? [];

        if (!$idCliente || empty($productos)) {
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Selecciona un cliente y al menos un producto.'];
            header("Location: vendedorventascontroller.php?accion=index"); exit;
        }

        $db->beginTransaction();
        try {
            $idVenta    = $ventaModel->crear([
                'id_usuario' => $idUsuario,
                'id_cliente' => $idCliente,
                'total'      => 0,
            ]);

            $totalVenta = 0;

            foreach ($productos as $i => $idProducto) {
                $idProducto = (int)$idProducto;
                $cantidad   = (int)($cantidades[$i] ?? 1);

                if ($idProducto <= 0 || $cantidad <= 0) continue;

                if (!$ventaModel->descontarStock($idProducto, $cantidad)) {
                    throw new Exception("Stock insuficiente para el producto #$idProducto.");
                }

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

            $_SESSION['alert'] = ['type' => 'success', 'text' => "Venta #$idVenta registrada por $" . number_format($totalVenta, 2) . "."];

        } catch (Exception $e) {
            $db->rollBack();
            $_SESSION['alert'] = ['type' => 'danger', 'text' => 'Error: ' . $e->getMessage()];
        }

        header("Location: vendedorventascontroller.php?accion=index"); exit;

    // ── DETALLE (JSON) ───────────────────────────────────────
    case 'detalle':
        $id      = (int)($_GET['id'] ?? 0);
        $venta   = $ventaModel->obtenerPorId($id, $idUsuario);
        $detalle = $ventaModel->obtenerDetalle($id);

        header('Content-Type: application/json');
        echo json_encode(['venta' => $venta, 'detalle' => $detalle]);
        exit;

    // ── CREAR CLIENTE (JSON) ─────────────────────────────────
    case 'crearCliente':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: vendedorventascontroller.php?accion=index"); exit;
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
        header("Location: vendedorventascontroller.php?accion=index"); exit;
}
?>
