<?php

namespace App\Livewire\Student;

use App\Models\Task;
use Livewire\Component;

class Dashboard extends Component
{
    public $user;
    public $pendingTasks;
    public $completedTasks;
    public $totalTasks;
    public $workshops;

    public function mount()
    {
        $this->user = auth()->user();
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->pendingTasks = $this->user->tasks()->where('status', '!=', 'completed')->count();
        $this->completedTasks = $this->user->tasks()->where('status', 'completed')->count();
        $this->totalTasks = $this->user->tasks()->count();
        $this->workshops = $this->user->workshops()->with('tasks')->get();
    }

    public function markTaskCompleted($taskId)
    {
        $task = Task::find($taskId);
        if ($task && $task->assigned_to === auth()->id()) {
            $task->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            $this->loadStats();
            session()->flash('message', 'Tarea marcada como completada.');
        }
    }

    public function render()
    {
        return view('livewire.student.dashboard', [
            'recentTasks' => $this->user->tasks()->with('workshop')->latest()->take(5)->get(),
        ]);
    }
}