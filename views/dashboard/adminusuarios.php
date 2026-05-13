<?php
/**
 * VISTA: Gestión de Usuarios
 * Cargada por: controllers/adminusuariocontroller.php?accion=index
 * Variables: $usuarios, $total, $admins, $vendedores, $alert
 */
if (!isset($usuarios)) {
    header("Location: /inventory/controllers/adminusuariocontroller.php?accion=index");
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

$titulo = "Gestión de Usuarios";
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

table.users { width:100%; border-collapse:collapse; }
table.users th {
    font-size:.7rem; font-weight:700; color:#94a3b8;
    text-transform:uppercase; letter-spacing:.5px;
    padding:.65rem 1rem; text-align:left;
    background:#f8fafc; border-bottom:1px solid #f1f5f9;
}
table.users td {
    padding:.7rem 1rem; font-size:.83rem; color:#374151;
    border-bottom:1px solid #f8fafc; vertical-align:middle;
}
table.users tr:last-child td { border-bottom:none; }
table.users tbody tr:hover td { background:#fafbff; }
table.users tr.inactivo td   { opacity:.5; }

.avatar {
    width:34px; height:34px; border-radius:50%;
    background:#11225a;
    display:inline-flex; align-items:center; justify-content:center;
    color:white; font-size:.75rem; font-weight:700; flex-shrink:0;
}
.role-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.2rem .65rem; border-radius:20px; font-size:.7rem; font-weight:600;
}
.role-admin    { background:#dbeafe; color:#1e3a8a; }
.role-vendedor { background:#dbeafe; color:#2563eb; }

.estado-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.2rem .65rem; border-radius:20px; font-size:.7rem; font-weight:600;
}
.estado-activo   { background:#dcfce7; color:#16a34a; }
.estado-inactivo { background:#fee2e2; color:#dc2626; }

.btn-action {
    width:30px; height:30px; border-radius:7px; border:none;
    display:inline-flex; align-items:center; justify-content:center;
    font-size:.8rem; cursor:pointer; transition:all .2s; text-decoration:none;
}
.btn-edit     { background:#dbeafe; color:#1e3a8a; }
.btn-edit:hover     { background:#bfdbfe; }
.btn-activate   { background:#dcfce7; color:#16a34a; }
.btn-activate:hover { background:#bbf7d0; }
.btn-deactivate { background:#fee2e2; color:#dc2626; }
.btn-deactivate:hover { background:#fecaca; }

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

.form-group { display:flex; flex-direction:column; gap:.35rem; margin-bottom:.6rem; }
.form-group label { font-size:.78rem; font-weight:600; color:#374151; }
.form-group input,
.form-group select {
    border:1px solid #e2e8f0; border-radius:8px;
    padding:.5rem .75rem; font-size:.85rem; color:#1e293b;
    outline:none; transition:border .2s; background:white;
}
.form-group input:focus,
.form-group select:focus { border-color:#1e3a8a; box-shadow:0 0 0 3px rgba(29,78,216,.1); }
.form-hint { font-size:.7rem; color:#94a3b8; margin-top:2px; }

.correo-msg {
    font-size:.75rem; font-weight:600; margin-top:.2rem;
    display:flex; align-items:center; gap:.3rem;
    height:1rem; overflow:hidden;
}
.correo-msg.error { color:#dc2626; }
.correo-msg.ok    { color:#16a34a; }

.alert-bar {
    padding:.75rem 1rem; border-radius:10px; font-size:.83rem; font-weight:500;
    display:flex; align-items:center; gap:.6rem;
}
.alert-success { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }
.alert-danger  { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }

.g3  { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
.gap { display:flex; flex-direction:column; gap:1.1rem; }

@media(max-width:768px){ .g3 { grid-template-columns:1fr; } }
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
            <h2><i class="fas fa-users" style="margin-right:.5rem;opacity:.8;"></i>Gesti&oacute;n de Usuarios</h2>
            <p>Administra los usuarios del sistema de inventario</p>
        </div>
        <button class="btn-primary" onclick="abrirModal('modalCrear')">
            <i class="fas fa-user-plus"></i> Nuevo Usuario
        </button>
    </div>

    <!-- Mini stats -->
    <div class="g3">
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:#dbeafe;color:#1e3a8a;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div class="mini-stat-val"><?= $total ?></div>
                <div class="mini-stat-lbl">Total usuarios</div>
            </div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:#dcfce7;color:#16a34a;">
                <i class="fas fa-user-shield"></i>
            </div>
            <div>
                <div class="mini-stat-val"><?= $admins ?></div>
                <div class="mini-stat-lbl">Administradores</div>
            </div>
        </div>
        <div class="mini-stat">
            <div class="mini-stat-icon" style="background:#dbeafe;color:#2563eb;">
                <i class="fas fa-user-tag"></i>
            </div>
            <div>
                <div class="mini-stat-val"><?= $vendedores ?></div>
                <div class="mini-stat-lbl">Vendedores</div>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="u-wrap">
        <div class="u-toolbar">
            <div style="font-size:.9rem;font-weight:700;color:#1e293b;">
                <i class="fas fa-list" style="color:#1e3a8a;margin-right:.4rem;"></i>
                Lista de Usuarios
            </div>
            <div class="u-search">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar usuario..." oninput="filtrarTabla()">
            </div>
        </div>

        <table class="users" id="tablaUsuarios">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Contrase&ntilde;a</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:2.5rem;color:#94a3b8;">
                            <i class="fas fa-users" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>
                            No hay usuarios registrados
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $i => $u):
                        $nombreU  = $u['nombre']  ?? '—';
                        $iniciales = strtoupper(substr($nombreU, 0, 2));
                        $esAdmin  = ($u['rol'] ?? '') === 'administrador';
                        $esMismo  = (int)($u['id_usuario'] ?? 0) === (int)($usuario['id_usuario'] ?? 0);
                        $activo   = (int)($u['activo'] ?? 1);
                    ?>
                    <tr class="<?= $activo ? '' : 'inactivo' ?>">
                        <td style="color:#94a3b8;font-size:.75rem;"><?= $i + 1 ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.65rem;">
                                <div class="avatar"><?= htmlspecialchars($iniciales) ?></div>
                                <div>
                                    <div style="font-weight:600;">
                                        <?= htmlspecialchars($nombreU) ?>
                                        <?php if ($esMismo): ?>
                                            <span style="font-size:.65rem;background:#fef3c7;color:#92400e;
                                                         padding:.1rem .4rem;border-radius:4px;margin-left:.3rem;">T&uacute;</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="color:#64748b;"><?= htmlspecialchars($u['correo'] ?? '') ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.4rem;">
                                <span class="pass-mask" data-pass="<?= htmlspecialchars($u['contrasena'] ?? '') ?>">
                                    &bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;
                                </span>
                                <button onclick="togglePass(this)" title="Ver contrase&ntilde;a"
                                    style="background:none;border:none;cursor:pointer;
                                           color:#94a3b8;font-size:.75rem;padding:0;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <span class="role-badge <?= $esAdmin ? 'role-admin' : 'role-vendedor' ?>">
                                <i class="fas <?= $esAdmin ? 'fa-user-shield' : 'fa-user-tag' ?>"
                                   style="font-size:.6rem;"></i>
                                <?= htmlspecialchars(ucfirst($u['rol'] ?? '')) ?>
                            </span>
                        </td>
                        <td>
                            <span class="estado-badge <?= $activo ? 'estado-activo' : 'estado-inactivo' ?>">
                                <i class="fas fa-circle" style="font-size:.45rem;"></i>
                                <?= $activo ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:.4rem;">
                                <!-- Editar -->
                                <button class="btn-action btn-edit" title="Editar"
                                    onclick="abrirModalEditar(
                                        <?= (int)($u['id_usuario'] ?? 0) ?>,
                                        '<?= addslashes($nombreU) ?>',
                                        '<?= addslashes($u['correo'] ?? '') ?>',
                                        '<?= addslashes($u['rol'] ?? '') ?>'
                                    )">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <!-- Activar / Desactivar (no aplica al propio admin) -->
                                <?php if (!$esMismo): ?>
                                <button class="btn-action <?= $activo ? 'btn-deactivate' : 'btn-activate' ?>"
                                   title="<?= $activo ? 'Desactivar' : 'Activar' ?>"
                                   onclick="confirmarToggle(
                                       <?= (int)$u['id_usuario'] ?>,
                                       '<?= addslashes($nombreU) ?>',
                                       <?= $activo ?>
                                   )">
                                    <i class="fas <?= $activo ? 'fa-toggle-on' : 'fa-toggle-off' ?>"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div><!-- end gap -->


<!-- ═══════ MODAL CREAR ═══════ -->
<div class="modal-overlay" id="modalCrear">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus" style="margin-right:.5rem;"></i>Nuevo Usuario</h3>
            <button class="modal-close" onclick="cerrarModal('modalCrear')"><i class="fas fa-times"></i></button>
        </div>
        <form action="/inventory/controllers/adminusuariocontroller.php?accion=crear" method="POST" id="formCrear">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombres y apellidos <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nombre" placeholder="Nombres y apellidos completos" required>
                </div>
                <div class="form-group">
                    <label>Correo electr&oacute;nico <span style="color:#ef4444;">*</span></label>
                    <input type="email" name="correo" id="crear_correo"
                           placeholder="usuario@correo.com" required
                           oninput="verificarCorreo(this, 'crear_correo_msg', 0)">
                    <span id="crear_correo_msg" class="correo-msg"></span>
                </div>
                <div class="form-group">
                    <label>Contrase&ntilde;a <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="password" placeholder="Contrase&ntilde;a" required>
                </div>
                <div class="form-group">
                    <label>Rol <span style="color:#ef4444;">*</span></label>
                    <select name="rol" required>
                        <option value="">— Seleccionar rol —</option>
                        <option value="administrador">Administrador</option>
                        <option value="vendedor">Vendedor</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="cerrarModal('modalCrear')">Cancelar</button>
                <button type="submit" id="btnGuardarCrear" class="btn-primary"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</div>


<!-- ═══════ MODAL EDITAR ═══════ -->
<div class="modal-overlay" id="modalEditar">
    <div class="modal-box">
        <div class="modal-header">
            <h3><i class="fas fa-pen" style="margin-right:.5rem;"></i>Editar Usuario</h3>
            <button class="modal-close" onclick="cerrarModal('modalEditar')"><i class="fas fa-times"></i></button>
        </div>
        <form action="/inventory/controllers/adminusuariocontroller.php?accion=editar" method="POST">
            <input type="hidden" name="id_usuario" id="edit_id">
            <div class="modal-body">
                <div class="form-group">
                    <label>Nombre <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="nombre" id="edit_nombre" required>
                </div>
                <div class="form-group">
                    <label>Correo electr&oacute;nico <span style="color:#ef4444;">*</span></label>
                    <input type="email" name="correo" id="edit_correo" required
                           oninput="verificarCorreo(this, 'edit_correo_msg', document.getElementById('edit_id').value)">
                    <span id="edit_correo_msg" class="correo-msg"></span>
                </div>
                <div class="form-group">
                    <label>Nueva contrase&ntilde;a</label>
                    <input type="text" name="password" placeholder="Dejar vac&iacute;o para no cambiar">
                    <span class="form-hint">Solo completa si deseas cambiarla</span>
                </div>
                <div class="form-group">
                    <label>Rol <span style="color:#ef4444;">*</span></label>
                    <select name="rol" id="edit_rol" required>
                        <option value="administrador">Administrador</option>
                        <option value="vendedor">Vendedor</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="cerrarModal('modalEditar')">Cancelar</button>
                <button type="submit" id="btnGuardarEditar" class="btn-primary"><i class="fas fa-save"></i> Actualizar</button>
            </div>
        </form>
    </div>
</div>


<!-- ═══════ MODAL TOGGLE ESTADO ═══════ -->
<div class="modal-overlay" id="modalToggle">
    <div class="modal-box" style="max-width:380px;">
        <div class="modal-header" id="toggleHeader">
            <h3 id="toggleAccion" style="margin:0;font-size:1rem;font-weight:700;"></h3>
            <button class="modal-close" onclick="cerrarModal('modalToggle')"><i class="fas fa-times"></i></button>
        </div>
        <div style="padding:1.75rem 1.5rem 1rem;text-align:center;">
            <div style="margin:0 auto 1rem;width:58px;height:58px;border-radius:50%;
                        display:flex;align-items:center;justify-content:center;font-size:1.5rem;">
                <span id="toggleIcon">
                    <span class="confirm-icon-inner ban"><i class="fas fa-ban"></i></span>
                </span>
            </div>
            <p style="font-size:.9rem;font-weight:700;color:#1e293b;margin-bottom:.4rem;">
                &iquest;<span id="toggleAccion2"></span> usuario?
            </p>
            <p style="font-size:.83rem;font-weight:600;color:#374151;margin-bottom:.3rem;">
                <span id="toggleNombre"></span>
            </p>
            <p style="font-size:.78rem;color:#94a3b8;" id="toggleMensaje"></p>
        </div>
        <div style="padding:.9rem 1.5rem;border-top:1px solid #f1f5f9;
                    display:flex;justify-content:flex-end;gap:.6rem;">
            <button class="btn-secondary" onclick="cerrarModal('modalToggle')">Cancelar</button>
            <a id="toggleBtn" href="#"
               style="display:inline-flex;align-items:center;gap:.4rem;
                      color:white;border-radius:9px;padding:.5rem 1.1rem;
                      font-size:.83rem;font-weight:600;text-decoration:none;transition:background .2s;">
            </a>
        </div>
    </div>
</div>

<style>
.confirm-icon-inner {
    width:58px;height:58px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;font-size:1.4rem;
}
.confirm-icon-inner.ban      { background:#fee2e2;color:#dc2626; }
.confirm-icon-inner.activate { background:#dcfce7;color:#16a34a; }
</style>

<script>
let correoTimer = null;

// ── Verificar correo en tiempo real ─────────────────────────
function verificarCorreo(input, msgId, excluirId) {
    const msg    = document.getElementById(msgId);
    const btnId  = msgId.startsWith('crear') ? 'btnGuardarCrear' : 'btnGuardarEditar';
    const btn    = document.getElementById(btnId);
    const correo = input.value.trim();

    if (!correo) {
        msg.textContent = '';
        msg.className   = 'correo-msg';
        input.style.borderColor = '';
        input.style.boxShadow   = '';
        if (btn) btn.disabled = false;
        return;
    }

    // Validar formato de correo (debe tener @ y dominio)
    const formatoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(correo);
    if (!formatoValido) {
        msg.innerHTML           = '<i class="fas fa-exclamation-circle"></i> Ingresa un correo válido con @.';
        msg.className           = 'correo-msg error';
        input.style.borderColor = '#ef4444';
        input.style.boxShadow   = '0 0 0 3px rgba(239,68,68,.1)';
        if (btn) btn.disabled   = true;
        return;
    }

    msg.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando...';
    msg.className = 'correo-msg';
    if (btn) btn.disabled = true;

    clearTimeout(correoTimer);
    correoTimer = setTimeout(() => {
        fetch('/inventory/controllers/verificarcorreo.php?correo=' + encodeURIComponent(correo) + '&excluir=' + (excluirId || 0))
            .then(r => r.json())
            .then(data => {
                if (data.existe) {
                    msg.innerHTML           = '<i class="fas fa-exclamation-circle"></i> Este correo ya está registrado.';
                    msg.className           = 'correo-msg error';
                    input.style.borderColor = '#ef4444';
                    input.style.boxShadow   = '0 0 0 3px rgba(239,68,68,.1)';
                    if (btn) btn.disabled   = true;
                } else {
                    msg.innerHTML           = '<i class="fas fa-check-circle"></i> Correo disponible.';
                    msg.className           = 'correo-msg ok';
                    input.style.borderColor = '#22c55e';
                    input.style.boxShadow   = '0 0 0 3px rgba(34,197,94,.1)';
                    if (btn) btn.disabled   = false;
                }
            })
            .catch(() => {
                msg.textContent = '';
                msg.className   = 'correo-msg';
                if (btn) btn.disabled = false;
            });
    }, 500);
}

// ── Modales ──────────────────────────────────────────────────
function abrirModal(id) {
    if (id === 'modalCrear') {
        const input = document.getElementById('crear_correo');
        const msg   = document.getElementById('crear_correo_msg');
        const btn   = document.getElementById('btnGuardarCrear');
        if (input) { input.value = ''; input.style.borderColor = ''; input.style.boxShadow = ''; }
        if (msg)   { msg.textContent = ''; msg.className = 'correo-msg'; }
        if (btn)   btn.disabled = false;
        // Limpiar el formulario completo
        document.getElementById('formCrear').reset();
    }
    document.getElementById(id).classList.add('open');
}

function cerrarModal(id) {
    document.getElementById(id).classList.remove('open');
}

function abrirModalEditar(id, nombre, correo, rol) {
    document.getElementById('edit_id').value     = id;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_correo').value = correo;
    document.getElementById('edit_rol').value    = rol;
    // Limpiar validación de correo
    const msg   = document.getElementById('edit_correo_msg');
    const input = document.getElementById('edit_correo');
    const btn   = document.getElementById('btnGuardarEditar');
    if (msg)   { msg.textContent = ''; msg.className = 'correo-msg'; }
    if (input) { input.style.borderColor = ''; input.style.boxShadow = ''; }
    if (btn)   btn.disabled = false;
    document.getElementById('modalEditar').classList.add('open');
}

// ── Toggle estado ────────────────────────────────────────────
function confirmarToggle(id, nombre, activo) {
    const esDesact = activo == 1;
    const accion   = esDesact ? 'Desactivar' : 'Activar';

    document.getElementById('toggleNombre').textContent  = nombre;
    document.getElementById('toggleAccion').textContent  = accion;
    document.getElementById('toggleAccion2').textContent = accion;
    document.getElementById('toggleMensaje').textContent = esDesact
        ? 'El usuario no podrá acceder al sistema mientras esté desactivado.'
        : 'El usuario podrá volver a acceder al sistema.';

    const iconEl = document.getElementById('toggleIcon');
    const btnEl  = document.getElementById('toggleBtn');

    if (esDesact) {
        iconEl.className       = 'confirm-icon-inner ban';
        iconEl.innerHTML       = '<i class="fas fa-toggle-off"></i>';
        btnEl.style.background = '#dc2626';
        btnEl.onmouseover      = () => btnEl.style.background = '#b91c1c';
        btnEl.onmouseout       = () => btnEl.style.background = '#dc2626';
    } else {
        iconEl.className       = 'confirm-icon-inner activate';
        iconEl.innerHTML       = '<i class="fas fa-toggle-on"></i>';
        btnEl.style.background = '#16a34a';
        btnEl.onmouseover      = () => btnEl.style.background = '#15803d';
        btnEl.onmouseout       = () => btnEl.style.background = '#16a34a';
    }

    btnEl.innerHTML = '<i class="fas ' + (esDesact ? 'fa-toggle-off' : 'fa-toggle-on') + '"></i> ' + accion;
    btnEl.href      = '/inventory/controllers/adminusuariocontroller.php?accion=toggleEstado&id=' + id;
    document.getElementById('modalToggle').classList.add('open');
}

// ── Ver/ocultar contraseña ───────────────────────────────────
function togglePass(btn) {
    const span = btn.previousElementSibling;
    const icon = btn.querySelector('i');
    if (span.dataset.visible === '1') {
        span.textContent    = '••••••••';
        icon.className      = 'fas fa-eye';
        span.dataset.visible = '0';
    } else {
        span.textContent    = span.dataset.pass;
        icon.className      = 'fas fa-eye-slash';
        span.dataset.visible = '1';
    }
}

// ── Búsqueda en tiempo real ──────────────────────────────────
function filtrarTabla() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('#tablaUsuarios tbody tr').forEach(tr => {
        tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}

// ── Cerrar modales ───────────────────────────────────────────
document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape')
        document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>






