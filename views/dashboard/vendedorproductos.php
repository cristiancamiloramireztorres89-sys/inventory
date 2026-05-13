<?php
/**
 * VISTA: Catálogo de Productos (Vendedor)
 * Cargada por: controllers/vendedorproductoscontroller.php
 * Variables: $productos, $total, $disponibles, $stockBajo
 */
if (!isset($productos)) {
    header("Location: /inventory/controllers/vendedorproductoscontroller.php");
    exit;
}

$usuario = $_SESSION['usuario'];
$nombre  = $usuario['nombre'] ?? 'Vendedor';
$rol     = $usuario['rol']    ?? 'vendedor';
$correo  = $usuario['correo'] ?? '';

$GLOBALS['nombre']  = $nombre;
$GLOBALS['rol']     = $rol;
$GLOBALS['correo']  = $correo;
$GLOBALS['usuario'] = $usuario;

$titulo = "Catálogo de Productos";
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

/* Grid de tarjetas */
.prod-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill, minmax(200px, 1fr));
    gap:1.1rem;
}

.prod-card {
    background:white; border-radius:14px; border:1px solid #e2e8f0;
    overflow:hidden; display:flex; flex-direction:column;
    transition:box-shadow .2s, transform .2s;
}
.prod-card:hover {
    box-shadow:0 8px 24px rgba(0,0,0,.1);
    transform:translateY(-2px);
}

.prod-card-img {
    width:100%; height:180px;
    object-fit:contain; background:#f8fafc;
    display:block; padding:.75rem;
}
.prod-card-img-placeholder {
    width:100%; height:180px;
    background:linear-gradient(135deg,#f1f5f9,#e2e8f0);
    display:flex; align-items:center; justify-content:center;
    color:#cbd5e1; font-size:2.5rem;
}

.prod-card-body {
    padding:.85rem 1rem; flex:1;
    display:flex; flex-direction:column; gap:.3rem;
}

.prod-card-name {
    font-size:.95rem; font-weight:700; color:#1e293b; line-height:1.3;
}
.prod-card-cat {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.15rem .55rem; border-radius:20px;
    font-size:.68rem; font-weight:600;
    background:#dcfce7; color:#16a34a; width:fit-content;
}
.prod-card-marca {
    font-size:.75rem; color:#94a3b8;
}
.prod-card-price {
    font-size:1.05rem; font-weight:800; color:#1e293b; margin-top:.2rem;
}

.stock-badge {
    display:inline-flex; align-items:center; gap:.3rem;
    padding:.18rem .6rem; border-radius:20px;
    font-size:.7rem; font-weight:600; width:fit-content;
}
.stock-ok  { background:#dcfce7; color:#16a34a; }
.stock-low { background:#fef9c3; color:#ca8a04; }
.stock-out { background:#fee2e2; color:#dc2626; }

/* Buscador */
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
    font-size:.83rem; color:#374151; width:220px;
}
.u-search i { color:#94a3b8; font-size:.8rem; }

.g3  { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
.gap { display:flex; flex-direction:column; gap:1.1rem; }

@media(max-width:1024px){ .prod-grid { grid-template-columns:repeat(auto-fill, minmax(180px,1fr)); } }
@media(max-width:640px) { .g3 { grid-template-columns:1fr; } }
</style>

<div class="gap">

    <!-- Cabecera -->
    <div class="page-header">
        <div>
            <h2><i class="fas fa-box-open" style="margin-right:.5rem;opacity:.8;"></i>Cat&aacute;logo de Productos</h2>
            <p>Consulta los productos disponibles en el inventario</p>
        </div>
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
            <div class="mini-stat-icon" style="background:#dcfce7;color:#16a34a;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div class="mini-stat-val"><?= $disponibles ?></div>
                <div class="mini-stat-lbl">Disponibles</div>
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
    </div>

    <!-- Buscador + Grid -->
    <div class="u-wrap">
        <div class="u-toolbar">
            <div style="font-size:.9rem;font-weight:700;color:#1e293b;">
                <i class="fas fa-th-large" style="color:#1e3a8a;margin-right:.4rem;"></i>
                Productos
            </div>
            <div class="u-search">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar producto..." oninput="filtrarGrid()">
            </div>
        </div>

        <div style="padding:1.25rem;">
            <?php if (empty($productos)): ?>
                <div style="text-align:center;padding:3rem;color:#94a3b8;">
                    <i class="fas fa-box-open" style="font-size:2.5rem;display:block;margin-bottom:.75rem;"></i>
                    No hay productos registrados
                </div>
            <?php else: ?>
                <div class="prod-grid" id="prodGrid">
                    <?php foreach ($productos as $p):
                        $stock      = (int)$p['stock_actual'];
                        $minimo     = (int)$p['stock_minimo'];
                        $stockClass = $stock === 0 ? 'stock-out' : ($stock <= $minimo ? 'stock-low' : 'stock-ok');
                        $stockLabel = $stock === 0 ? 'Agotado' : ($stock <= $minimo ? 'Stock bajo' : 'En stock');
                    ?>
                    <div class="prod-card" data-nombre="<?= htmlspecialchars(strtolower($p['nombre'])) ?>">

                        <?php if (!empty($p['imagen'])): ?>
                            <img class="prod-card-img"
                                 src="/inventory/img/productos/<?= htmlspecialchars($p['imagen']) ?>"
                                 alt="<?= htmlspecialchars($p['nombre']) ?>">
                        <?php else: ?>
                            <div class="prod-card-img-placeholder">
                                <i class="fas fa-box-open"></i>
                            </div>
                        <?php endif; ?>

                        <div class="prod-card-body">
                            <div class="prod-card-name"><?= htmlspecialchars($p['nombre']) ?></div>

                            <?php if (!empty($p['descripcion'])): ?>
                                <div style="font-size:.78rem;color:#64748b;line-height:1.5;margin-bottom:.2rem;">
                                    <?= htmlspecialchars($p['descripcion']) ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($p['categoria_nombre'])): ?>
                                <span class="prod-card-cat">
                                    <i class="fas fa-tag" style="font-size:.6rem;"></i>
                                    <?= htmlspecialchars($p['categoria_nombre']) ?>
                                </span>
                            <?php endif; ?>

                            <?php if (!empty($p['marca'])): ?>
                                <div class="prod-card-marca">
                                    <i class="fas fa-industry" style="font-size:.65rem;margin-right:3px;"></i>
                                    <?= htmlspecialchars($p['marca']) ?>
                                </div>
                            <?php endif; ?>

                            <div class="prod-card-price">
                                $<?= number_format((float)$p['precio_venta'], 2) ?>
                            </div>

                            <span class="stock-badge <?= $stockClass ?>">
                                <i class="fas fa-circle" style="font-size:.45rem;"></i>
                                <?= $stock ?> uds. &mdash; <?= $stockLabel ?>
                            </span>
                        </div>

                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div><!-- end gap -->

<script>
function filtrarGrid() {
    var q = document.getElementById('searchInput').value.toLowerCase();
    var visibles = 0;
    document.querySelectorAll('#prodGrid .prod-card').forEach(function(card) {
        var mostrar = card.dataset.nombre.includes(q);
        card.style.display = mostrar ? '' : 'none';
        if (mostrar) visibles++;
    });

    var noResult = document.getElementById('noResultMsg');
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
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>






