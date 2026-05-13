<?php
/**
 * VISTA: Dashboard Administrador
 * Cargada por: controllers/dashboardadmincontroller.php
 */
if (!isset($totalUsuarios)) {
    header("Location: /inventory/controllers/dashboardadmincontroller.php");
    exit;
}

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<style>
.stat-card {
    border-radius:14px; padding:1.35rem 1.4rem; color:white;
    position:relative; overflow:hidden; transition:transform .2s, box-shadow .2s;
}
.stat-card:hover { transform:translateY(-3px); box-shadow:0 14px 32px rgba(0,0,0,.18); }
.stat-card::after {
    content:''; position:absolute; top:-18px; right:-18px;
    width:90px; height:90px; background:rgba(255,255,255,.08); border-radius:50%;
}
.sc-label  { font-size:.78rem; opacity:.82; font-weight:500; margin-bottom:.3rem; }
.sc-value  { font-size:2rem; font-weight:800; line-height:1; }
.sc-footer { font-size:.72rem; opacity:.75; margin-top:.5rem; display:flex; align-items:center; gap:.3rem; }
.sc-icon {
    position:absolute; top:1.1rem; right:1.1rem;
    width:42px; height:42px; background:rgba(255,255,255,.15);
    border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.15rem;
}
.dash-card {
    background:white; border-radius:14px; border:1px solid #e2e8f0;
    box-shadow:0 1px 4px rgba(0,0,0,.05); padding:1.35rem 1.4rem; transition:box-shadow .2s;
}
.dash-card:hover { box-shadow:0 4px 18px rgba(0,0,0,.08); }
.card-title {
    font-size:.92rem; font-weight:700; color:#1e293b;
    margin-bottom:1rem; display:flex; align-items:center; gap:.45rem;
}
.card-title i { color:#1e3a8a; }
.qa-btn {
    display:flex; flex-direction:column; align-items:center; justify-content:center;
    gap:.45rem; padding:1rem .5rem; border-radius:12px; text-decoration:none;
    font-size:.78rem; font-weight:600; color:#374151;
    border:none; background:none; cursor:pointer; transition:transform .2s; width:100%;
}
.qa-btn:hover { transform:translateY(-2px); color:#374151; }
.qa-icon {
    width:42px; height:42px; border-radius:11px;
    display:flex; align-items:center; justify-content:center;
    font-size:1.1rem; transition:transform .2s;
}
.qa-btn:hover .qa-icon { transform:scale(1.1); }
.u-table { width:100%; border-collapse:collapse; }
.u-table th {
    font-size:.7rem; font-weight:700; color:#94a3b8;
    text-transform:uppercase; letter-spacing:.5px;
    padding:.5rem .75rem; text-align:left; border-bottom:1px solid #f1f5f9;
}
.u-table td {
    padding:.65rem .75rem; font-size:.83rem; color:#374151;
    border-bottom:1px solid #f8fafc; vertical-align:middle;
}
.u-table tr:last-child td { border-bottom:none; }
.u-table tr:hover td { background:#f8fafc; }
.avatar-sm {
    width:32px; height:32px; border-radius:50%;
    background:#11225a;
    display:inline-flex; align-items:center; justify-content:center;
    color:white; font-size:.75rem; font-weight:700; flex-shrink:0;
}
.role-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.18rem .6rem; border-radius:20px; font-size:.7rem; font-weight:600;
}
.role-admin    { background:#dbeafe; color:#1e3a8a; }
.role-vendedor { background:#dbeafe; color:#2563eb; }
.stock-alert {
    display:flex; align-items:center; gap:.75rem;
    padding:.7rem .85rem; border-radius:10px;
    background:#fef9c3; border:1px solid #fde68a;
    font-size:.82rem; color:#92400e;
}
.stock-alert i { color:#d97706; flex-shrink:0; }
.g4  { display:grid; grid-template-columns:repeat(4,1fr); gap:1.1rem; }
.g3  { display:grid; grid-template-columns:repeat(3,1fr); gap:1.1rem; }
.g2  { display:grid; grid-template-columns:repeat(2,1fr); gap:1.1rem; }
.gqa { display:grid; grid-template-columns:repeat(5,1fr); gap:.65rem; }
.gap { display:flex; flex-direction:column; gap:1.2rem; }
@media(max-width:1100px){ .g4{grid-template-columns:repeat(2,1fr);} .gqa{grid-template-columns:repeat(3,1fr);} }
@media(max-width:768px) { .g4{grid-template-columns:1fr;} .g3{grid-template-columns:1fr;} .g2{grid-template-columns:1fr;} .gqa{grid-template-columns:repeat(2,1fr);} }
</style>

<div class="gap">

    <!-- Bienvenida -->
    <div class="dash-card" style="background:#11225a;border:none;color:white;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
            <div>
                <p style="font-size:.78rem;opacity:.65;margin-bottom:.25rem;">
                    <?php
                    $dias   = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
                    $meses  = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
                    echo $dias[date('w')] . ', ' . date('d') . ' de ' . $meses[date('n')-1] . ' de ' . date('Y');
                    ?>
                </p>
                <h2 style="font-size:1.45rem;font-weight:800;margin:0;">
                    Bienvenido, <?= htmlspecialchars($nombre) ?>
                </h2>
                <p style="font-size:.85rem;opacity:.72;margin-top:.3rem;">
                    Panel de administraci&oacute;n &mdash; Sistema de Inventario
                </p>
            </div>
            <a href="/inventory/controllers/adminusuariocontroller.php?accion=index"
               style="display:inline-flex;align-items:center;gap:.4rem;
                      background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);
                      color:white;text-decoration:none;border-radius:9px;
                      padding:.45rem 1rem;font-size:.8rem;font-weight:600;">
                <i class="fas fa-users"></i> Gestionar Usuarios
            </a>
        </div>
    </div>

    <!-- Stats principales -->
    <div class="g4">
        <div class="stat-card" style="background:#11225a;">
            <div class="sc-icon"><i class="fas fa-box-open"></i></div>
            <div class="sc-label">Total Productos</div>
            <div class="sc-value"><?= number_format($totalProductos) ?></div>
            <div class="sc-footer">
                <?php if ($stockBajo > 0): ?>
                    <i class="fas fa-triangle-exclamation" style="color:#fbbf24;"></i> <?= $stockBajo ?> con stock bajo
                <?php else: ?>
                    <i class="fas fa-check-circle"></i> Stock en orden
                <?php endif; ?>
            </div>
        </div>
        <div class="stat-card" style="background:linear-gradient(135deg,#3b82f6,#1e3a8a);">
            <div class="sc-icon"><i class="fas fa-users"></i></div>
            <div class="sc-label">Usuarios Registrados</div>
            <div class="sc-value"><?= $totalUsuarios ?></div>
            <div class="sc-footer">
                <i class="fas fa-user-shield"></i> <?= $totalAdmins ?> admin
                &nbsp;&middot;&nbsp;
                <i class="fas fa-user-tag"></i> <?= $totalVendedores ?> vendedor<?= $totalVendedores !== 1 ? 'es' : '' ?>
            </div>
        </div>
    </div>

    <!-- Stats secundarias -->
    <div class="g3">
        <div class="dash-card" style="display:flex;align-items:center;gap:1rem;">
            <div style="width:48px;height:48px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;color:#1e3a8a;font-size:1.3rem;flex-shrink:0;">
                <i class="fas fa-tags"></i>
            </div>
            <div>
                <div style="font-size:.78rem;color:#94a3b8;font-weight:500;">Categor&iacute;as</div>
                <div style="font-size:1.6rem;font-weight:800;color:#1e293b;line-height:1.1;"><?= $totalCategorias ?></div>
            </div>
        </div>
        <div class="dash-card" style="display:flex;align-items:center;gap:1rem;">
            <div style="width:48px;height:48px;border-radius:12px;background:#dcfce7;display:flex;align-items:center;justify-content:center;color:#16a34a;font-size:1.3rem;flex-shrink:0;">
                <i class="fas fa-user-tie"></i>
            </div>
            <div>
                <div style="font-size:.78rem;color:#94a3b8;font-weight:500;">Administradores</div>
                <div style="font-size:1.6rem;font-weight:800;color:#1e293b;line-height:1.1;"><?= $totalAdmins ?></div>
            </div>
        </div>
        <div class="dash-card" style="display:flex;align-items:center;gap:1rem;">
            <div style="width:48px;height:48px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;color:#2563eb;font-size:1.3rem;flex-shrink:0;">
                <i class="fas fa-user-tag"></i>
            </div>
            <div>
                <div style="font-size:.78rem;color:#94a3b8;font-weight:500;">Vendedores</div>
                <div style="font-size:1.6rem;font-weight:800;color:#1e293b;line-height:1.1;"><?= $totalVendedores ?></div>
            </div>
        </div>
    </div>

    <!-- Acciones rapidas -->
    <div class="dash-card">
        <div class="card-title"><i class="fas fa-bolt"></i> Acciones R&aacute;pidas</div>
        <div class="gqa">
            <a href="/inventory/controllers/adminusuariocontroller.php?accion=index" class="qa-btn">
                <div class="qa-icon" style="background:#dbeafe;color:#1e3a8a;"><i class="fas fa-users"></i></div>
                Usuarios
            </a>
            <a href="/inventory/controllers/adminproductoscontroller.php?accion=index" class="qa-btn">
                <div class="qa-icon" style="background:#dbeafe;color:#2563eb;"><i class="fas fa-plus-circle"></i></div>
                Nuevo Producto
            </a>
            <a href="/inventory/controllers/admincategoriascontroller.php?accion=index" class="qa-btn">
                <div class="qa-icon" style="background:#dcfce7;color:#16a34a;"><i class="fas fa-tags"></i></div>
                Nueva Categor&iacute;a
            </a>
            <a href="/inventory/controllers/admincomprascontroller.php?accion=index" class="qa-btn">
                <div class="qa-icon" style="background:#fef3c7;color:#d97706;"><i class="fas fa-cart-flatbed"></i></div>
                Nueva Compra
            </a>
        </div>
    </div>

    <!-- Ultimos usuarios + Alertas -->
    <div class="g2">

        <div class="dash-card">
            <div class="card-title">
                <i class="fas fa-user-clock"></i> &Uacute;ltimos Usuarios
                <a href="/inventory/controllers/adminusuariocontroller.php?accion=index"
                   style="margin-left:auto;font-size:.75rem;color:#1e3a8a;text-decoration:none;font-weight:600;">
                    Ver todos <i class="fas fa-arrow-right" style="font-size:.65rem;"></i>
                </a>
            </div>
            <?php if (empty($ultimosUsuarios)): ?>
                <p style="font-size:.85rem;color:#94a3b8;text-align:center;padding:1.5rem 0;">
                    No hay usuarios registrados a&uacute;n.
                </p>
            <?php else: ?>
                <table class="u-table">
                    <thead>
                        <tr><th>Usuario</th><th>Correo</th><th>Rol</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimosUsuarios as $u):
                            $nombreU   = $u['nombre'] ?? '—';
                            $iniciales = strtoupper(substr($nombreU, 0, 2));
                            $esAdmin   = ($u['rol'] ?? '') === 'administrador';
                        ?>
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:.6rem;">
                                    <div class="avatar-sm"><?= htmlspecialchars($iniciales) ?></div>
                                    <span style="font-weight:600;font-size:.83rem;"><?= htmlspecialchars($nombreU) ?></span>
                                </div>
                            </td>
                            <td style="color:#64748b;"><?= htmlspecialchars($u['correo'] ?? '') ?></td>
                            <td>
                                <span class="role-badge <?= $esAdmin ? 'role-admin' : 'role-vendedor' ?>">
                                    <i class="fas <?= $esAdmin ? 'fa-user-shield' : 'fa-user-tag' ?>" style="font-size:.6rem;"></i>
                                    <?= htmlspecialchars(ucfirst($u['rol'] ?? '')) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div style="display:flex;flex-direction:column;gap:1.1rem;">
            <div class="dash-card">
                <div class="card-title">
                    <i class="fas fa-triangle-exclamation" style="color:#f59e0b;"></i> Alertas del Sistema
                </div>
                <?php if ($stockBajo > 0): ?>
                    <div class="stock-alert">
                        <i class="fas fa-box-open"></i>
                        <div>
                            <strong><?= $stockBajo ?> producto<?= $stockBajo !== 1 ? 's' : '' ?></strong>
                            con stock igual o por debajo del m&iacute;nimo.
                            <a href="/inventory/controllers/adminproductoscontroller.php?accion=index" style="color:#92400e;font-weight:700;margin-left:.3rem;">
                                Ver &rarr;
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <div style="display:flex;align-items:center;gap:.6rem;padding:.7rem .85rem;
                                border-radius:10px;background:#dcfce7;border:1px solid #bbf7d0;
                                font-size:.82rem;color:#166534;">
                        <i class="fas fa-check-circle" style="color:#16a34a;"></i>
                        Todo el stock est&aacute; en niveles normales.
                    </div>
                <?php endif; ?>
            </div>

            <div class="dash-card">
                <div class="card-title"><i class="fas fa-chart-pie"></i> Resumen General</div>
                <div style="display:flex;flex-direction:column;gap:.6rem;">
                    <?php
                    $items = [
                        ['label' => 'Productos en inventario', 'value' => $totalProductos,  'icon' => 'fa-box-open', 'color' => '#1e3a8a'],
                        ['label' => 'Categor&iacute;as',       'value' => $totalCategorias, 'icon' => 'fa-tags',     'color' => '#10b981'],
                        ['label' => 'Usuarios del sistema',    'value' => $totalUsuarios,   'icon' => 'fa-users',    'color' => '#3b82f6'],
                    ];
                    foreach ($items as $item): ?>
                        <div style="display:flex;align-items:center;justify-content:space-between;
                                    padding:.5rem .6rem;border-radius:8px;background:#f8fafc;">
                            <div style="display:flex;align-items:center;gap:.6rem;font-size:.82rem;color:#475569;">
                                <i class="fas <?= $item['icon'] ?>" style="color:<?= $item['color'] ?>;width:16px;text-align:center;"></i>
                                <?= $item['label'] ?>
                            </div>
                            <span style="font-size:.85rem;font-weight:700;color:#1e293b;"><?= $item['value'] ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

</div><!-- end gap -->

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>






