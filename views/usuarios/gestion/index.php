<?php
// Gestión de Usuarios Page
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'administrador') {
    header("Location: ../../usuarios/login.php");
    exit;
}

require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../../models/Usuario.php';

$database = new Database();
$db = $database->conectar();
$usuarioModel = new Usuario($db);

// Set page title
$titulo = "Gestión de Usuarios";

// Include the main layout which contains header, sidebar, and footer
require_once __DIR__ . '/../../layouts/main.php';
?>

<!-- Page Content -->
<div class="space-y-6 animate-fade-in">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover-card">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Gestión de <span class="text-blue-600">Usuarios</span></h2>
                <p class="text-gray-600 mt-2">Administra los usuarios y permisos del sistema</p>
            </div>
            <button onclick="openModal('modalCrear')" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-user-plus"></i>
                Agregar Usuario
            </button>
        </div>
        
        <?php if (isset($_SESSION['alert'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: '<?= htmlspecialchars($_SESSION['alert']['icon']) ?>',
                        title: '<?= htmlspecialchars($_SESSION['alert']['title']) ?>',
                        text: '<?= htmlspecialchars($_SESSION['alert']['text']) ?>',
                        confirmButtonText: 'Aceptar'
                    });
                });
            </script>
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Usuarios</p>
                    <p class="text-3xl font-bold mt-2">156</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Usuarios Activos</p>
                    <p class="text-3xl font-bold mt-2">142</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white hover-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Administradores</p>
                    <p class="text-3xl font-bold mt-2">8</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-shield text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover-card">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Lista de Usuarios</h3>
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <input type="text" placeholder="Buscar usuarios..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos los roles</option>
                        <option value="administrador">Administrador</option>
                        <option value="vendedor">Vendedor</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correo</th>
                        <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                        <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="p-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Registro</th>
                        <th class="p-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">Juan Pérez</div>
                                    <div class="text-xs text-gray-500">ID: #001</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-sm text-gray-900">juan.perez@empresa.com</td>
                        <td class="p-4">
                            <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-800 font-medium">Administrador</span>
                        </td>
                        <td class="p-4">
                            <span class="flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                <span class="text-sm text-green-600 font-medium">Activo</span>
                            </span>
                        </td>
                        <td class="p-4 text-sm text-gray-500">01/01/2024</td>
                        <td class="p-4">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 transition-colors" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 transition-colors" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">María García</div>
                                    <div class="text-xs text-gray-500">ID: #002</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-sm text-gray-900">maria.garcia@empresa.com</td>
                        <td class="p-4">
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">Vendedor</span>
                        </td>
                        <td class="p-4">
                            <span class="flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                <span class="text-sm text-green-600 font-medium">Activo</span>
                            </span>
                        </td>
                        <td class="p-4 text-sm text-gray-500">15/01/2024</td>
                        <td class="p-4">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 transition-colors" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 transition-colors" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">Carlos López</div>
                                    <div class="text-xs text-gray-500">ID: #003</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-sm text-gray-900">carlos.lopez@empresa.com</td>
                        <td class="p-4">
                            <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">Vendedor</span>
                        </td>
                        <td class="p-4">
                            <span class="flex items-center">
                                <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                                <span class="text-sm text-gray-600 font-medium">Inactivo</span>
                            </span>
                        </td>
                        <td class="p-4 text-sm text-gray-500">20/01/2024</td>
                        <td class="p-4">
                            <div class="flex items-center justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 transition-colors" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-800 transition-colors" title="Activar">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Mostrando <span class="font-medium">1</span> a <span class="font-medium">3</span> de <span class="font-medium">156</span> resultados
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-3 py-1 bg-blue-600 text-white rounded-md">1</button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">2</button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">3</button>
                    <button class="px-3 py-1 border border-gray-300 rounded-md hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for creating users (placeholder) -->
<div id="modalCrear" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Crear Nuevo Usuario</h3>
        <form>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
                    <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="administrador">Administrador</option>
                        <option value="vendedor">Vendedor</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="closeModal('modalCrear')" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Page Specific JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.querySelector('input[placeholder="Buscar usuarios..."]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Filter by role
    const roleSelect = document.querySelector('select');
    if (roleSelect) {
        roleSelect.addEventListener('change', function() {
            const selectedRole = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                if (selectedRole === '') {
                    row.style.display = '';
                } else {
                    const roleCell = row.querySelector('td:nth-child(3)');
                    const rowRole = roleCell.textContent.toLowerCase();
                    row.style.display = rowRole.includes(selectedRole) ? '' : 'none';
                }
            });
        });
    }
});

// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}
</script>
