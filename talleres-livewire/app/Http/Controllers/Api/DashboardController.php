<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workshop;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard para administradores
     */
    public function adminDashboard(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado'
            ], 403);
        }

        // Estadísticas generales
        $totalStudents = User::where('role', 'student')->count();
        $totalWorkshops = Workshop::count();
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'completed')->count();
        $pendingTasks = Task::where('status', 'pending')->count();
        $overdueTasks = Task::where('due_date', '<', now())
                           ->where('status', '!=', 'completed')
                           ->count();

        // Tareas recientes
        $recentTasks = Task::with(['assignedTo', 'workshop'])
                          ->latest()
                          ->take(10)
                          ->get();

        // Talleres activos con más información
        $activeWorkshops = Workshop::where('status', 'active')
                                 ->withCount('students')
                                 ->with(['tasks' => function($query) {
                                     $query->select('workshop_id', 'status')
                                           ->groupBy('workshop_id', 'status');
                                 }])
                                 ->get();

        // Estudiantes más activos (por tareas completadas)
        $topStudents = User::where('role', 'student')
                          ->withCount(['tasks as completed_tasks_count' => function($query) {
                              $query->where('status', 'completed');
                          }])
                          ->orderBy('completed_tasks_count', 'desc')
                          ->take(5)
                          ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => [
                    'total_students' => $totalStudents,
                    'total_workshops' => $totalWorkshops,
                    'total_tasks' => $totalTasks,
                    'completed_tasks' => $completedTasks,
                    'pending_tasks' => $pendingTasks,
                    'overdue_tasks' => $overdueTasks,
                    'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0,
                ],
                'recent_tasks' => $recentTasks,
                'active_workshops' => $activeWorkshops,
                'top_students' => $topStudents,
            ]
        ]);
    }

    /**
     * Dashboard para estudiantes
     */
    public function studentDashboard(Request $request)
    {
        $user = $request->user();

        if (!$user->isStudent()) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado'
            ], 403);
        }

        // Estadísticas del estudiante
        $totalTasks = $user->tasks()->count();
        $pendingTasks = $user->tasks()->where('status', 'pending')->count();
        $inProgressTasks = $user->tasks()->where('status', 'in_progress')->count();
        $completedTasks = $user->tasks()->where('status', 'completed')->count();
        $overdueTasks = $user->tasks()
                            ->where('due_date', '<', now())
                            ->where('status', '!=', 'completed')
                            ->count();

        // Tareas recientes del estudiante
        $recentTasks = $user->tasks()
                           ->with('workshop')
                           ->orderBy('due_date', 'asc')
                           ->take(5)
                           ->get();

        // Talleres del estudiante
        $myWorkshops = $user->workshops()
                           ->withCount(['tasks as total_tasks' => function($query) use ($user) {
                               $query->where('assigned_to', $user->id);
                           }])
                           ->withCount(['tasks as completed_tasks' => function($query) use ($user) {
                               $query->where('assigned_to', $user->id)
                                     ->where('status', 'completed');
                           }])
                           ->get();

        // Próximas fechas límite
        $upcomingDeadlines = $user->tasks()
                                 ->with('workshop')
                                 ->where('status', '!=', 'completed')
                                 ->where('due_date', '>', now())
                                 ->orderBy('due_date', 'asc')
                                 ->take(5)
                                 ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'student_id' => $user->student_id,
                ],
                'stats' => [
                    'total_tasks' => $totalTasks,
                    'pending_tasks' => $pendingTasks,
                    'in_progress_tasks' => $inProgressTasks,
                    'completed_tasks' => $completedTasks,
                    'overdue_tasks' => $overdueTasks,
                    'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0,
                    'total_workshops' => $myWorkshops->count(),
                ],
                'recent_tasks' => $recentTasks,
                'my_workshops' => $myWorkshops,
                'upcoming_deadlines' => $upcomingDeadlines,
            ]
        ]);
    }

    /**
     * Dashboard general (redirige según el rol)
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard($request);
        } else {
            return $this->studentDashboard($request);
        }
    }
}