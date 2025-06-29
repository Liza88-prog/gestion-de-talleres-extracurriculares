<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Workshop;
use App\Models\Task;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalStudents;
    public $totalWorkshops;
    public $totalTasks;
    public $completedTasks;
    public $recentTasks;
    public $activeWorkshops;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalStudents = User::where('role', 'student')->count();
        $this->totalWorkshops = Workshop::count();
        $this->totalTasks = Task::count();
        $this->completedTasks = Task::where('status', 'completed')->count();
        $this->recentTasks = Task::with(['assignedTo', 'workshop'])
            ->latest()
            ->take(5)
            ->get();
        $this->activeWorkshops = Workshop::where('status', 'active')
            ->with('students')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}