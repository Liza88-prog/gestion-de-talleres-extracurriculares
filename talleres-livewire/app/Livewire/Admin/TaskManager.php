<?php

namespace App\Livewire\Admin;

use App\Models\Task;
use App\Models\User;
use App\Models\Workshop;
use Livewire\Component;
use Livewire\WithPagination;

class TaskManager extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editMode = false;
    public $taskId;
    
    public $title = '';
    public $description = '';
    public $workshop_id = '';
    public $assigned_to = '';
    public $due_date = '';
    public $priority = 'medium';
    public $status = 'pending';

    protected $rules = [
        'title' => 'required|min:3',
        'description' => 'required',
        'workshop_id' => 'required|exists:workshops,id',
        'assigned_to' => 'required|exists:users,id',
        'due_date' => 'required|date|after:today',
        'priority' => 'required|in:low,medium,high',
        'status' => 'required|in:pending,in_progress,completed',
    ];

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editMode = false;
        $this->taskId = null;
        $this->title = '';
        $this->description = '';
        $this->workshop_id = '';
        $this->assigned_to = '';
        $this->due_date = '';
        $this->priority = 'medium';
        $this->status = 'pending';
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'workshop_id' => $this->workshop_id,
            'assigned_to' => $this->assigned_to,
            'assigned_by' => auth()->id(),
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'status' => $this->status,
        ];

        if ($this->editMode) {
            Task::find($this->taskId)->update($data);
            session()->flash('message', 'Tarea actualizada exitosamente.');
        } else {
            Task::create($data);
            session()->flash('message', 'Tarea creada exitosamente.');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $task = Task::find($id);
        $this->editMode = true;
        $this->taskId = $id;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->workshop_id = $task->workshop_id;
        $this->assigned_to = $task->assigned_to;
        $this->due_date = $task->due_date->format('Y-m-d\TH:i');
        $this->priority = $task->priority;
        $this->status = $task->status;
        $this->showModal = true;
    }

    public function delete($id)
    {
        Task::find($id)->delete();
        session()->flash('message', 'Tarea eliminada exitosamente.');
    }

    public function render()
    {
        return view('livewire.admin.task-manager', [
            'tasks' => Task::with(['assignedTo', 'workshop'])->latest()->paginate(10),
            'workshops' => Workshop::where('status', 'active')->get(),
            'students' => User::where('role', 'student')->get(),
        ]);
    }
}