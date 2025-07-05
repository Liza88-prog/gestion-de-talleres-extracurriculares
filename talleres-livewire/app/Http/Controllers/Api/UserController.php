<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Listar usuarios (solo admin)
     */
    public function index(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado'
            ], 403);
        }

        $query = User::query();

        // Filtros
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('student_id', 'like', '%' . $search . '%');
            });
        }

        // Incluir conteos de relaciones
        $query->withCount(['workshops', 'tasks']);

        // Paginación
        $perPage = $request->get('per_page', 15);
        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'users' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ]
            ]
        ]);
    }

    /**
     * Crear nuevo usuario (solo admin)
     */
    public function store(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,student',
            'student_id' => 'nullable|string|unique:users',
            'phone' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'student_id' => $request->student_id,
            'phone' => $request->phone,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado exitosamente',
            'data' => [
                'user' => $user
            ]
        ], 201);
    }

    /**
     * Mostrar usuario específico
     */
    public function show(Request $request, $id)
    {
        $currentUser = $request->user();
        
        // Los estudiantes solo pueden ver su propio perfil
        if ($currentUser->isStudent() && $currentUser->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para ver este usuario'
            ], 403);
        }

        $user = User::with(['workshops', 'tasks.workshop'])
                   ->withCount(['workshops', 'tasks'])
                   ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user
            ]
        ]);
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        $currentUser = $request->user();
        $targetUser = User::findOrFail($id);

        // Los estudiantes solo pueden actualizar su propio perfil
        if ($currentUser->isStudent() && $currentUser->id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para actualizar este usuario'
            ], 403);
        }

        $rules = [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'nullable|string',
        ];

        // Solo admin puede cambiar rol y student_id
        if ($currentUser->isAdmin()) {
            $rules['role'] = 'sometimes|in:admin,student';
            $rules['student_id'] = 'nullable|string|unique:users,student_id,' . $id;
            $rules['password'] = 'sometimes|string|min:6';
        }

        $request->validate($rules);

        $updateData = $request->only(['name', 'email', 'phone']);

        if ($currentUser->isAdmin()) {
            $updateData = array_merge($updateData, $request->only(['role', 'student_id']));
            
            if ($request->has('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
        }

        $targetUser->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado exitosamente',
            'data' => [
                'user' => $targetUser->fresh()
            ]
        ]);
    }

    /**
     * Eliminar usuario (solo admin)
     */
    public function destroy(Request $request, $id)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado'
            ], 403);
        }

        $user = User::findOrFail($id);

        // No permitir eliminar el último admin
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el último administrador'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado exitosamente'
        ]);
    }

    /**
     * Listar solo estudiantes (para asignación de tareas)
     */
    public function students(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado'
            ], 403);
        }

        $students = User::where('role', 'student')
                       ->withCount(['tasks', 'workshops'])
                       ->orderBy('name')
                       ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'students' => $students
            ]
        ]);
    }
}