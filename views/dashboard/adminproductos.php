<?php
/**
 * VISTA: Gestión de Productos
 * Cargada por: controllers/adminproductoscontroller.php?accion=index
 * Variables: $productos, $categorias, $total, $stockBajo, $alert
 */
if (!isset($productos)) {
    header("Location: /inventory/controllers/adminproductoscontroller.php?accion=index");
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

$titulo = "Gestión de Productos";
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<style>
.page-header {
    background:#11225a;
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

/* ── Tarjetas de producto ── */
.prod-grid {
    display:grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap:1.1rem;
    padding:1.25rem;
}

.prod-card {
    background:white;
    border-radius:14px;
    border:1px solid #e2e8f0;
    overflow:hidden;
    display:flex;
    flex-direction:column;
    transition:box-shadow .2s, transform .2s;
}
.prod-card:hover {
    box-shadow:0 8px 24px rgba(0,0,0,.1);
    transform:translateY(-2px);
}

.prod-card-img {
    width:100%;
    height:200px;
    object-fit:contain;
    background:#f8fafc;
    display:block;
    padding:1rem;
}

.prod-card-img-placeholder {
    width:100%;
    height:200px;
    background:linear-gradient(135deg,#f1f5f9,#e2e8f0);
    display:flex;
    align-items:center;
    justify-content:center;
    color:#cbd5e1;
    font-size:2.5rem;
}

.prod-card-body {
    padding:.9rem 1rem;
    flex:1;
    display:flex;
    flex-direction:column;
    gap:.35rem;
}

.prod-card-name {
    font-size:1rem;
    font-weight:700;
    color:#1e293b;
    line-height:1.3;
}

.prod-card-cat {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.15rem .55rem; border-radius:20px;
    font-size:.68rem; font-weight:600;
    background:#dcfce7; color:#16a34a;
    width:fit-content;
}

.prod-card-marca {
    font-size:.75rem; color:#94a3b8;
}

.prod-card-price {
    font-size:1.1rem; font-weight:800; color:#1e293b;
    margin-top:.2rem;
}

.prod-card-stock {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.18rem .6rem; border-radius:20px;
    font-size:.7rem; font-weight:600; width:fit-content;
}
.stock-ok  { background:#dcfce7; color:#16a34a; }
.stock-low { background:#fef9c3; color:#ca8a04; }
.stock-out { background:#fee2e2; color:#dc2626; }

.prod-card-footer {
    padding:.75rem 1rem;
    border-top:1px solid #f1f5f9;
    display:flex;
    gap:.5rem;
}

.prod-card-footer .btn-action {
    flex:1;
    height:34px;
    border-radius:8px;
    border:none;
    display:flex; align-items:center; justify-content:center;
    gap:.4rem;
    font-size:.78rem; font-weight:600;
    cursor:pointer; transition:all .2s;
    text-decoration:none;
}
.btn-edit-card   { background:#dbeafe; color:#1e3a8a; }
.btn-edit-card:hover   { background:#bfdbfe; }
.btn-delete-card { background:#fee2e2; color:#dc2626; }
.btn-delete-card:hover { background:#fecaca; }

/* Vacío */
.prod-empty {
    padding:3rem; text-align:center; color:#94a3b8;
    grid-column:1/-1;
}
.prod-empty i { font-size:2.5rem; display:block; margin-bottom:.75rem; }
    width:36px; height:36px; border-radius:9px;
    background:#11225a;
    display:inline-flex; align-items:center; justify-content:center;
    color:white; font-size:.85rem; flex-shrink:0;
}

.cat-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.2rem .65rem; border-radius:20px;
    font-size:.7rem; font-weight:600;
    background:#dcfce7; color:#16a34a;
}

.stock-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.2rem .65rem; border-radius:20px;
    font-size:.72rem; font-weight:700;
}
.stock-ok  { background:#dcfce7; color:#16a34a; }
.stock-low { background:#fef9c3; color:#ca8a04; }
.stock-out { background:#fee2e2; color:#dc2626; }

.btn-action {
    width:30px; height:30px; border-radius:7px; border:none;
    display:inline-flex; align-items:center; justify-content:center;
    font-size:.8rem; cursor:pointer; transition:all .2s;
}
.btn-edit   { background:#dbeafe; color:#1e3a8a; }
.btn-edit:hover   { background:#bfdbfe; }
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

.btn-secondary {
    display:inline-flex; align-items:center; gap:.45rem;
    background:white; color:#374151; border:1px solid #e2e8f0;
    border-radius:9px; padding:.5rem 1.1rem; font-size:.83rem;
    font-weight:600; cursor:pointer; transition:all .2s;
}
.btn-secondary:hover { background:#f8fafc; }

/* Modal */
.modal-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(15,23,42,.55); backdrop-filter:blur(4px);
    z-index:2000; align-items:flex-start; justify-content:center;
    padding:2rem 1rem;
    overflow-y:auto;
}
.modal-overlay.open { display:flex; }
.modal-box {
    background:white; border-radius:16px; width:100%; max-width:540px;
    box-shadow:0 24px 60px rgba(0,0,0,.2);
    animation:modalIn .25s ease-out; overflow:hidden;
    display:flex; flex-direction:column;
    margin: auto;
}
.confirm-box {
    background:white; border-radius:16px; width:100%; max-width:380px;
    box-shadow:0 24px 60px rgba(0,0,0,.2); animation:modalIn .25s ease-out;
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
.modal-body   { padding:1.4rem; }
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
.form-group select:focus,
.form-group textarea:focus { border-color:#1e3a8a; box-shadow:0 0 0 3px rgba(29,78,216,.1); }
.form-group textarea { resize:vertical; min-height:70px; }

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
            <h2><i class="fas fa-box-open" style="margin-right:.5rem;opacity:.8;"></i>Gesti&oacute;n de Productos</h2>
            <p>Administra el inventario de productos del sistema</p>
        </div>
        <button class="btn-primary" onclick="abrirModal('modalCrear')">
            <i class="fas fa-plus"></i> Nuevo Producto
        </button>
    </div>

    <!-- Mini stats -->
    <div class="g3">
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:#dbeafe;color:#1e3a8a;">
                <i class="fas fa-box-open"></i>
            </div>
            <div>
                <div class="mini-stat-val"><?= $total ?></div>
                <div class="mini-stat-lbl">Total productos</div>
            </div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:#fef9c3;color:#ca8a04;">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <div>
                <div class="mini-stat-val"><?= $stockBajo ?></div>
                <div class="mini-stat-lbl">Stock bajo o agotado</div>
            </div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:#dcfce7;color:#16a34a;">
                <i class="fas fa-tags"></i>
            </div>
            <div>
                <div class="mini-stat-val"><?= count($categorias) ?></div>
                <div class="mini-stat-lbl">Categor&iacute;as disponibles</div>
            </div>
        </div>
    </div>

    <!-- Grid de productos -->
    <div class="u-wrap">
        <div class="u-toolbar">
            <div style="font-size:.9rem;font-weight:700;color:#1e293b;">
                <i class="fas fa-th-large" style="color:#1e3a8a;margin-right:.4rem;"></i>
                Cat&aacute;logo de Productos
            </div>
            <div class="u-search">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar producto..." oninput="filtrarGrid()">
            </div>
        </div>

        <div class="prod-grid" id="prodGrid">
            <?php if (empty($productos)): ?>
                <div class="prod-empty">
                    <i class="fas fa-box-open"></i>
                    No hay productos registrados
                </div>
            <?php else: ?>
                <?php foreach ($productos as $p):
                    $stock      = (int)$p['stock_actual'];
                    $minimo     = (int)$p['stock_minimo'];
                    $stockClass = $stock === 0 ? 'stock-out' : ($stock <= $minimo ? 'stock-low' : 'stock-ok');
                    $stockLabel = $stock === 0 ? 'Agotado' : ($stock <= $minimo ? 'Stock bajo' : 'En stock');
                ?>
                <div class="prod-card" data-nombre="<?= htmlspecialchars(strtolower($p['nombre'])) ?>">

                    <!-- Imagen -->
                    <?php if (!empty($p['imagen'])): ?>
                        <img class="prod-card-img"
                             src="/inventory/img/productos/<?= htmlspecialchars($p['imagen']) ?>"
                             alt="<?= htmlspecialchars($p['nombre']) ?>">
                    <?php else: ?>
                        <div class="prod-card-img-placeholder">
                            <i class="fas fa-box-open"></i>
                        </div>
                    <?php endif; ?>

                    <!-- Info -->
                    <div class="prod-card-body">
                        <div class="prod-card-name"><?= htmlspecialchars($p['nombre']) ?></div>

                        <?php if (!empty($p['descripcion'])): ?>
                            <div style="font-size:.78rem;color:#64748b;line-height:1.4;">
                                <?= htmlspecialchars($p['descripcion']) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($p['marca'])): ?>
                            <div class="prod-card-marca">
                                <i class="fas fa-industry" style="font-size:.65rem;margin-right:3px;"></i>
                                <?= htmlspecialchars($p['marca']) ?>
                            </div>
                        <?php endif; ?>

                        <span class="prod-card-cat">
                            <i class="fas fa-tag" style="font-size:.6rem;"></i>
                            <?= htmlspecialchars($p['categoria_nombre'] ?? 'Sin categoría') ?>
                        </span>

                        <span class="prod-card-stock <?= $stockClass ?>">
                            <i class="fas fa-circle" style="font-size:.45rem;"></i>
                            <?= $stock ?> unidades &mdash; <?= $stockLabel ?>
                        </span>

                        <div class="prod-card-price">
                            $<?= number_format((float)$p['precio_venta'], 2) ?>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="prod-card-footer">
                        <button class="btn-action btn-edit-card"
                            data-id="<?= (int)$p['id_producto'] ?>"
                            data-categoria="<?= (int)$p['id_categoria'] ?>"
                            data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>"
                            data-marca="<?= htmlspecialchars($p['marca'] ?? '', ENT_QUOTES) ?>"
                            data-stock-actual="<?= (int)$p['stock_actual'] ?>"
                            data-stock-minimo="<?= (int)$p['stock_minimo'] ?>"
                            data-precio="<?= (float)$p['precio_venta'] ?>"
                            data-descripcion="<?= htmlspecialchars($p['descripcion'] ?? '', ENT_QUOTES) ?>"
                            data-imagen="<?= htmlspecialchars($p['imagen'] ?? '', ENT_QUOTES) ?>"
                            onclick="editarDesdeCard(this)">
                            <i class="fas fa-pen"></i> Editar
                        </button>
                        <button class="btn-action btn-delete-card"
                            data-id="<?= (int)$p['id_producto'] ?>"
                            data-nombre="<?= htmlspecialchars($p['nombre'], ENT_QUOTES) ?>"
                            onclick="eliminarDesdeCard(this)">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>

                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div><!-- end gap -->


<!-- ═══════ MODAL CREAR ═══════ -->
<div class="modal-overlay" id="modalCrear">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-plus" style="margin-right:.5rem;"></i>Nuevo Producto</h3>
            <button class="modal-close" onclick="cerrarModal('modalCrear')"><i class="fas fa-times"></i></button>
        </div>
        <form action="/inventory/controllers/adminproductoscontroller.php?accion=crear" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nombre" placeholder="Nombre del producto" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Categor&iacute;a <span style="color:#ef4444;">*</span></label>
                        <select name="id_categoria" required>
                            <option value="">— Seleccionar —</option>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id_categoria'] ?>">
                                    <?= htmlspecialchars($cat['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" placeholder="Marca del producto">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Precio de Venta <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="precio_venta" placeholder="0.00"
                               step="0.01" min="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Stock Actual</label>
                        <input type="number" name="stock_actual" placeholder="0" min="0" value="0">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Stock M&iacute;nimo</label>
                        <input type="number" name="stock_minimo" placeholder="0" min="0" value="0">
                    </div>
                    <div class="form-group" style="grid-column:span 1;"></div>
                </div>
                <div class="form-group">
                    <label>Descripci&oacute;n</label>
                    <textarea name="descripcion" placeholder="Descripción opcional..."></textarea>
                </div>
                <div class="form-group">
                    <label>Imagen del Producto</label>
                    <input type="file" name="imagen" id="input_imagen_crear"
                           accept="image/jpeg,image/png,image/webp,image/gif"
                           onchange="mostrarNombreArchivo(this, 'nombre_archivo_crear')">
                    <span id="nombre_archivo_crear"
                          style="font-size:.75rem;color:#1e3a8a;font-weight:500;margin-top:3px;display:none;"></span>
                    <span style="font-size:.7rem;color:#94a3b8;margin-top:2px;">
                        JPG, PNG, WEBP o GIF &mdash; m&aacute;x. 2MB
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>


<!-- ═══════ MODAL EDITAR ═══════ -->
<div class="modal-overlay" id="modalEditar">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-pen" style="margin-right:.5rem;"></i>Editar Producto</h3>
            <button class="modal-close" onclick="cerrarModal('modalEditar')"><i class="fas fa-times"></i></button>
        </div>
        <form action="/inventory/controllers/adminproductoscontroller.php?accion=editar" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_producto" id="edit_id">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nombre" id="edit_nombre" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Categor&iacute;a <span style="color:#ef4444;">*</span></label>
                        <select name="id_categoria" id="edit_categoria" required>
                            <?php foreach ($categorias as $cat): ?>
                                <option value="<?= $cat['id_categoria'] ?>">
                                    <?= htmlspecialchars($cat['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Marca</label>
                        <input type="text" name="marca" id="edit_marca">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Precio de Venta <span style="color:#ef4444;">*</span></label>
                        <input type="number" name="precio_venta" id="edit_precio"
                               step="0.01" min="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Stock Actual</label>
                        <input type="number" name="stock_actual" id="edit_stock_actual" min="0">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Stock M&iacute;nimo</label>
                        <input type="number" name="stock_minimo" id="edit_stock_minimo" min="0">
                    </div>
                    <div class="form-group" style="grid-column:span 1;"></div>
                </div>
                <div class="form-group">
                    <label>Descripci&oacute;n</label>
                    <textarea name="descripcion" id="edit_descripcion"></textarea>
                </div>
                <div class="form-group">
                    <label>Imagen del Producto</label>
                    <div id="edit_img_preview_wrap" style="margin-bottom:.5rem;display:none;">
                        <img id="edit_img_preview" src="" alt="Imagen actual"
                             style="height:60px;width:auto;border-radius:8px;
                                    border:1px solid #e2e8f0;object-fit:cover;">
                        <div style="font-size:.7rem;color:#94a3b8;margin-top:2px;">Imagen actual</div>
                    </div>
                    <input type="hidden" name="imagen_actual" id="edit_imagen_actual">
                    <input type="file" name="imagen" accept="image/jpeg,image/png,image/webp,image/gif"
                           onchange="mostrarNombreArchivo(this, 'nombre_archivo_editar')">
                    <span id="nombre_archivo_editar"
                          style="font-size:.75rem;color:#1e3a8a;font-weight:500;margin-top:3px;display:none;"></span>
                    <span style="font-size:.7rem;color:#94a3b8;margin-top:2px;">
                        Dejar vac&iacute;o para conservar la imagen actual
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Actualizar</button>
            </div>
        </form>
    </div>
</div>


<!-- ═══════ MODAL CONFIRMAR ELIMINAR ═══════ -->
<div class="modal-overlay" id="modalEliminar" style="align-items:center;">
    <div class="confirm-box">
        <div style="padding:1.75rem 1.5rem 1rem;text-align:center;">
            <div class="confirm-icon-del"><i class="fas fa-trash"></i></div>
            <h3 style="font-size:1rem;font-weight:700;color:#1e293b;margin-bottom:.4rem;">
                &iquest;Eliminar producto?
            </h3>
            <p style="font-size:.83rem;color:#64748b;" id="confirmNombre"></p>
            <p style="font-size:.78rem;color:#94a3b8;margin-top:.3rem;">
                Esta acci&oacute;n no se puede deshacer.
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


<script>
function abrirModal(id) {
    if (id === 'modalCrear') document.querySelector('#modalCrear form').reset();
    document.getElementById(id).classList.add('open');
}

function cerrarModal(id) {
    document.getElementById(id).classList.remove('open');
}

function editarDesdeCard(btn) {
    const d = btn.dataset;
    abrirModalEditar(
        d.id, d.categoria, d.nombre, d.marca,
        d.stockActual, d.stockMinimo, d.precio,
        d.descripcion, d.imagen
    );
}

function eliminarDesdeCard(btn) {
    confirmarEliminar(btn.dataset.id, btn.dataset.nombre);
}

function abrirModalEditar(id, idCat, nombre, marca, stockActual, stockMinimo, precio, descripcion, imagen) {
    document.getElementById('edit_id').value            = id;
    document.getElementById('edit_nombre').value        = nombre;
    document.getElementById('edit_categoria').value     = idCat;
    document.getElementById('edit_marca').value         = marca;
    document.getElementById('edit_precio').value        = precio;
    document.getElementById('edit_stock_actual').value  = stockActual;
    document.getElementById('edit_stock_minimo').value  = stockMinimo;
    document.getElementById('edit_descripcion').value   = descripcion;
    document.getElementById('edit_imagen_actual').value = imagen;

    // Limpiar nombre de archivo anterior
    const span = document.getElementById('nombre_archivo_editar');
    if (span) { span.textContent = ''; span.style.display = 'none'; }

    // Mostrar u ocultar imagen actual según el producto
    const wrap    = document.getElementById('edit_img_preview_wrap');
    const preview = document.getElementById('edit_img_preview');
    if (wrap && preview) {
        if (imagen) {
            preview.src        = '/inventory/img/productos/' + imagen;
            wrap.style.display = 'block';
        } else {
            preview.src        = '';
            wrap.style.display = 'none';
        }
    }

    document.getElementById('modalEditar').classList.add('open');
}

function confirmarEliminar(id, nombre) {
    document.getElementById('confirmNombre').textContent = nombre;
    document.getElementById('btnConfirmarEliminar').href =
        '/inventory/controllers/adminproductoscontroller.php?accion=eliminar&id=' + id;
    document.getElementById('modalEliminar').classList.add('open');
}

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape')
        document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
});

function filtrarGrid() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    let visibles = 0;
    document.querySelectorAll('#prodGrid .prod-card').forEach(card => {
        const mostrar = card.dataset.nombre.includes(q);
        card.style.display = mostrar ? '' : 'none';
        if (mostrar) visibles++;
    });

    // Mensaje de no encontrado
    let noResult = document.getElementById('noResultMsg');
    if (!noResult) {
        noResult = document.createElement('div');
        noResult.id = 'noResultMsg';
        noResult.style.cssText = 'grid-column:1/-1;text-align:center;padding:2.5rem;color:#94a3b8;';
        noResult.innerHTML = '<i class="fas fa-search" style="font-size:2rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>' +
                             '<p style="font-size:.9rem;font-weight:600;color:#64748b;">Producto no encontrado</p>' +
                             '<p style="font-size:.8rem;margin-top:.25rem;">No existe ning\u00fan producto con ese nombre.</p>';
        document.getElementById('prodGrid').appendChild(noResult);
    }
    noResult.style.display = visibles === 0 && q !== '' ? 'block' : 'none';
}

function mostrarNombreArchivo(input, spanId) {
    const span = document.getElementById(spanId);
    if (input.files && input.files[0]) {
        span.textContent  = '✓ ' + input.files[0].name;
        span.style.display = 'block';
    } else {
        span.textContent  = '';
        span.style.display = 'none';
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>






