<?php
/**
 * VISTA: Gestión de Categorías
 * Cargada por: controllers/admincategoriascontroller.php?accion=index
 * Variables: $categorias, $total, $alert
 */
if (!isset($categorias)) {
    header("Location: /inventory/controllers/admincategoriascontroller.php?accion=index");
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

$titulo = "Gestión de Categorías";
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

table.cats { width:100%; border-collapse:collapse; }
table.cats th {
    font-size:.7rem; font-weight:700; color:#94a3b8;
    text-transform:uppercase; letter-spacing:.5px;
    padding:.65rem 1rem; text-align:left;
    background:#f8fafc; border-bottom:1px solid #f1f5f9;
}
table.cats th:nth-child(1) { width:45px; }
table.cats th:nth-child(2) { width:auto; }
table.cats th:nth-child(3) { width:110px; }
table.cats th:nth-child(4) { width:100px; }table.cats td {
    padding:.75rem 1rem; font-size:.83rem; color:#374151;
    border-bottom:1px solid #f8fafc; vertical-align:middle;
    overflow:hidden;
}
table.cats tr:last-child td { border-bottom:none; }
table.cats tbody tr:hover td { background:#fafbff; }
    padding:.75rem 1rem; font-size:.83rem; color:#374151;
    border-bottom:1px solid #f8fafc; vertical-align:middle;
}
table.cats tr:last-child td { border-bottom:none; }
table.cats tbody tr:hover td { background:#fafbff; }

.cat-icon {
    width:36px; height:36px; border-radius:9px;
    background:linear-gradient(135deg,#10b981,#059669);
    display:inline-flex; align-items:center; justify-content:center;
    color:white; font-size:.85rem; flex-shrink:0;
}

.desc-text {
    color:#64748b; font-size:.8rem;
    display:block; max-width:350px;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}

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
    z-index:2000; align-items:center; justify-content:center; padding:1rem;
}
.modal-overlay.open { display:flex; }
.modal-box {
    background:white; border-radius:16px; width:100%; max-width:460px;
    box-shadow:0 24px 60px rgba(0,0,0,.2);
    animation:modalIn .25s ease-out; overflow:hidden;
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
    display:flex; justify-content:flex-end; gap:.6rem;
}
.form-group { display:flex; flex-direction:column; gap:.35rem; margin-bottom:.85rem; }
.form-group label { font-size:.78rem; font-weight:600; color:#374151; }
.form-group input,
.form-group textarea {
    border:1px solid #e2e8f0; border-radius:8px;
    padding:.5rem .75rem; font-size:.85rem; color:#1e293b;
    outline:none; transition:border .2s; background:white;
    font-family: inherit;
}
.form-group input:focus,
.form-group textarea:focus { border-color:#1e3a8a; box-shadow:0 0 0 3px rgba(29,78,216,.1); }
.form-group textarea { resize:vertical; min-height:80px; }

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

.gap { display:flex; flex-direction:column; gap:1.1rem; }
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
            <h2><i class="fas fa-tags" style="margin-right:.5rem;opacity:.8;"></i>Gesti&oacute;n de Categor&iacute;as</h2>
            <p>Administra las categorías de productos del sistema</p>
        </div>
        <button class="btn-primary" onclick="abrirModal('modalCrear')">
            <i class="fas fa-plus"></i> Nueva Categor&iacute;a
        </button>
    </div>

    <!-- Mini stat -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:#dcfce7;color:#16a34a;">
                <i class="fas fa-tags"></i>
            </div>
            <div>
                <div class="mini-stat-val"><?= $total ?></div>
                <div class="mini-stat-lbl">Total categor&iacute;as</div>
            </div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:#dbeafe;color:#2563eb;">
                <i class="fas fa-box-open"></i>
            </div>
            <div>
                <div class="mini-stat-val"><?= array_sum(array_column($categorias, 'total_productos')) ?></div>
                <div class="mini-stat-lbl">Productos asociados</div>
            </div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:#fef3c7;color:#d97706;">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div>
                <div class="mini-stat-val"><?= date('d/m/Y') ?></div>
                <div class="mini-stat-lbl">Fecha actual</div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="u-wrap">
        <div class="u-toolbar">
            <div style="font-size:.9rem;font-weight:700;color:#1e293b;">
                <i class="fas fa-list" style="color:#1e3a8a;margin-right:.4rem;"></i>
                Lista de Categor&iacute;as
            </div>
            <div class="u-search">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar categoría..." oninput="filtrarTabla()">
            </div>
        </div>

        <!-- Tabla de categorías -->
        <table id="tablaCategorias" style="width:100%;border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;border-bottom:1px solid #f1f5f9;">
                    <th style="padding:.65rem 1rem;font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;text-align:left;width:45px;">#</th>
                    <th style="padding:.65rem 1rem;font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;text-align:left;">Categor&iacute;a</th>
                    <th style="padding:.65rem 1rem;font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;text-align:center;width:130px;">Productos</th>
                    <th style="padding:.65rem 1rem;font-size:.7rem;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;text-align:center;width:100px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categorias)): ?>
                    <tr>
                        <td colspan="4" style="text-align:center;padding:2.5rem;color:#94a3b8;">
                            <i class="fas fa-tags" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>
                            No hay categorías registradas
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categorias as $i => $c):
                        $tp = (int)($c['total_productos'] ?? 0);
                    ?>
                    <tr style="border-bottom:1px solid #f8fafc;" onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background=''">
                        <td style="padding:.75rem 1rem;font-size:.75rem;color:#94a3b8;"><?= $i + 1 ?></td>
                        <td style="padding:.75rem 1rem;">
                            <div style="display:flex;align-items:flex-start;gap:.65rem;">
                                <div class="cat-icon" style="margin-top:2px;flex-shrink:0;"><i class="fas fa-tag"></i></div>
                                <div>
                                    <div style="font-weight:600;font-size:.85rem;color:#1e293b;">
                                        <?= htmlspecialchars($c['nombre']) ?>
                                    </div>
                                    <div style="font-size:.75rem;margin-top:2px;
                                                color:<?= $c['descripcion'] ? '#94a3b8' : '#cbd5e1' ?>;
                                                font-style:<?= $c['descripcion'] ? 'normal' : 'italic' ?>;">
                                        <?= $c['descripcion'] ? htmlspecialchars($c['descripcion']) : 'Sin descripción' ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:.75rem 1rem;text-align:center;">
                            <span style="display:inline-flex;align-items:center;gap:.3rem;
                                         padding:.2rem .65rem;border-radius:20px;font-size:.75rem;font-weight:700;
                                         background:<?= $tp > 0 ? '#dbeafe' : '#f1f5f9' ?>;
                                         color:<?= $tp > 0 ? '#2563eb' : '#94a3b8' ?>;">
                                <i class="fas fa-box" style="font-size:.6rem;"></i> <?= $tp ?>
                            </span>
                        </td>
                        <td style="padding:.75rem 1rem;text-align:center;">
                            <div style="display:inline-flex;gap:.4rem;">
                                <button class="btn-action btn-edit" title="Editar"
                                    onclick="abrirModalEditar(
                                        <?= (int)$c['id_categoria'] ?>,
                                        '<?= addslashes($c['nombre']) ?>',
                                        '<?= addslashes($c['descripcion'] ?? '') ?>'
                                    )">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="btn-action btn-delete" title="Eliminar"
                                    onclick="confirmarEliminar(
                                        <?= (int)$c['id_categoria'] ?>,
                                        '<?= addslashes($c['nombre']) ?>'
                                    )">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div><!-- end u-wrap -->

</div><!-- end gap -->


<!-- ═══════ MODAL CREAR ═══════ -->
<div class="modal-overlay" id="modalCrear">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-plus" style="margin-right:.5rem;"></i>Nueva Categor&iacute;a</h3>
            <button class="modal-close" onclick="cerrarModal('modalCrear')"><i class="fas fa-times"></i></button>
        </div>
        <form action="/inventory/controllers/admincategoriascontroller.php?accion=crear" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nombre" placeholder="Nombre de la categoría" required>
                </div>
                <div class="form-group">
                    <label>Descripci&oacute;n</label>
                    <textarea name="descripcion" placeholder="Descripción opcional..."></textarea>
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
            <h3><i class="fas fa-pen" style="margin-right:.5rem;"></i>Editar Categor&iacute;a</h3>
            <button class="modal-close" onclick="cerrarModal('modalEditar')"><i class="fas fa-times"></i></button>
        </div>
        <form action="/inventory/controllers/admincategoriascontroller.php?accion=editar" method="POST">
            <input type="hidden" name="id_categoria" id="edit_id">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nombre" id="edit_nombre" required>
                </div>
                <div class="form-group">
                    <label>Descripci&oacute;n</label>
                    <textarea name="descripcion" id="edit_descripcion" placeholder="Descripción opcional..."></textarea>
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
<div class="modal-overlay" id="modalEliminar">
    <div class="confirm-box">
        <div style="padding:1.75rem 1.5rem 1rem;text-align:center;">
            <div class="confirm-icon-del"><i class="fas fa-trash"></i></div>
            <h3 style="font-size:1rem;font-weight:700;color:#1e293b;margin-bottom:.4rem;">
                &iquest;Eliminar categor&iacute;a?
            </h3>
            <p style="font-size:.83rem;color:#64748b;" id="confirmNombre"></p>
            <p style="font-size:.78rem;color:#94a3b8;margin-top:.3rem;">
                Solo se puede eliminar si no tiene productos asociados.
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
    if (id === 'modalCrear') {
        document.querySelector('#modalCrear form').reset();
    }
    document.getElementById(id).classList.add('open');
}

function cerrarModal(id) {
    document.getElementById(id).classList.remove('open');
}

function abrirModalEditar(id, nombre, descripcion) {
    document.getElementById('edit_id').value          = id;
    document.getElementById('edit_nombre').value      = nombre;
    document.getElementById('edit_descripcion').value = descripcion;
    document.getElementById('modalEditar').classList.add('open');
}

function confirmarEliminar(id, nombre) {
    document.getElementById('confirmNombre').textContent = nombre;
    document.getElementById('btnConfirmarEliminar').href =
        '/inventory/controllers/admincategoriascontroller.php?accion=eliminar&id=' + id;
    document.getElementById('modalEliminar').classList.add('open');
}

// Cerrar al click fuera
document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});

// Cerrar con Escape
document.addEventListener('keydown', e => {
    if (e.key === 'Escape')
        document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
});

// Búsqueda en tiempo real
function filtrarTabla() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#tablaCategorias tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>






