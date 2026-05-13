<?php
/**
 * VISTA: Gestión de Compras
 * Cargada por: controllers/admincomprascontroller.php?accion=index
 * Variables: $compras, $proveedores, $productos, $total, $totalMes, $alert
 */
if (!isset($compras)) {
    header("Location: /inventory/controllers/admincomprascontroller.php?accion=index");
    exit;
}

$usuario = $_SESSION['usuario'];
$nombre  = $usuario['nombre'] ?? 'Administrador';
$rol     = $usuario['rol']    ?? 'administrador';
$correo  = $usuario['correo'] ?? '';

$GLOBALS['nombre']  = $nombre;
$GLOBALS['rol']     = $rol;
$GLOBALS['correo']  = $correo;
$GLOBALS['usuario'] = $usuario;

$titulo = "Gestión de Compras";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<style>
.page-header {
    background: #11225a;
    border-radius:14px; padding:1.4rem 1.6rem; color:white;
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:1rem;
}
.page-header h2 { font-size:1.3rem; font-weight:800; margin:0; }
.page-header p  { font-size:.8rem; opacity:.7; margin:.2rem 0 0; }

.mini-stat {
    background:white; border-radius:12px; border:1px solid #e2e8f0;
    padding:1rem 1.25rem; display:flex; align-items:center; gap:.85rem;
}
.mini-stat-icon {
    width:40px; height:40px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-size:1rem; flex-shrink:0;
}
.mini-stat-val { font-size:1.5rem; font-weight:800; color:#1e293b; line-height:1; }
.mini-stat-lbl { font-size:.72rem; color:#94a3b8; font-weight:500; margin-top:2px; }

.u-wrap { background:white; border-radius:14px; border:1px solid #e2e8f0; overflow:hidden; }
.u-toolbar {
    padding:.9rem 1.25rem;
    display:flex; align-items:center; justify-content:space-between;
    flex-wrap:wrap; gap:.75rem; border-bottom:1px solid #f1f5f9;
}
.u-search {
    display:flex; align-items:center; gap:.5rem;
    background:#f8fafc; border:1px solid #e2e8f0;
    border-radius:8px; padding:.4rem .85rem;
}
.u-search input {
    border:none; background:transparent; outline:none;
    font-size:.83rem; color:#374151; width:200px;
}
.u-search i { color:#94a3b8; font-size:.8rem; }

table.ventas-tbl { width:100%; border-collapse:collapse; }
table.ventas-tbl th {
    font-size:.7rem; font-weight:700; color:#94a3b8;
    text-transform:uppercase; letter-spacing:.5px;
    padding:.65rem 1rem; text-align:left;
    background:#f8fafc; border-bottom:1px solid #f1f5f9;
}
table.ventas-tbl td {
    padding:.75rem 1rem; font-size:.83rem; color:#374151;
    border-bottom:1px solid #f8fafc; vertical-align:middle;
}
table.ventas-tbl tr:last-child td { border-bottom:none; }
table.ventas-tbl tbody tr:hover td { background:#fafbff; }

.btn-action {
    width:30px; height:30px; border-radius:7px; border:none;
    display:inline-flex; align-items:center; justify-content:center;
    font-size:.8rem; cursor:pointer; transition:all .2s;
}
.btn-view   { background:#dbeafe; color:#2563eb; }
.btn-view:hover   { background:#bfdbfe; }
.btn-delete { background:#fee2e2; color:#dc2626; }
.btn-delete:hover { background:#fecaca; }

.btn-primary {
    display:inline-flex; align-items:center; gap:.45rem;
    background:#11225a;
    color:white; border:none; border-radius:9px;
    padding:.5rem 1.1rem; font-size:.83rem; font-weight:600;
    cursor:pointer; text-decoration:none; transition:all .2s;
    box-shadow:0 3px 10px rgba(29,78,216,.3);
}
.btn-primary:hover { transform:translateY(-1px); color:white; }
.btn-primary:disabled { opacity:.6; cursor:not-allowed; transform:none; }

.btn-secondary {
    display:inline-flex; align-items:center; gap:.45rem;
    background:white; color:#374151; border:1px solid #e2e8f0;
    border-radius:9px; padding:.5rem 1.1rem; font-size:.83rem;
    font-weight:600; cursor:pointer; transition:all .2s;
}
.btn-secondary:hover { background:#f8fafc; }

.btn-success {
    display:inline-flex; align-items:center; gap:.45rem;
    background:linear-gradient(135deg,#16a34a,#15803d);
    color:white; border:none; border-radius:9px;
    padding:.5rem 1.1rem; font-size:.83rem; font-weight:600;
    cursor:pointer; transition:all .2s;
    box-shadow:0 3px 10px rgba(22,163,74,.25);
}
.btn-success:hover { transform:translateY(-1px); }

/* Modal */
.modal-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(15,23,42,.55); backdrop-filter:blur(4px);
    z-index:2000; align-items:flex-start; justify-content:center;
    padding:2rem 1rem; overflow-y:auto;
}
.modal-overlay.open { display:flex; }
.modal-box {
    background:white; border-radius:16px; width:100%; max-width:680px;
    box-shadow:0 24px 60px rgba(0,0,0,.2);
    animation:modalIn .25s ease-out; overflow:hidden;
    display:flex; flex-direction:column; margin:auto;
    max-height:88vh;
}
.modal-box-sm {
    background:white; border-radius:16px; width:100%; max-width:420px;
    box-shadow:0 24px 60px rgba(0,0,0,.2);
    animation:modalIn .25s ease-out; overflow:hidden;
    display:flex; flex-direction:column; margin:auto;
}
.confirm-box {
    background:white; border-radius:16px; width:100%; max-width:380px;
    box-shadow:0 24px 60px rgba(0,0,0,.2); animation:modalIn .25s ease-out;
    margin:auto;
}
@keyframes modalIn {
    from { opacity:0; transform:translateY(-14px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
.modal-header {
    background:#11225a; color:white;
    padding:1.1rem 1.4rem; display:flex; align-items:center; justify-content:space-between;
    flex-shrink:0;
}
.modal-header h3 { font-size:1rem; font-weight:700; margin:0; }
.modal-close {
    background:rgba(255,255,255,.15); border:none; color:white;
    width:28px; height:28px; border-radius:7px;
    display:flex; align-items:center; justify-content:center;
    cursor:pointer; font-size:.85rem;
}
.modal-close:hover { background:rgba(255,255,255,.25); }
.modal-body   { padding:1.4rem; overflow-y:auto; }
.modal-footer {
    padding:.9rem 1.4rem; border-top:1px solid #f1f5f9;
    display:flex; justify-content:flex-end; gap:.6rem; flex-shrink:0;
}

.form-row { display:grid; grid-template-columns:1fr 1fr; gap:.85rem; }
.form-group { display:flex; flex-direction:column; gap:.35rem; margin-bottom:.75rem; }
.form-group label { font-size:.78rem; font-weight:600; color:#374151; }
.form-group input,
.form-group select,
.form-group textarea {
    border:1px solid #e2e8f0; border-radius:8px;
    padding:.5rem .75rem; font-size:.85rem; color:#1e293b;
    outline:none; transition:border .2s; background:white; font-family:inherit;
}
.form-group input:focus,
.form-group select:focus { border-color:#1e3a8a; box-shadow:0 0 0 3px rgba(29,78,216,.1); }

.alert-bar {
    padding:.75rem 1rem; border-radius:10px; font-size:.83rem; font-weight:500;
    display:flex; align-items:center; gap:.6rem;
}
.alert-success { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
.alert-danger  { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }

.confirm-icon-del {
    width:56px; height:56px; border-radius:50%;
    background:#fee2e2; color:#dc2626;
    display:flex; align-items:center; justify-content:center;
    font-size:1.4rem; margin:0 auto 1rem;
}

/* Tabla carrito */
.cart-table { width:100%; border-collapse:collapse; margin-top:.75rem; }
.cart-table th {
    font-size:.7rem; font-weight:700; color:#94a3b8;
    text-transform:uppercase; letter-spacing:.4px;
    padding:.5rem .75rem; background:#f8fafc;
    border-bottom:1px solid #e2e8f0; text-align:left;
}
.cart-table td {
    padding:.55rem .75rem; font-size:.82rem; color:#374151;
    border-bottom:1px solid #f8fafc; vertical-align:middle;
}
.cart-table tr:last-child td { border-bottom:none; }

.cart-qty {
    width:60px; border:1px solid #e2e8f0; border-radius:6px;
    padding:.3rem .5rem; font-size:.82rem; text-align:center;
    outline:none;
}
.cart-qty:focus { border-color:#1e3a8a; }

.cart-price {
    width:90px; border:1px solid #e2e8f0; border-radius:6px;
    padding:.3rem .5rem; font-size:.82rem; text-align:right;
    outline:none;
}
.cart-price:focus { border-color:#1e3a8a; }

.btn-remove-row {
    width:26px; height:26px; border-radius:6px; border:none;
    background:#fee2e2; color:#dc2626; cursor:pointer;
    display:inline-flex; align-items:center; justify-content:center;
    font-size:.75rem; transition:background .2s;
}
.btn-remove-row:hover { background:#fecaca; }

.total-box {
    background:#11225a;
    border-radius:10px; padding:.85rem 1.1rem;
    display:flex; align-items:center; justify-content:space-between;
    color:white; margin-top:.75rem;
}
.total-box span { font-size:.85rem; opacity:.8; }
.total-box strong { font-size:1.3rem; font-weight:800; }

/* Detalle compra */
.detalle-table { width:100%; border-collapse:collapse; }
.detalle-table th {
    font-size:.7rem; font-weight:700; color:#94a3b8;
    text-transform:uppercase; letter-spacing:.4px;
    padding:.5rem .75rem; background:#f8fafc;
    border-bottom:1px solid #e2e8f0; text-align:left;
}
.detalle-table td {
    padding:.6rem .75rem; font-size:.82rem; color:#374151;
    border-bottom:1px solid #f8fafc; vertical-align:middle;
}
.detalle-table tr:last-child td { border-bottom:none; }

.info-row {
    display:flex; gap:.5rem; align-items:baseline;
    font-size:.83rem; margin-bottom:.4rem;
}
.info-row .lbl { color:#94a3b8; font-weight:600; min-width:90px; font-size:.75rem; }
.info-row .val { color:#1e293b; font-weight:500; }

.g3  { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
.gap { display:flex; flex-direction:column; gap:1.1rem; }

@media(max-width:768px){
    .form-row { grid-template-columns:1fr; }
    .g3 { grid-template-columns:1fr; }
}
</style>


<div class="gap">

    <!-- Alerta -->
    <?php if ($alert): ?>
        <div class="alert-bar alert-<?= htmlspecialchars($alert['type']) ?>">
            <i class="fas fa-<?= $alert['type']==='success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
            <?= htmlspecialchars($alert['text']) ?>
        </div>
    <?php endif; ?>

    <!-- Cabecera -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-truck" style="margin-right:.5rem;opacity:.8;"></i>Gesti&oacute;n de Compras</h2>
            <p>Registra y administra las compras del sistema</p>
        </div>
        <button class="btn-primary" onclick="abrirModal('modalCrear')">
            <i class="fas fa-plus"></i> Nueva Compra
        </button>
    </div>

    <!-- Mini stats -->
    <div class="g3">
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:#dbeafe;color:#1e3a8a;">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div>
                <div class="mini-stat-val"><?= $total ?></div>
                <div class="mini-stat-lbl">Total compras</div>
            </div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:#dcfce7;color:#16a34a;">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div>
                <div class="mini-stat-val">$<?= number_format((float)$totalMes, 2) ?></div>
                <div class="mini-stat-lbl">Total del mes</div>
            </div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:#dbeafe;color:#2563eb;">
                <i class="fas fa-building"></i>
            </div>
            <div>
                <div class="mini-stat-val"><?= count($proveedores) ?></div>
                <div class="mini-stat-lbl">Proveedores registrados</div>
            </div>
        </div>
    </div>

    <!-- Tabla de compras -->
    <div class="u-wrap">
        <div class="u-toolbar">
            <div style="font-size:.9rem;font-weight:700;color:#1e293b;">
                <i class="fas fa-list" style="color:#1e3a8a;margin-right:.4rem;"></i>
                Historial de Compras
            </div>
            <div class="u-search">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar compra..." oninput="filtrarTabla()">
            </div>
        </div>

        <table class="ventas-tbl" id="tablaCompras">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($compras)): ?>
                    <tr>
                        <td colspan="5" style="text-align:center;padding:2.5rem;color:#94a3b8;">
                            <i class="fas fa-truck" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>
                            No hay compras registradas
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($compras as $i => $c): ?>
                    <tr>
                        <td style="color:#94a3b8;font-size:.75rem;"><?= (int)$c['id_compra'] ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.55rem;">
                                <div style="width:32px;height:32px;border-radius:50%;
                                            background:#11225a;
                                            display:flex;align-items:center;justify-content:center;
                                            color:white;font-size:.7rem;font-weight:700;flex-shrink:0;">
                                    <?= strtoupper(substr($c['proveedor_nombre'] ?? 'P', 0, 2)) ?>
                                </div>
                                <span style="font-weight:600;"><?= htmlspecialchars($c['proveedor_nombre'] ?? '—') ?></span>
                            </div>
                        </td>
                        <td style="color:#64748b;font-size:.8rem;">
                            <?= date('d/m/Y h:i A', strtotime($c['fecha'])) ?>
                        </td>
                        <td>
                            <span style="font-weight:700;color:#1e293b;">
                                $<?= number_format((float)$c['total'], 2) ?>
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:.4rem;">
                                <button class="btn-action btn-view" title="Ver detalle"
                                    onclick="verDetalle(<?= (int)$c['id_compra'] ?>)">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-action btn-delete" title="Eliminar"
                                    onclick="confirmarEliminar(<?= (int)$c['id_compra'] ?>, '<?= addslashes($c['proveedor_nombre'] ?? '') ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div><!-- end gap -->


<!-- ═══════════════════════════════════════════════════════════
     MODAL CREAR COMPRA
════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalCrear">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-plus" style="margin-right:.5rem;"></i>Nueva Compra</h3>
            <button class="modal-close" onclick="cerrarModal('modalCrear')"><i class="fas fa-times"></i></button>
        </div>
        <form action="/inventory/controllers/admincomprascontroller.php?accion=crear" method="POST" id="formCrearCompra">
            <div class="modal-body">

                <!-- Proveedor -->
                <div class="form-group">
                    <label>Proveedor <span style="color:#ef4444;">*</span></label>
                    <div style="display:flex;gap:.5rem;align-items:center;">
                        <select name="id_proveedor" id="selectProveedor" required style="flex:1;">
                            <option value="">— Seleccionar proveedor —</option>
                            <?php foreach ($proveedores as $p): ?>
                                <option value="<?= (int)$p['id_proveedor'] ?>">
                                    <?= htmlspecialchars($p['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn-secondary" style="white-space:nowrap;padding:.5rem .85rem;"
                            onclick="abrirModal('modalNuevoProveedor')">
                            <i class="fas fa-building"></i> Nuevo Proveedor
                        </button>
                    </div>
                </div>

                <!-- Agregar producto -->
                <div style="border:1px solid #e2e8f0;border-radius:10px;padding:1rem;margin-bottom:.75rem;">
                    <div style="font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.65rem;">
                        <i class="fas fa-box-open" style="color:#1e3a8a;margin-right:.35rem;"></i>
                        Agregar Producto
                    </div>
                    <div style="display:flex;gap:.5rem;align-items:flex-end;flex-wrap:wrap;">
                        <div style="flex:1;min-width:180px;">
                            <label style="font-size:.75rem;font-weight:600;color:#374151;display:block;margin-bottom:.3rem;">
                                Producto
                            </label>
                            <select id="selectProducto" style="width:100%;border:1px solid #e2e8f0;border-radius:8px;
                                padding:.5rem .75rem;font-size:.83rem;outline:none;">
                                <option value="">— Seleccionar —</option>
                                <?php foreach ($productos as $p): ?>
                                    <option value="<?= (int)$p['id_producto'] ?>"
                                        data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>">
                                        <?= htmlspecialchars($p['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div style="width:80px;">
                            <label style="font-size:.75rem;font-weight:600;color:#374151;display:block;margin-bottom:.3rem;">
                                Cantidad
                            </label>
                            <input type="number" id="inputCantidad" value="1" min="1"
                                style="width:100%;border:1px solid #e2e8f0;border-radius:8px;
                                       padding:.5rem .5rem;font-size:.83rem;outline:none;text-align:center;">
                        </div>
                        <div style="width:110px;">
                            <label style="font-size:.75rem;font-weight:600;color:#374151;display:block;margin-bottom:.3rem;">
                                Precio compra
                            </label>
                            <input type="number" id="inputPrecio" value="" min="0" step="0.01" placeholder="0.00"
                                style="width:100%;border:1px solid #e2e8f0;border-radius:8px;
                                       padding:.5rem .5rem;font-size:.83rem;outline:none;text-align:right;">
                        </div>
                        <button type="button" class="btn-primary" style="padding:.5rem .9rem;" onclick="agregarProducto()">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                    </div>
                </div>

                <!-- Tabla carrito -->
                <div id="carritoWrap" style="display:none;">
                    <div style="font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">
                        <i class="fas fa-shopping-basket" style="color:#1e3a8a;margin-right:.35rem;"></i>
                        Productos en la compra
                    </div>
                    <table class="cart-table" id="tablaCarrito">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th style="width:80px;text-align:center;">Cant.</th>
                                <th style="width:110px;text-align:right;">Precio Compra</th>
                                <th style="width:90px;text-align:right;">Subtotal</th>
                                <th style="width:36px;"></th>
                            </tr>
                        </thead>
                        <tbody id="carritoBody">
                        </tbody>
                    </table>
                    <!-- Inputs ocultos para el POST -->
                    <div id="carritoInputs"></div>
                    <!-- Total -->
                    <div class="total-box">
                        <span><i class="fas fa-calculator" style="margin-right:.4rem;"></i>Total de la compra</span>
                        <strong id="totalCompra">$0.00</strong>
                    </div>
                </div>

                <div id="carritoVacio" style="text-align:center;padding:1.5rem;color:#94a3b8;font-size:.83rem;">
                    <i class="fas fa-shopping-basket" style="font-size:1.5rem;display:block;margin-bottom:.4rem;opacity:.4;"></i>
                    A&uacute;n no has agregado productos
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn-primary" id="btnGuardarCompra" disabled>
                    <i class="fas fa-save"></i> Registrar Compra
                </button>
            </div>
        </form>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════════
     MODAL VER DETALLE
════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalDetalle">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-eye" style="margin-right:.5rem;"></i>Detalle de Compra</h3>
            <button class="modal-close" onclick="cerrarModal('modalDetalle')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="detalleContenido">
            <div style="text-align:center;padding:2rem;color:#94a3b8;">
                <i class="fas fa-spinner fa-spin" style="font-size:1.5rem;"></i>
                <p style="margin-top:.5rem;font-size:.83rem;">Cargando...</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="cerrarModal('modalDetalle')">Cerrar</button>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════════
     MODAL CONFIRMAR ELIMINAR
════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalEliminar" style="align-items:center;">
    <div class="confirm-box">
        <div style="padding:1.75rem 1.5rem 1rem;text-align:center;">
            <div class="confirm-icon-del"><i class="fas fa-trash"></i></div>
            <h3 style="font-size:1rem;font-weight:700;color:#1e293b;margin-bottom:.4rem;">
                &iquest;Eliminar compra?
            </h3>
            <p style="font-size:.83rem;color:#64748b;" id="confirmNombreCompra"></p>
            <p style="font-size:.78rem;color:#94a3b8;margin-top:.3rem;">
                El stock de los productos ser&aacute; revertido autom&aacute;ticamente.
            </p>
        </div>
        <div style="padding:.9rem 1.5rem;border-top:1px solid #f1f5f9;
                    display:flex;justify-content:flex-end;gap:.6rem;">
            <button class="btn-secondary" onclick="cerrarModal('modalEliminar')">Cancelar</button>
            <a id="btnConfirmarEliminar" href="#"
               style="display:inline-flex;align-items:center;gap:.4rem;
                      background:#dc2626;color:white;border-radius:9px;
                      padding:.5rem 1.1rem;font-size:.83rem;font-weight:600;text-decoration:none;">
                <i class="fas fa-trash"></i> Eliminar
            </a>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════════
     MODAL ALERTA
════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalAlerta" style="align-items:center;z-index:4000;">
    <div class="confirm-box">
        <div style="padding:1.75rem 1.5rem 1rem;text-align:center;">
            <div style="width:56px;height:56px;border-radius:50%;
                        background:#fef9c3;color:#d97706;
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.4rem;margin:0 auto 1rem;">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <h3 style="font-size:1rem;font-weight:700;color:#1e293b;margin-bottom:.4rem;" id="alertaTitulo">
                Atenci&oacute;n
            </h3>
            <p style="font-size:.83rem;color:#64748b;" id="alertaMensaje"></p>
        </div>
        <div style="padding:.9rem 1.5rem;border-top:1px solid #f1f5f9;
                    display:flex;justify-content:center;">
            <button class="btn-primary" onclick="cerrarModal('modalAlerta')" style="min-width:100px;">
                Entendido
            </button>
        </div>
    </div>
</div>


<!-- ═══════════════════════════════════════════════════════════
     MODAL NUEVO PROVEEDOR
════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modalNuevoProveedor" style="z-index:3000;">
    <div class="modal-box-sm">
        <div class="modal-header">
            <h3><i class="fas fa-building" style="margin-right:.5rem;"></i>Nuevo Proveedor</h3>
            <button class="modal-close" onclick="cerrarModal('modalNuevoProveedor')"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="alertNuevoProveedor" style="display:none;margin-bottom:.75rem;"></div>
            <div class="form-group">
                <label>Nombre <span style="color:#ef4444;">*</span></label>
                <input type="text" id="np_nombre" placeholder="Nombre del proveedor o empresa">
            </div>
            <div class="form-group">
                <label>Tel&eacute;fono</label>
                <input type="text" id="np_telefono" placeholder="Opcional">
            </div>
            <div class="form-group">
                <label>Correo</label>
                <input type="email" id="np_correo" placeholder="Opcional">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-secondary" onclick="cerrarModal('modalNuevoProveedor')">Cancelar</button>
            <button class="btn-primary" id="btnGuardarProveedor" onclick="guardarNuevoProveedor()">
                <i class="fas fa-save"></i> Guardar Proveedor
            </button>
        </div>
    </div>
</div>


<script>
// ═══════════════════════════════════════════════════════════
//  MODALES
// ═══════════════════════════════════════════════════════════
function abrirModal(id) {
    if (id === 'modalCrear') resetFormCompra();
    if (id === 'modalNuevoProveedor') resetFormProveedor();
    document.getElementById(id).classList.add('open');
}

function cerrarModal(id) {
    document.getElementById(id).classList.remove('open');
}

// Cerrar al click fuera del box
document.querySelectorAll('.modal-overlay').forEach(function(o) {
    o.addEventListener('click', function(e) {
        if (e.target === o) o.classList.remove('open');
    });
});

// Cerrar con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.open').forEach(function(m) {
            m.classList.remove('open');
        });
    }
});


// ═══════════════════════════════════════════════════════════
//  ALERTA PERSONALIZADA
// ═══════════════════════════════════════════════════════════
function mostrarAlerta(titulo, mensaje) {
    document.getElementById('alertaTitulo').textContent  = titulo;
    document.getElementById('alertaMensaje').textContent = mensaje;
    document.getElementById('modalAlerta').classList.add('open');
}


// ═══════════════════════════════════════════════════════════
//  CARRITO DE PRODUCTOS
// ═══════════════════════════════════════════════════════════
var carrito = []; // [{id, nombre, precio, cantidad}]

function resetFormCompra() {
    carrito = [];
    renderCarrito();
    document.getElementById('selectProveedor').value = '';
    document.getElementById('selectProducto').value  = '';
    document.getElementById('inputCantidad').value   = 1;
    document.getElementById('inputPrecio').value     = '';
}

function agregarProducto() {
    var sel        = document.getElementById('selectProducto');
    var cantInput  = document.getElementById('inputCantidad');
    var precInput  = document.getElementById('inputPrecio');
    var idProd     = parseInt(sel.value);
    var cantidad   = parseInt(cantInput.value);
    var precio     = parseFloat(precInput.value);

    if (!idProd) {
        mostrarAlerta('Producto requerido', 'Selecciona un producto antes de agregar.');
        return;
    }
    if (!cantidad || cantidad < 1) {
        mostrarAlerta('Cantidad inválida', 'La cantidad debe ser al menos 1.');
        return;
    }
    if (isNaN(precio) || precio < 0) {
        mostrarAlerta('Precio inválido', 'Ingresa un precio de compra válido (mayor o igual a 0).');
        return;
    }

    var opt    = sel.options[sel.selectedIndex];
    var nombre = opt.dataset.nombre;

    // Si ya está en el carrito, actualizar cantidad y precio
    var existente = carrito.find(function(item) { return item.id === idProd; });
    if (existente) {
        existente.cantidad += cantidad;
        existente.precio    = precio; // actualizar precio al último ingresado
    } else {
        carrito.push({ id: idProd, nombre: nombre, precio: precio, cantidad: cantidad });
    }

    // Reset inputs
    sel.value          = '';
    cantInput.value    = 1;
    precInput.value    = '';
    renderCarrito();
}

function quitarProducto(idx) {
    carrito.splice(idx, 1);
    renderCarrito();
}

function actualizarCantidad(idx, val) {
    var cantidad = parseInt(val);
    if (!cantidad || cantidad < 1) cantidad = 1;
    carrito[idx].cantidad = cantidad;
    renderCarrito();
}

function actualizarPrecio(idx, val) {
    var precio = parseFloat(val);
    if (isNaN(precio) || precio < 0) precio = 0;
    carrito[idx].precio = precio;
    renderCarrito();
}

function renderCarrito() {
    var tbody      = document.getElementById('carritoBody');
    var inputs     = document.getElementById('carritoInputs');
    var wrap       = document.getElementById('carritoWrap');
    var vacio      = document.getElementById('carritoVacio');
    var btnGuardar = document.getElementById('btnGuardarCompra');

    tbody.innerHTML  = '';
    inputs.innerHTML = '';

    if (carrito.length === 0) {
        wrap.style.display  = 'none';
        vacio.style.display = 'block';
        btnGuardar.disabled = true;
        return;
    }

    wrap.style.display  = 'block';
    vacio.style.display = 'none';
    btnGuardar.disabled = false;

    var total = 0;

    carrito.forEach(function(item, idx) {
        var subtotal = item.precio * item.cantidad;
        total += subtotal;

        // Fila visible
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td style="font-weight:600;">' + escHtml(item.nombre) + '</td>' +
            '<td style="text-align:center;">' +
                '<input type="number" class="cart-qty" value="' + item.cantidad + '" min="1"' +
                ' onchange="actualizarCantidad(' + idx + ', this.value)">' +
            '</td>' +
            '<td style="text-align:right;">' +
                '<input type="number" class="cart-price" value="' + item.precio.toFixed(2) + '" min="0" step="0.01"' +
                ' onchange="actualizarPrecio(' + idx + ', this.value)">' +
            '</td>' +
            '<td style="text-align:right;font-weight:700;">$' + subtotal.toFixed(2) + '</td>' +
            '<td style="text-align:center;">' +
                '<button type="button" class="btn-remove-row" onclick="quitarProducto(' + idx + ')">' +
                    '<i class="fas fa-times"></i>' +
                '</button>' +
            '</td>';
        tbody.appendChild(tr);

        // Inputs ocultos para el POST
        var inpProd = document.createElement('input');
        inpProd.type  = 'hidden';
        inpProd.name  = 'productos[]';
        inpProd.value = item.id;
        inputs.appendChild(inpProd);

        var inpCant = document.createElement('input');
        inpCant.type  = 'hidden';
        inpCant.name  = 'cantidades[]';
        inpCant.value = item.cantidad;
        inputs.appendChild(inpCant);

        var inpPrec = document.createElement('input');
        inpPrec.type  = 'hidden';
        inpPrec.name  = 'precios[]';
        inpPrec.value = item.precio.toFixed(2);
        inputs.appendChild(inpPrec);
    });

    document.getElementById('totalCompra').textContent = '$' + total.toFixed(2);
}

function escHtml(str) {
    var d = document.createElement('div');
    d.appendChild(document.createTextNode(str));
    return d.innerHTML;
}


// ═══════════════════════════════════════════════════════════
//  VER DETALLE DE COMPRA (fetch)
// ═══════════════════════════════════════════════════════════
function verDetalle(idCompra) {
    var contenido = document.getElementById('detalleContenido');
    contenido.innerHTML =
        '<div style="text-align:center;padding:2rem;color:#94a3b8;">' +
        '<i class="fas fa-spinner fa-spin" style="font-size:1.5rem;"></i>' +
        '<p style="margin-top:.5rem;font-size:.83rem;">Cargando...</p></div>';

    document.getElementById('modalDetalle').classList.add('open');

    fetch('/inventory/controllers/admincomprascontroller.php?accion=detalle&id=' + idCompra)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var c = data.compra;
            var d = data.detalle;

            if (!c) {
                contenido.innerHTML = '<p style="color:#dc2626;text-align:center;">No se encontró la compra.</p>';
                return;
            }

            var fecha = new Date(c.fecha);
            var fechaStr = fecha.toLocaleDateString('es-MX', {
                day:'2-digit', month:'2-digit', year:'numeric',
                hour:'2-digit', minute:'2-digit'
            });

            var html =
                '<div style="background:#f8fafc;border-radius:10px;padding:1rem;margin-bottom:1rem;">' +
                    '<div class="info-row"><span class="lbl">Compra #</span><span class="val" style="font-weight:700;color:#1e3a8a;">' + c.id_compra + '</span></div>' +
                    '<div class="info-row"><span class="lbl">Proveedor</span><span class="val">' + escHtml(c.proveedor_nombre || '—') + '</span></div>' +
                    (c.proveedor_telefono ? '<div class="info-row"><span class="lbl">Tel\u00e9fono</span><span class="val">' + escHtml(c.proveedor_telefono) + '</span></div>' : '') +
                    (c.proveedor_correo   ? '<div class="info-row"><span class="lbl">Correo</span><span class="val">' + escHtml(c.proveedor_correo) + '</span></div>' : '') +
                    '<div class="info-row"><span class="lbl">Fecha</span><span class="val">' + fechaStr + '</span></div>' +
                '</div>';

            if (d && d.length > 0) {
                html += '<div style="font-size:.8rem;font-weight:700;color:#374151;margin-bottom:.5rem;">' +
                        '<i class="fas fa-list" style="color:#1e3a8a;margin-right:.35rem;"></i>Productos</div>' +
                        '<table class="detalle-table"><thead><tr>' +
                        '<th>Producto</th><th style="text-align:center;">Cant.</th>' +
                        '<th style="text-align:right;">Precio Compra</th><th style="text-align:right;">Subtotal</th>' +
                        '</tr></thead><tbody>';

                d.forEach(function(item) {
                    html += '<tr>' +
                        '<td style="font-weight:600;">' + escHtml(item.producto_nombre || '—') + '</td>' +
                        '<td style="text-align:center;">' + item.cantidad + '</td>' +
                        '<td style="text-align:right;color:#64748b;">$' + parseFloat(item.precio_compra || 0).toFixed(2) + '</td>' +
                        '<td style="text-align:right;font-weight:700;">$' + parseFloat(item.subtotal).toFixed(2) + '</td>' +
                        '</tr>';
                });

                html += '</tbody></table>';
            } else {
                html += '<p style="color:#94a3b8;text-align:center;font-size:.83rem;">Sin productos en el detalle.</p>';
            }

            html +=
                '<div class="total-box" style="margin-top:.85rem;">' +
                '<span><i class="fas fa-calculator" style="margin-right:.4rem;"></i>Total</span>' +
                '<strong>$' + parseFloat(c.total).toFixed(2) + '</strong></div>';

            contenido.innerHTML = html;
        })
        .catch(function() {
            contenido.innerHTML = '<p style="color:#dc2626;text-align:center;">Error al cargar el detalle.</p>';
        });
}


// ═══════════════════════════════════════════════════════════
//  CONFIRMAR ELIMINAR
// ═══════════════════════════════════════════════════════════
function confirmarEliminar(id, proveedorNombre) {
    document.getElementById('confirmNombreCompra').textContent =
        'Compra #' + id + ' — ' + proveedorNombre;
    document.getElementById('btnConfirmarEliminar').href =
        '/inventory/controllers/admincomprascontroller.php?accion=eliminar&id=' + id;
    document.getElementById('modalEliminar').classList.add('open');
}


// ═══════════════════════════════════════════════════════════
//  NUEVO PROVEEDOR RÁPIDO (fetch)
// ═══════════════════════════════════════════════════════════
function resetFormProveedor() {
    document.getElementById('np_nombre').value   = '';
    document.getElementById('np_telefono').value = '';
    document.getElementById('np_correo').value   = '';
    var alerta = document.getElementById('alertNuevoProveedor');
    alerta.style.display = 'none';
    alerta.innerHTML = '';
}

function guardarNuevoProveedor() {
    var nombre   = document.getElementById('np_nombre').value.trim();
    var telefono = document.getElementById('np_telefono').value.trim();
    var correo   = document.getElementById('np_correo').value.trim();
    var alerta   = document.getElementById('alertNuevoProveedor');
    var btn      = document.getElementById('btnGuardarProveedor');

    if (!nombre) {
        alerta.innerHTML = '<div class="alert-bar alert-danger"><i class="fas fa-exclamation-circle"></i> El nombre es obligatorio.</div>';
        alerta.style.display = 'block';
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

    var formData = new FormData();
    formData.append('nombre',   nombre);
    formData.append('telefono', telefono);
    formData.append('correo',   correo);

    fetch('/inventory/controllers/admincomprascontroller.php?accion=crearProveedor', {
        method: 'POST',
        body: formData
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.error) {
            alerta.innerHTML = '<div class="alert-bar alert-danger"><i class="fas fa-exclamation-circle"></i> ' + escHtml(data.error) + '</div>';
            alerta.style.display = 'block';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save"></i> Guardar Proveedor';
            return;
        }

        // Agregar al select de proveedores
        var select = document.getElementById('selectProveedor');
        var opt    = document.createElement('option');
        opt.value       = data.id;
        opt.textContent = data.nombre;
        opt.selected    = true;
        select.appendChild(opt);

        cerrarModal('modalNuevoProveedor');
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Guardar Proveedor';
    })
    .catch(function() {
        alerta.innerHTML = '<div class="alert-bar alert-danger"><i class="fas fa-exclamation-circle"></i> Error de conexión.</div>';
        alerta.style.display = 'block';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save"></i> Guardar Proveedor';
    });
}


// ═══════════════════════════════════════════════════════════
//  BÚSQUEDA EN TABLA
// ═══════════════════════════════════════════════════════════
function filtrarTabla() {
    var q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#tablaCompras tbody tr').forEach(function(tr) {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>







