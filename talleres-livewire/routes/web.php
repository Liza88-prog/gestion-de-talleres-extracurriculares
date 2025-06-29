<?php

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\WorkshopManager;
use App\Livewire\Admin\TaskManager;
use App\Livewire\Student\Dashboard as StudentDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin() 
            ? redirect()->route('admin.dashboard')
            : redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Admin routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/workshops', WorkshopManager::class)->name('workshops');
        Route::get('/tasks', TaskManager::class)->name('tasks');
    });

    // Student routes
    Route::middleware(['student'])->prefix('student')->name('student.')->group(function () {
        Route::get('/dashboard', StudentDashboard::class)->name('dashboard');
        Route::get('/tasks', function () {
            return view('student.tasks');
        })->name('tasks');
        
        // Route to complete tasks
        Route::patch('/tasks/{task}/complete', function ($taskId) {
            $task = \App\Models\Task::findOrFail($taskId);
            
            // Verify the task belongs to the authenticated student
            if ($task->assigned_to !== auth()->id()) {
                abort(403, 'No tienes permiso para completar esta tarea');
            }
            
            $task->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            
            return redirect()->route('student.tasks')->with('message', 'Tarea marcada como completada exitosamente.');
        })->name('task.complete');
    });
    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::post('/login', function () {
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->attempt($credentials)) {
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    });

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    
    Route::post('/register', function () {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,student'
        ]);

        $data['password'] = bcrypt($data['password']);
        
        $user = \App\Models\User::create($data);
        auth()->login($user);
        
        return redirect('/');
    });
});