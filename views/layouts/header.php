<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: ../usuarios/login.php");
    exit;
}

// Get variables from session or globals
$usuario = $usuario ?? $GLOBALS['usuario'] ?? $_SESSION['usuario'];
$nombre = $nombre ?? $GLOBALS['nombre'] ?? $usuario['nombre'] ?? 'Usuario';
$rol = $rol ?? $GLOBALS['rol'] ?? $usuario['rol'] ?? 'invitado';
$correo = $correo ?? $GLOBALS['correo'] ?? $usuario['correo'] ?? '';
$titulo = $titulo ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo) ?> - Inventory System</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #4338ca;
            --secondary-color: #06b6d4;
            --accent-color: #f59e0b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --light-bg: #f8fafc;
            --dark-bg: #1e293b;
            --sidebar-bg: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        * {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        /* Layout Classes */
        .flex {
            display: flex;
        }

        .flex-col {
            flex-direction: column;
        }

        .flex-1 {
            flex: 1;
        }

        .min-h-screen {
            min-height: 100vh;
        }

        .w-full {
            width: 100%;
        }

        .h-24 {
            height: 6rem;
        }

        .w-72 {
            width: 18rem;
        }

        .w-12 {
            width: 3rem;
        }

        .h-12 {
            height: 3rem;
        }

        .w-8 {
            width: 2rem;
        }

        .h-8 {
            height: 2rem;
        }

        .w-2 {
            width: 0.5rem;
        }

        .h-2 {
            height: 0.5rem;
        }

        .w-64 {
            width: 16rem;
        }

        .w-10 {
            width: 2.5rem;
        }

        .h-10 {
            height: 2.5rem;
        }

        .w-4 {
            width: 1rem;
        }

        .h-4 {
            height: 1rem;
        }

        /* Spacing */
        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .px-8 {
            padding-left: 2rem;
            padding-right: 2rem;
        }

        .py-3 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .py-6 {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .p-6 {
            padding: 1.5rem;
        }

        .p-8 {
            padding: 2rem;
        }

        .m-4 {
            margin: 1rem;
        }

        .mt-6 {
            margin-top: 1.5rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .mb-6 {
            margin-bottom: 1.5rem;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }

        .mr-3 {
            margin-right: 0.75rem;
        }

        .ml-3 {
            margin-left: 0.75rem;
        }

        /* Flexbox utilities */
        .items-center {
            align-items: center;
        }

        .justify-center {
            justify-content: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .space-x-3 > * + * {
            margin-left: 0.75rem;
        }

        .space-x-4 > * + * {
            margin-left: 1rem;
        }

        .space-y-1 > * + * {
            margin-top: 0.25rem;
        }

        .space-y-2 > * + * {
            margin-top: 0.5rem;
        }

        .space-y-4 > * + * {
            margin-top: 1rem;
        }

        .space-y-6 > * + * {
            margin-top: 1.5rem;
        }

        /* Colors */
        .bg-white {
            background-color: white;
        }

        .bg-gray-50 {
            background-color: #f9fafb;
        }

        .bg-gradient-to-b {
            background-image: linear-gradient(to bottom, var(--gradient-from), var(--gradient-to));
        }

        .bg-gradient-to-r {
            background-image: linear-gradient(to right, var(--gradient-from), var(--gradient-to));
        }

        .bg-gradient-to-br {
            background-image: linear-gradient(to bottom right, var(--gradient-from), var(--gradient-to));
        }

        .text-white {
            color: white;
        }

        .text-gray-300 {
            color: #d1d5db;
        }

        .text-gray-400 {
            color: #9ca3af;
        }

        .text-gray-500 {
            color: #6b7280;
        }

        .text-gray-600 {
            color: #4b5563;
        }

        .text-gray-700 {
            color: #374151;
        }

        .text-gray-800 {
            color: #1f2937;
        }

        .text-blue-400 {
            color: #60a5fa;
        }

        .text-blue-600 {
            color: #2563eb;
        }

        .text-green-400 {
            color: #4ade80;
        }

        .text-green-600 {
            color: #16a34a;
        }

        .text-orange-400 {
            color: #fb923c;
        }

        .text-orange-600 {
            color: #ea580c;
        }

        .text-purple-400 {
            color: #c084fc;
        }

        .text-purple-600 {
            color: #9333ea;
        }

        .text-yellow-400 {
            color: #facc15;
        }

        .text-red-600 {
            color: #dc2626;
        }

        /* Background Colors */
        .bg-gray-900 {
            background-color: #111827;
        }

        .bg-slate-800 {
            background-color: #1e293b;
        }

        .bg-slate-900 {
            background-color: #0f172a;
        }

        .bg-blue-600 {
            background-color: #2563eb;
        }

        .bg-green-600 {
            background-color: #16a34a;
        }

        .bg-orange-600 {
            background-color: #ea580c;
        }

        .bg-purple-600 {
            background-color: #9333ea;
        }

        .bg-blue-700 {
            background-color: #1d4ed8;
        }

        .bg-blue-800 {
            background-color: #1e40af;
        }

        .bg-sky-700 {
            background-color: #0284c7;
        }

        /* Border */
        .border {
            border: 1px solid var(--border-color);
        }

        .border-gray-200 {
            border-color: #e5e7eb;
        }

        .border-slate-200 {
            border-color: #e2e8f0;
        }

        .border-t {
            border-top: 1px solid var(--border-color);
        }

        .border-b {
            border-bottom: 1px solid var(--border-color);
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .rounded-xl {
            border-radius: 0.75rem;
        }

        .rounded-2xl {
            border-radius: 1rem;
        }

        .rounded-full {
            border-radius: 50%;
        }

        /* Shadow */
        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .shadow {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .shadow-xl {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .shadow-2xl {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        /* Position */
        .relative {
            position: relative;
        }

        .absolute {
            position: absolute;
        }

        .fixed {
            position: fixed;
        }

        .inset-0 {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }

        .z-10 {
            z-index: 10;
        }

        .z-20 {
            z-index: 20;
        }

        .z-30 {
            z-index: 30;
        }

        .z-40 {
            z-index: 40;
        }

        .z-50 {
            z-index: 50;
        }

        /* Overflow */
        .overflow-hidden {
            overflow: hidden;
        }

        .overflow-x-auto {
            overflow-x: auto;
        }

        /* Animations */
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .transition-colors {
            transition: color 0.3s ease, background-color 0.3s ease;
        }

        .transition-transform {
            transition: transform 0.3s ease;
        }

        .duration-200 {
            transition-duration: 200ms;
        }

        .duration-300 {
            transition-duration: 300ms;
        }

        /* Hover states */
        .hover\:bg-white\/10:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .hover\:bg-blue-700:hover {
            background-color: #1d4ed8;
        }

        .hover\:bg-blue-600:hover {
            background-color: #2563eb;
        }

        .hover\:bg-blue-100:hover {
            background-color: #dbeafe;
        }

        .hover\:bg-green-100:hover {
            background-color: #d1fae5;
        }

        .hover\:bg-purple-100:hover {
            background-color: #e9d5ff;
        }

        .hover\:bg-orange-100:hover {
            background-color: #fed7aa;
        }

        .hover\:bg-gray-50:hover {
            background-color: #f9fafb;
        }

        .hover\:text-white:hover {
            color: white;
        }

        .hover\:text-blue-400:hover {
            color: #60a5fa;
        }

        .hover\:text-blue-600:hover {
            color: #2563eb;
        }

        .hover\:text-green-400:hover {
            color: #4ade80;
        }

        .hover\:text-green-600:hover {
            color: #16a34a;
        }

        .hover\:text-orange-400:hover {
            color: #fb923c;
        }

        .hover\:text-orange-600:hover {
            color: #ea580c;
        }

        .hover\:text-purple-400:hover {
            color: #c084fc;
        }

        .hover\:text-purple-600:hover {
            color: #9333ea;
        }

        .hover\:scale-110:hover {
            transform: scale(1.1);
        }

        /* Sidebar Styles */
        .sidebar-link {
            position: relative;
            overflow: hidden;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: var(--primary-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar-link:hover::before,
        .sidebar-link.active::before {
            transform: scaleY(1);
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.1) 0%, transparent 100%);
            border-left: 3px solid var(--primary-color);
        }

        /* Header Styles */
        .header-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            position: relative;
            overflow: hidden;
        }

        .header-gradient::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* User Avatar */
        .user-avatar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            position: relative;
            overflow: hidden;
        }

        .user-avatar::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            animation: rotate 15s linear infinite;
        }

        /* Badge animations */
        .badge-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(79, 70, 229, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
            }
        }

        /* Grid */
        .grid {
            display: grid;
        }

        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }

        .grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .grid-cols-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .gap-4 {
            gap: 1rem;
        }

        .gap-6 {
            gap: 1.5rem;
        }

        .gap-8 {
            gap: 2rem;
        }

        /* Text */
        .text-xs {
            font-size: 0.75rem;
            line-height: 1rem;
        }

        .text-sm {
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .text-base {
            font-size: 1rem;
            line-height: 1.5rem;
        }

        .text-lg {
            font-size: 1.125rem;
            line-height: 1.75rem;
        }

        .text-xl {
            font-size: 1.25rem;
            line-height: 1.75rem;
        }

        .text-2xl {
            font-size: 1.5rem;
            line-height: 2rem;
        }

        .text-3xl {
            font-size: 1.875rem;
            line-height: 2.25rem;
        }

        .text-4xl {
            font-size: 2.25rem;
            line-height: 2.5rem;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-extrabold {
            font-weight: 800;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .capitalize {
            text-transform: capitalize;
        }

        .tracking-wide {
            letter-spacing: 0.025em;
        }

        .leading-tight {
            line-height: 1.25;
        }

        .leading-relaxed {
            line-height: 1.625;
        }

        /* Width utilities */
        .max-w-7xl {
            max-width: 80rem;
        }

        /* Opacity */
        .opacity-5 {
            opacity: 0.05;
        }

        .opacity-0 {
            opacity: 0;
        }

        .opacity-100 {
            opacity: 1;
        }

        .invisible {
            visibility: hidden;
        }

        .visible {
            visibility: visible;
        }

        /* Transform */
        .translate-x-1 {
            transform: translateX(0.25rem);
        }

        .translate-y-2 {
            transform: translateY(-0.5rem);
        }

        /* Custom gradient variables */
        .from-gray-900 {
            --gradient-from: #111827;
        }

        .via-slate-800 {
            --gradient-via: #1e293b;
        }

        .to-gray-900 {
            --gradient-to: #111827;
        }

        .from-blue-500 {
            --gradient-from: #3b82f6;
        }

        .to-purple-600 {
            --gradient-to: #9333ea;
        }

        .from-blue-600 {
            --gradient-from: #2563eb;
        }

        .to-blue-800 {
            --gradient-to: #1e40af;
        }

        .from-blue-800 {
            --gradient-from: #1e40af;
        }

        .to-sky-700 {
            --gradient-to: #0284c7;
        }

        .from-blue-500 {
            --gradient-from: #3b82f6;
        }

        .to-purple-600 {
            --gradient-to: #9333ea;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            aside {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 50;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            
            aside.show {
                transform: translateX(0);
            }
            
            main {
                margin-left: 0 !important;
            }
            
            .header-gradient h1 {
                font-size: 2rem;
            }
            
            .header-gradient p {
                font-size: 0.875rem;
            }
        }

        @media (max-width: 768px) {
            .w-72 {
                width: 100%;
                max-width: 280px;
            }
            
            .sidebar-link {
                padding: 12px 16px;
            }
            
            .header-gradient {
                padding: 0 1rem;
            }
            
            .header-gradient .flex {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .header-gradient h1 {
                font-size: 1.5rem;
            }
            
            .grid-cols-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
            
            .py-16 {
                padding-top: 3rem;
                padding-bottom: 3rem;
            }
            
            .py-6 {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
            
            .space-x-6 > * + * {
                margin-left: 0;
                margin-top: 0.5rem;
            }
            
            .flex-col.md\:flex-row {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .header-gradient h1 {
                font-size: 1.25rem;
            }
            
            .header-gradient p {
                display: none;
            }
            
            .w-8 {
                width: 2rem;
                height: 2rem;
            }
            
            .py-16 {
                padding-top: 3rem;
                padding-bottom: 3rem;
            }
            
            .py-6 {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
            
            .grid-cols-2 {
                grid-template-columns: repeat(1, minmax(0, 1fr));
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

