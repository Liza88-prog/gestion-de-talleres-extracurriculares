<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workshop;
use App\Models\WorkshopEnrollment;
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    /**
     * Listar todos los talleres
     */
    public function index(Request $request)
    {
        $query = Workshop::withCount('students');

        // Filtros opcionales
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('instructor')) {
            $query->where('instructor', 'like', '%' . $request->instructor . '%');
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Paginación
        $perPage = $request->get('per_page', 15);
        $workshops = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'workshops' => $workshops->items(),
                'pagination' => [
                    'current_page' => $workshops->currentPage(),
                    'last_page' => $workshops->lastPage(),
                    'per_page' => $workshops->perPage(),
                    'total' => $workshops->total(),
                ]
            ]
        ]);
    }

    /**
     * Crear un nuevo taller (solo admin)
     */
    public function store(Request $request)
    {
        $this->authorize('create', Workshop::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'instructor' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $workshop = Workshop::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Taller creado exitosamente',
            'data' => [
                'workshop' => $workshop->load('students')
            ]
        ], 201);
    }

    /**
     * Mostrar un taller específico
     */
    public function show($id)
    {
        $workshop = Workshop::with(['students', 'tasks.assignedTo'])
                           ->withCount('students')
                           ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'workshop' => $workshop
            ]
        ]);
    }

    /**
     * Actualizar un taller (solo admin)
     */
    public function update(Request $request, $id)
    {
        $workshop = Workshop::findOrFail($id);
        $this->authorize('update', $workshop);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'instructor' => 'sometimes|string|max:255',
            'capacity' => 'sometimes|integer|min:1',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'location' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:active,inactive',
        ]);

        $workshop->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Taller actualizado exitosamente',
            'data' => [
                'workshop' => $workshop->fresh()->load('students')
            ]
        ]);
    }

    /**
     * Eliminar un taller (solo admin)
     */
    public function destroy($id)
    {
        $workshop = Workshop::findOrFail($id);
        $this->authorize('delete', $workshop);

        $workshop->delete();

        return response()->json([
            'success' => true,
            'message' => 'Taller eliminado exitosamente'
        ]);
    }

    /**
     * Inscribir estudiante en un taller
     */
    public function enroll(Request $request, $id)
    {
        $workshop = Workshop::findOrFail($id);
        $user = $request->user();

        // Verificar que el usuario sea estudiante
        if (!$user->isStudent()) {
            return response()->json([
                'success' => false,
                'message' => 'Solo los estudiantes pueden inscribirse en talleres'
            ], 403);
        }

        // Verificar que el taller esté activo
        if (!$workshop->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'El taller no está activo'
            ], 400);
        }

        // Verificar cupos disponibles
        if ($workshop->available_spots <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No hay cupos disponibles en este taller'
            ], 400);
        }

        // Verificar si ya está inscrito
        $existingEnrollment = WorkshopEnrollment::where('user_id', $user->id)
                                               ->where('workshop_id', $workshop->id)
                                               ->first();

        if ($existingEnrollment) {
            return response()->json([
                'success' => false,
                'message' => 'Ya estás inscrito en este taller'
            ], 400);
        }

        // Crear inscripción
        WorkshopEnrollment::create([
            'user_id' => $user->id,
            'workshop_id' => $workshop->id,
            'enrollment_date' => now(),
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Inscripción exitosa',
            'data' => [
                'workshop' => $workshop->fresh()->load('students')
            ]
        ]);
    }

    /**
     * Desinscribir estudiante de un taller
     */
    public function unenroll(Request $request, $id)
    {
        $workshop = Workshop::findOrFail($id);
        $user = $request->user();

        $enrollment = WorkshopEnrollment::where('user_id', $user->id)
                                       ->where('workshop_id', $workshop->id)
                                       ->first();

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'No estás inscrito en este taller'
            ], 400);
        }

        $enrollment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Desinscripción exitosa'
        ]);
    }

    /**
     * Obtener talleres del usuario autenticado
     */
    public function myWorkshops(Request $request)
    {
        $user = $request->user();
        
        $workshops = $user->workshops()
                         ->withCount('students')
                         ->with(['tasks' => function($query) use ($user) {
                             $query->where('assigned_to', $user->id);
                         }])
                         ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'workshops' => $workshops
            ]
        ]);
    }
}