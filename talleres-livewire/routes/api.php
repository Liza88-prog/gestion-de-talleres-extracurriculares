<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\WorkshopController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas públicas de autenticación
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

// Rutas protegidas por autenticación
Route::middleware('auth:sanctum')->group(function () {
    
    // Autenticación
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
    });

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard']);
    Route::get('dashboard/admin', [DashboardController::class, 'adminDashboard']);
    Route::get('dashboard/student', [DashboardController::class, 'studentDashboard']);

    // Talleres
    Route::apiResource('workshops', WorkshopController::class);
    Route::post('workshops/{workshop}/enroll', [WorkshopController::class, 'enroll']);
    Route::delete('workshops/{workshop}/enroll', [WorkshopController::class, 'unenroll']);
    Route::get('my-workshops', [WorkshopController::class, 'myWorkshops']);

    // Tareas
    Route::apiResource('tasks', TaskController::class);
    Route::post('tasks/{task}/complete', [TaskController::class, 'complete']);
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus']);
    Route::get('tasks-stats', [TaskController::class, 'stats']);

    // Usuarios (principalmente para admin)
    Route::apiResource('users', UserController::class);
    Route::get('students', [UserController::class, 'students']);
});

// Ruta de información de la API
Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'API del Sistema de Talleres Extracurriculares',
        'version' => '1.0.0',
        'endpoints' => [
            'auth' => [
                'POST /api/auth/login' => 'Iniciar sesión',
                'POST /api/auth/register' => 'Registrar usuario',
                'POST /api/auth/logout' => 'Cerrar sesión',
                'GET /api/auth/me' => 'Información del usuario',
                'PUT /api/auth/profile' => 'Actualizar perfil',
            ],
            'dashboard' => [
                'GET /api/dashboard' => 'Dashboard según rol',
                'GET /api/dashboard/admin' => 'Dashboard administrativo',
                'GET /api/dashboard/student' => 'Dashboard del estudiante',
            ],
            'workshops' => [
                'GET /api/workshops' => 'Listar talleres',
                'POST /api/workshops' => 'Crear taller (admin)',
                'GET /api/workshops/{id}' => 'Ver taller específico',
                'PUT /api/workshops/{id}' => 'Actualizar taller (admin)',
                'DELETE /api/workshops/{id}' => 'Eliminar taller (admin)',
                'POST /api/workshops/{id}/enroll' => 'Inscribirse en taller',
                'DELETE /api/workshops/{id}/enroll' => 'Desinscribirse de taller',
                'GET /api/my-workshops' => 'Mis talleres',
            ],
            'tasks' => [
                'GET /api/tasks' => 'Listar tareas',
                'POST /api/tasks' => 'Crear tarea (admin)',
                'GET /api/tasks/{id}' => 'Ver tarea específica',
                'PUT /api/tasks/{id}' => 'Actualizar tarea (admin)',
                'DELETE /api/tasks/{id}' => 'Eliminar tarea (admin)',
                'POST /api/tasks/{id}/complete' => 'Completar tarea',
                'PATCH /api/tasks/{id}/status' => 'Cambiar estado de tarea',
                'GET /api/tasks-stats' => 'Estadísticas de tareas',
            ],
            'users' => [
                'GET /api/users' => 'Listar usuarios (admin)',
                'POST /api/users' => 'Crear usuario (admin)',
                'GET /api/users/{id}' => 'Ver usuario específico',
                'PUT /api/users/{id}' => 'Actualizar usuario',
                'DELETE /api/users/{id}' => 'Eliminar usuario (admin)',
                'GET /api/students' => 'Listar estudiantes (admin)',
            ],
        ],
        'authentication' => 'Bearer Token (Sanctum)',
        'documentation' => 'Ver API_DOCUMENTATION.md para ejemplos detallados'
    ]);
});