<?php
/**
 * VISTA: Dashboard Vendedor
 * Cargada por: controllers/vendedorcontroller.php
 * Variables: $nombre, $rol, $correo, $usuario, $titulo
 */
if (!isset($usuario)) {
    header("Location: /inventory/controllers/vendedorcontroller.php");
    exit;
}

require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/sidebar.php';
?>

<style>
    /* Tarjetas stat */
    .stat-card {
        border-radius: 14px;
        padding: 1.4rem 1.5rem;
        color: white;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }
    .stat-card::after {
        content: '';
        position: absolute;
        top: -20px; right: -20px;
        width: 100px; height: 100px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .stat-card-label  { font-size: 0.8rem; opacity: 0.85; font-weight: 500; }
    .stat-card-value  { font-size: 2rem; font-weight: 800; line-height: 1; }
    .stat-card-footer { font-size: 0.75rem; opacity: 0.8; display: flex; align-items: center; gap: 0.3rem; }
    .stat-card-icon {
        position: absolute; top: 1.2rem; right: 1.2rem;
        width: 44px; height: 44px;
        background: rgba(255,255,255,0.15);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
    }

    /* Sección card */
    .dash-card {
        background: white;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        padding: 1.4rem 1.5rem;
        transition: box-shadow 0.2s;
    }
    .dash-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
    .dash-card-title {
        font-size: 0.95rem; font-weight: 700;
        color: #1e293b; margin-bottom: 1.1rem;
        display: flex; align-items: center; gap: 0.5rem;
    }
    .dash-card-title i { color: #1e3a8a; }

    /* Acciones rápidas */
    .quick-action {
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 0.5rem; padding: 1.1rem 0.75rem;
        border-radius: 12px; text-decoration: none;
        font-size: 0.8rem; font-weight: 600;
        color: #374151; border: 1px solid transparent;
        transition: all 0.2s ease; cursor: pointer;
        background: none;
        width: 100%;
    }
    .quick-action:hover { transform: translateY(-2px); }
    .quick-action .qa-icon {
        width: 44px; height: 44px; border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; transition: transform 0.2s;
    }
    .quick-action:hover .qa-icon { transform: scale(1.1); }

    /* Actividad */
    .activity-item {
        display: flex; align-items: center; gap: 0.85rem;
        padding: 0.7rem 0.75rem; border-radius: 10px;
        transition: background 0.15s;
    }
    .activity-item:hover { background: #f8fafc; }
    .activity-icon {
        width: 38px; height: 38px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; flex-shrink: 0;
    }
    .activity-title { font-size: 0.85rem; font-weight: 600; color: #1e293b; }
    .activity-sub   { font-size: 0.75rem; color: #94a3b8; margin-top: 1px; }

    /* Tabla productos */
    .prod-table { width: 100%; border-collapse: collapse; }
    .prod-table th {
        font-size: 0.72rem; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.5px;
        padding: 0.5rem 0.75rem; text-align: left;
        border-bottom: 1px solid #f1f5f9;
    }
    .prod-table td {
        padding: 0.7rem 0.75rem; font-size: 0.85rem;
        color: #374151; border-bottom: 1px solid #f8fafc;
    }
    .prod-table tr:last-child td { border-bottom: none; }
    .prod-table tr:hover td { background: #f8fafc; }
    .badge-stock {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.2rem 0.6rem; border-radius: 20px;
        font-size: 0.72rem; font-weight: 600;
    }
    .badge-ok      { background: #dcfce7; color: #16a34a; }
    .badge-low     { background: #fef9c3; color: #ca8a04; }
    .badge-out     { background: #fee2e2; color: #dc2626; }

    /* Grid helpers */
    .grid-4 { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.1rem; }
    .grid-2 { display: grid; grid-template-columns: repeat(2,1fr); gap: 1.1rem; }
    .grid-qa{ display: grid; grid-template-columns: repeat(4,1fr); gap: 0.75rem; }
    .dash-gap { display: flex; flex-direction: column; gap: 1.25rem; }

    @media (max-width: 1024px) {
        .grid-4  { grid-template-columns: repeat(2,1fr); }
        .grid-qa { grid-template-columns: repeat(2,1fr); }
    }
    @media (max-width: 640px) {
        .grid-4  { grid-template-columns: 1fr; }
        .grid-2  { grid-template-columns: 1fr; }
        .grid-qa { grid-template-columns: repeat(2,1fr); }
    }
</style>

<!-- ===== CONTENIDO ===== -->
<div class="dash-gap">

    <!-- Bienvenida -->
    <div class="dash-card" style="background: #11225a; border:none; color:white;">
        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
            <div>
                <p style="font-size:0.8rem; opacity:0.7; margin-bottom:0.3rem;">
                    <?php
                    $dias  = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
                    $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
                    echo $dias[date('w')] . ', ' . date('d') . ' de ' . $meses[date('n')-1] . ' de ' . date('Y');
                    ?>
                </p>
                <h2 style="font-size:1.5rem; font-weight:800; margin:0;">
                    Hola, <?= htmlspecialchars($nombre) ?> 👋
                </h2>
                <p style="font-size:0.875rem; opacity:0.75; margin-top:0.35rem;">
                    Aquí tienes el resumen de tu actividad de hoy.
                </p>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
            <div class="stat-card-icon"><i class="fas fa-box-open"></i></div>
            <span class="stat-card-label">Productos Disponibles</span>
            <span class="stat-card-value"><?= count($productosDisponibles) ?></span>
            <span class="stat-card-footer"><i class="fas fa-boxes-stacked"></i> En inventario</span>
        </div>

    </div>

    <!-- Acciones rápidas -->
    <div class="dash-card">
        <div class="dash-card-title">
            <i class="fas fa-bolt"></i> Acciones Rápidas
        </div>
        <div class="grid-qa">
            <a href="/inventory/controllers/vendedorventascontroller.php?accion=index" class="quick-action">
                <div class="qa-icon" style="background:#dbeafe; color:#1e3a8a;">
                    <i class="fas fa-plus"></i>
                </div>
                Nueva Venta
            </a>
            <a href="/inventory/controllers/vendedorproductoscontroller.php" class="quick-action">
                <div class="qa-icon" style="background:#dbeafe; color:#2563eb;">
                    <i class="fas fa-search"></i>
                </div>
                Buscar Producto
            </a>
            <a href="/inventory/controllers/vendedorventascontroller.php?accion=index" class="quick-action">
                <div class="qa-icon" style="background:#dcfce7; color:#16a34a;">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                Ver Mis Ventas
            </a>
        </div>
    </div>

    <!-- Productos disponibles -->
    <div class="dash-gap" style="display:block;">

        <!-- Productos disponibles -->
        <div class="dash-card">
            <div class="dash-card-title">
                <i class="fas fa-boxes-stacked"></i> Productos Disponibles
            </div>
            <table class="prod-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($productosDisponibles)): ?>
                        <tr>
                            <td colspan="3" style="text-align:center;padding:1.5rem;color:#94a3b8;">
                                No hay productos registrados
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach (array_slice($productosDisponibles, 0, 5) as $p):
                            $stock  = (int)$p['stock_actual'];
                            $minimo = (int)$p['stock_minimo'];
                            $clase  = $stock === 0 ? 'badge-out' : ($stock <= $minimo ? 'badge-low' : 'badge-ok');
                        ?>
                        <tr>
                            <td style="font-weight:600;"><?= htmlspecialchars($p['nombre']) ?></td>
                            <td>$<?= number_format((float)$p['precio_venta'], 2) ?></td>
                            <td>
                                <span class="badge-stock <?= $clase ?>">
                                    <i class="fas fa-circle" style="font-size:.45rem;"></i>
                                    <?= $stock ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <div style="margin-top:1rem; text-align:right;">
                <a href="/inventory/controllers/vendedorproductoscontroller.php"
                   style="font-size:0.8rem; color:#1e3a8a; font-weight:600; text-decoration:none;">
                    Ver todos los productos <i class="fas fa-arrow-right" style="font-size:0.7rem;"></i>
                </a>
            </div>
        </div>

    </div><!-- end dash-gap -->

</div><!-- end dash-gap -->

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>






