<?php

namespace App\Livewire\Todo;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Task as TaskModel;
use App\Models\Project as ProjectModel;
use App\Models\Pengguna;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Task extends Component
{
    use WithPagination, WithFileUploads;

    public $project_id;
    public $project;

    public $task_id;
    public $title;
    public $description;
    public $assigned_to;
    public $deadline;
    public $priority;
    public $attachment;
    public $status;
    public $progress;

    public $perPage = 5;
    public $search = '';

    public $confirmInput = false;
    public $confirmEdit = false;
    public $confirmUpload = false;
    public $task_id_for_upload;
    public $revision_notes;

    public function mount($projectId)
    {
        $this->project_id = $projectId;
        $this->project = ProjectModel::findOrFail($projectId);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function showDataInput()
    {
        $this->resetForm();
        $this->confirmInput = true;
    }

    public function closeModal()
    {
        $this->confirmInput = false;
        $this->confirmEdit = false;
        $this->confirmUpload = false;
        $this->task_id_for_upload = null;
        $this->revision_notes = null;
    }

    public function showUploadFile($id)
    {
        $this->task_id_for_upload = $id;
        $this->revision_notes = null;
        $this->attachment = null;
        $this->confirmUpload = true;
    }

    public function uploadFile()
    {
        $this->validate([
            'attachment' => 'required|file|max:10240',
            'revision_notes' => 'nullable|max:500',
        ]);

        $task = TaskModel::findOrFail($this->task_id_for_upload);

        $attachmentPath = $task->attachment;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('task-attachments', 'public');
        }

        $task->update([
            'attachment' => $attachmentPath,
            'revision_notes' => $this->revision_notes,
            'status' => 'submitted',
            'submitted_at' => now(),
            'updated_by' => auth()->user()->nip ?? null,
        ]);

        session()->flash('success', 'Hasil berhasil dikirim!');

        $this->closeModal();
    }

    public function render()
    {
        $tasks = TaskModel::where('project_id', $this->project_id)
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->paginate($this->perPage);

        $penggunas = Pengguna::all();

        return view('livewire.todo.task', [
            'tasks' => $tasks,
            'penggunas' => $penggunas,
            'project' => $this->project,
        ])->layout('layouts.app');
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'assigned_to' => 'required|exists:pengguna,nip',
            'deadline' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,submitted,verified,approved,cancelled',
            'progress' => 'nullable|integer|min:0|max:100',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $attachmentPath = null;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('task-attachments', 'public');
        }

        TaskModel::create([
            'project_id' => $this->project_id,
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to,
            'deadline' => $this->deadline,
            'priority' => $this->priority,
            'status' => $this->status ?? 'pending',
            'progress' => $this->progress ?? 0,
            'attachment' => $attachmentPath,
            'created_by' => auth()->user()->nip ?? null,
        ]);

        session()->flash('success', 'Task berhasil ditambahkan');

        $this->resetForm();
        $this->closeModal();
    }

    public function edit($id)
    {
        $task = TaskModel::findOrFail($id);

        $this->task_id = $task->id;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->assigned_to = $task->assigned_to;
        $this->deadline = $task->deadline;
        $this->priority = $task->priority;
        $this->status = $task->status;
        $this->progress = $task->progress;

        $this->confirmEdit = true;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'assigned_to' => 'required|exists:pengguna,nip',
            'deadline' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,submitted,verified,approved,cancelled',
            'progress' => 'nullable|integer|min:0|max:100',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $task = TaskModel::findOrFail($this->task_id);

        $attachmentPath = $task->attachment;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('task-attachments', 'public');
        }

        $status = $this->status;
        $submittedAt = $task->submitted_at;
        
        if (in_array($task->status, ['pending', 'in_progress']) && $this->attachment) {
            $status = 'submitted';
            $submittedAt = now();
        }

        $task->update([
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to,
            'deadline' => $this->deadline,
            'priority' => $this->priority,
            'status' => $status,
            'progress' => $this->progress,
            'attachment' => $attachmentPath,
            'submitted_at' => $submittedAt,
            'updated_by' => auth()->user()->nip ?? null,
        ]);

        session()->flash('success', 'Task berhasil diupdate');

        $this->resetForm();
        $this->closeModal();
    }

    public function verifikasi($id)
    {
        $task = TaskModel::findOrFail($id);

        $task->update([
            'status' => 'verified',
            'verified_by' => auth()->user()->nip ?? null,
            'verified_at' => now(),
            'updated_by' => auth()->user()->nip ?? null,
        ]);

        session()->flash('success', 'Task berhasil diverifikasi!');
    }

    public function approve($id)
    {
        $task = TaskModel::findOrFail($id);

        $task->update([
            'status' => 'approved',
            'approved_by' => auth()->user()->nip ?? null,
            'approved_at' => now(),
            'updated_by' => auth()->user()->nip ?? null,
        ]);

        session()->flash('success', 'Task berhasil diapprove!');
    }

    public function confirmDelete($id)
    {
        $this->dispatch('show-delete-confirmation', id: $id);
    }

    #[On('deleteConfirmed')]
    public function deleteConfirmed($id)
    {
        $this->delete($id);
    }

    public function delete($id)
    {
        TaskModel::findOrFail($id)->delete();
        session()->flash('success', 'Task berhasil dihapus');

        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset([
            'task_id',
            'title',
            'description',
            'assigned_to',
            'deadline',
            'priority',
            'status',
            'progress',
            'attachment',
        ]);

        $this->status = 'pending';
        $this->priority = 'medium';
        $this->progress = 0;
    }
}
