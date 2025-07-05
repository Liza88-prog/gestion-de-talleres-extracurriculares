<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Workshop;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Listar tareas (filtradas por rol)
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Task::with(['assignedTo', 'workshop', 'assignedBy']);

        // Si es estudiante, solo ver sus tareas
        if ($user->isStudent()) {
            $query->where('assigned_to', $user->id);
        }

        // Filtros opcionales
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('workshop_id')) {
            $query->where('workshop_id', $request->workshop_id);
        }

        if ($request->has('overdue')) {
            $query->where('due_date', '<', now())
                  ->where('status', '!=', 'completed');
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'due_date');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginación
        $perPage = $request->get('per_page', 15);
        $tasks = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'tasks' => $tasks->items(),
                'pagination' => [
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                    'per_page' => $tasks->perPage(),
                    'total' => $tasks->total(),
                ]
            ]
        ]);
    }

    /**
     * Crear una nueva tarea (solo admin)
     */
    public function store(Request $request)
    {
        $this->authorize('create', Task::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'workshop_id' => 'required|exists:workshops,id',
            'assigned_to' => 'required|exists:users,id',
            'due_date' => 'required|date|after:now',
            'priority' => 'required|in:low,medium,high',
            'status' => 'sometimes|in:pending,in_progress,completed',
        ]);

        // Verificar que el usuario asignado sea estudiante
        $assignedUser = User::findOrFail($request->assigned_to);
        if (!$assignedUser->isStudent()) {
            return response()->json([
                'success' => false,
                'message' => 'Solo se pueden asignar tareas a estudiantes'
            ], 400);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'workshop_id' => $request->workshop_id,
            'assigned_to' => $request->assigned_to,
            'assigned_by' => $request->user()->id,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => $request->get('status', 'pending'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarea creada exitosamente',
            'data' => [
                'task' => $task->load(['assignedTo', 'workshop', 'assignedBy'])
            ]
        ], 201);
    }

    /**
     * Mostrar una tarea específica
     */
    public function show(Request $request, $id)
    {
        $task = Task::with(['assignedTo', 'workshop', 'assignedBy'])->findOrFail($id);
        $user = $request->user();

        // Verificar permisos
        if ($user->isStudent() && $task->assigned_to !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver esta tarea'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'task' => $task
            ]
        ]);
    }

    /**
     * Actualizar una tarea
     */
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = $request->user();

        // Solo admin puede actualizar tareas
        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Solo los administradores pueden actualizar tareas'
            ], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'workshop_id' => 'sometimes|exists:workshops,id',
            'assigned_to' => 'sometimes|exists:users,id',
            'due_date' => 'sometimes|date',
            'priority' => 'sometimes|in:low,medium,high',
            'status' => 'sometimes|in:pending,in_progress,completed',
        ]);

        $task->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Tarea actualizada exitosamente',
            'data' => [
                'task' => $task->fresh()->load(['assignedTo', 'workshop', 'assignedBy'])
            ]
        ]);
    }

    /**
     * Eliminar una tarea (solo admin)
     */
    public function destroy(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Solo los administradores pueden eliminar tareas'
            ], 403);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tarea eliminada exitosamente'
        ]);
    }

    /**
     * Marcar tarea como completada (estudiante)
     */
    public function complete(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = $request->user();

        // Verificar que la tarea pertenezca al estudiante
        if ($task->assigned_to !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para completar esta tarea'
            ], 403);
        }

        // Verificar que no esté ya completada
        if ($task->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'La tarea ya está completada'
            ], 400);
        }

        $request->validate([
            'completion_notes' => 'nullable|string|max:1000',
        ]);

        $task->update([
            'status' => 'completed',
            'completed_at' => now(),
            'completion_notes' => $request->completion_notes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tarea marcada como completada',
            'data' => [
                'task' => $task->fresh()->load(['assignedTo', 'workshop', 'assignedBy'])
            ]
        ]);
    }

    /**
     * Cambiar estado de tarea (estudiante puede cambiar a in_progress)
     */
    public function updateStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = $request->user();

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        // Verificar permisos según rol
        if ($user->isStudent()) {
            // Estudiante solo puede actualizar sus propias tareas
            if ($task->assigned_to !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permiso para actualizar esta tarea'
                ], 403);
            }

            // Estudiante solo puede cambiar a in_progress o completed
            if (!in_array($request->status, ['in_progress', 'completed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo puedes cambiar el estado a "en progreso" o "completada"'
                ], 400);
            }
        }

        $updateData = ['status' => $request->status];

        // Si se marca como completada, agregar timestamp
        if ($request->status === 'completed') {
            $updateData['completed_at'] = now();
        }

        $task->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Estado de tarea actualizado',
            'data' => [
                'task' => $task->fresh()->load(['assignedTo', 'workshop', 'assignedBy'])
            ]
        ]);
    }

    /**
     * Obtener estadísticas de tareas del usuario
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        
        if ($user->isAdmin()) {
            // Estadísticas generales para admin
            $stats = [
                'total_tasks' => Task::count(),
                'pending_tasks' => Task::where('status', 'pending')->count(),
                'in_progress_tasks' => Task::where('status', 'in_progress')->count(),
                'completed_tasks' => Task::where('status', 'completed')->count(),
                'overdue_tasks' => Task::where('due_date', '<', now())
                                     ->where('status', '!=', 'completed')
                                     ->count(),
                'high_priority_tasks' => Task::where('priority', 'high')
                                           ->where('status', '!=', 'completed')
                                           ->count(),
            ];
        } else {
            // Estadísticas personales para estudiante
            $userTasks = $user->tasks();
            $stats = [
                'total_tasks' => $userTasks->count(),
                'pending_tasks' => $userTasks->where('status', 'pending')->count(),
                'in_progress_tasks' => $userTasks->where('status', 'in_progress')->count(),
                'completed_tasks' => $userTasks->where('status', 'completed')->count(),
                'overdue_tasks' => $userTasks->where('due_date', '<', now())
                                            ->where('status', '!=', 'completed')
                                            ->count(),
                'completion_rate' => $userTasks->count() > 0 
                    ? round(($userTasks->where('status', 'completed')->count() / $userTasks->count()) * 100, 2)
                    : 0,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats
            ]
        ]);
    }
}