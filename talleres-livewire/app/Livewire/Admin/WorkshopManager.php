<?php

namespace App\Livewire\Admin;

use App\Models\Workshop;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;


class WorkshopManager extends Component
{
    use WithPagination;

    public $showForm = false;
    public $editMode = false;
    public $workshopId;
    
    public $name = '';
    public $description = '';
    public $instructor = '';
    public $capacity = 20;
    public $start_date = '';
    public $end_date = '';
    public $location = '';
    public $status = 'active';

    protected $rules = [
        'name' => 'required|min:3',
        'description' => 'required',
        'instructor' => 'required',
        'capacity' => 'required|integer|min:1',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'location' => 'required',
        'status' => 'required|in:active,inactive',
    ];

 public function openForm()
{
    Log::info('openForm triggered');
    $this->resetForm();
    $this->showForm = true;
}


    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editMode = false;
        $this->workshopId = null;
        $this->name = '';
        $this->description = '';
        $this->instructor = '';
        $this->capacity = 20;
        $this->start_date = '';
        $this->end_date = '';
        $this->location = '';
        $this->status = 'active';
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'instructor' => $this->instructor,
            'capacity' => $this->capacity,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'location' => $this->location,
            'status' => $this->status,
        ];

        if ($this->editMode) {
            Workshop::find($this->workshopId)->update($data);
            session()->flash('message', 'Taller actualizado exitosamente.');
        } else {
            Workshop::create($data);
            session()->flash('message', 'Taller creado exitosamente.');
        }

        $this->closeForm();
    }

    public function edit($id)
    {
        $workshop = Workshop::find($id);
        $this->editMode = true;
        $this->workshopId = $id;
        $this->name = $workshop->name;
        $this->description = $workshop->description;
        $this->instructor = $workshop->instructor;
        $this->capacity = $workshop->capacity;
        $this->start_date = $workshop->start_date->format('Y-m-d');
        $this->end_date = $workshop->end_date->format('Y-m-d');
        $this->location = $workshop->location;
        $this->status = $workshop->status;
        $this->showForm = true;
    }

    public function delete($id)
    {
        Workshop::find($id)->delete();
        session()->flash('message', 'Taller eliminado exitosamente.');
    }

    public function render()
{
    $workshops = Workshop::paginate(10); // o withCount('students') si usás la relación

    return view('livewire.admin.workshop-manager', [
        'workshops' => $workshops
    ])->layout('layouts.app');
}


}
