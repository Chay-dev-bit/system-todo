<?php

namespace App\Livewire\Todo;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Task as TaskModel;
use App\Models\Project as ProjectModel;
use App\Models\Pengguna;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Services\WahaService;

class Task extends Component
{
    use WithPagination, WithFileUploads;

    public $project_id;
    public $project;

    public $task_id;
    public $title;
    public $description;
    public $assigned_to;
    public $priority;
    public $attachment;
    public $status;

    public $perPage = 5;
    public $search = '';

    public $confirmInput = false;
    public $confirmEdit = false;
    public $confirmUpload = false;
    public $confirmReject = false;
    public $task_id_for_upload;
    public $revision_notes;
    public $task_id_for_reject;
    public $rejection_note;

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
        $this->confirmReject = false;
        $this->task_id_for_upload = null;
        $this->task_id_for_reject = null;
        $this->revision_notes = null;
        $this->rejection_note = null;
    }

    public function showReject($id)
    {
        $this->task_id_for_reject = $id;
        $this->rejection_note = null;
        $this->confirmReject = true;
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

        $task->project->updateStatus();
        $this->sendNotificationToAsmen($task);

        session()->flash('success', 'Hasil berhasil dikirim!');

        $this->closeModal();
    }

    protected function sendNotificationToAsmen($task)
    {
        $waha = new WahaService();
        $asmen = $task->project->asmen;

        if (!$asmen) {
            return;
        }

        $staffName = auth()->user()->nama_lengkap ?? 'Staff';
        $message = "Halo, ada task baru yang menunggu verifikasi!\n\n" .
                   "Staff: {$staffName}\n" .
                   "Judul Task: {$task->title}\n" .
                   "Project: {$task->project->project_name}\n" .
                   "Silakan cek di sistem!";

        $phoneNumber = $asmen->no_wa ?: ($asmen->pegawai?->no_telp);
        if ($phoneNumber) {
            $waha->sendWhatsApp($phoneNumber, $message);
        }
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
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,submitted,verified,approved,rejected,cancelled',
        ]);

        $task = TaskModel::create([
            'project_id' => $this->project_id,
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to,
            'priority' => $this->priority,
            'status' => $this->status ?? 'pending',
            'progress' => 0,
            'created_by' => auth()->user()->nip ?? null,
        ]);

        $task->project->updateStatus();

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
        $this->priority = $task->priority;
        $this->status = $task->status;

        $this->confirmEdit = true;
    }

    public function update()
    {
        $this->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'assigned_to' => 'required|exists:pengguna,nip',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,submitted,verified,approved,rejected,cancelled',
        ]);

        $task = TaskModel::findOrFail($this->task_id);

        $task->update([
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to,
            'priority' => $this->priority,
            'status' => $this->status,
            'updated_by' => auth()->user()->nip ?? null,
        ]);

        $task->project->updateStatus();

        session()->flash('success', 'Task berhasil diupdate');

        $this->resetForm();
        $this->closeModal();
    }

    public function verifikasi($id)
    {
        try {
            $task = TaskModel::findOrFail($id);
            $user = auth()->user();

            if (!$user) {
                session()->flash('error', 'Anda harus login terlebih dahulu!');
                return;
            }

            if (!$user->isAsmen()) {
                session()->flash('error', 'Hanya Asisten Manajer yang bisa melakukan verifikasi!');
                return;
            }

            if ($task->status !== 'submitted') {
                session()->flash('error', 'Task ini tidak dalam status menunggu verifikasi!');
                return;
            }

            if ($task->project?->asmen_id && $user->nip !== $task->project->asmen_id) {
                session()->flash('error', 'Anda tidak memiliki akses verifikasi task pada project ini!');
                return;
            }

            $task->update([
                'status' => 'verified',
                'verified_by' => $user->nip ?? null,
                'verified_at' => now(),
                'updated_by' => $user->nip ?? null,
            ]);

            $task->project->updateStatus();
            $this->sendNotificationToManajer($task, $user);
            $this->sendNotificationVerifiedToStaff($task, $user);

            session()->flash('success', 'Task berhasil diverifikasi!');
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        try {
            $task = TaskModel::findOrFail($id);
            $user = auth()->user();

            if (!$user) {
                session()->flash('error', 'Anda harus login terlebih dahulu!');
                return;
            }

            if (!$user->isManajer()) {
                session()->flash('error', 'Hanya Manajer yang bisa melakukan approve!');
                return;
            }

            $task->update([
                'status' => 'approved',
                'approved_by' => $user->nip ?? null,
                'approved_at' => now(),
                'updated_by' => $user->nip ?? null,
            ]);

            $task->project->updateStatus();

            session()->flash('success', 'Task berhasil diapprove!');
            $this->resetPage();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function reject()
    {
        $this->validate([
            'rejection_note' => 'required|min:10|max:500',
        ]);

        $task = TaskModel::findOrFail($this->task_id_for_reject);

        $task->update([
            'status' => 'rejected',
            'rejection_note' => $this->rejection_note,
            'rejected_by' => auth()->user()->nip ?? null,
            'rejected_at' => now(),
            'updated_by' => auth()->user()->nip ?? null,
        ]);

        $task->project->updateStatus();
        $this->sendNotificationToStaff($task);

        session()->flash('success', 'Task berhasil ditolak! Pesan akan dikirim ke staff.');

        $this->closeModal();
    }

    protected function sendNotificationToManajer($task, $asmen)
    {
        $waha = new WahaService();
        $manajer = $task->project->manajer;

        if (!$manajer) {
            return;
        }

        $asmenName = $asmen->nama_lengkap ?? 'Asisten Manajer';
        $message = "Halo, ada task baru yang menunggu approve!\n\n" .
                   "Asisten Manajer: {$asmenName}\n" .
                   "Judul Task: {$task->title}\n" .
                   "Project: {$task->project->project_name}\n" .
                   "Silakan cek di sistem!";

        $phoneNumber = $manajer->no_wa ?: ($manajer->pegawai?->no_telp);
        if ($phoneNumber) {
            $waha->sendWhatsApp($phoneNumber, $message);
        }
    }

    protected function sendNotificationToStaff($task)
    {
        $waha = new WahaService();
        $staff = $task->assignee;

        if (!$staff) {
            return;
        }

        $rejectorName = auth()->user()->nama_lengkap ?? 'User';
        $message = "Halo, task Anda telah ditolak!\n\n" .
                   "Judul Task: {$task->title}\n" .
                   "Ditolak oleh: {$rejectorName}\n" .
                   "Alasan: {$this->rejection_note}\n" .
                   "Silakan cek di sistem!";

        $phoneNumber = $staff->no_wa ?: ($staff->pegawai?->no_telp);
        if ($phoneNumber) {
            $waha->sendWhatsApp($phoneNumber, $message);
        }
    }

    protected function sendNotificationVerifiedToStaff($task, $asmen): void
    {
        $waha = new WahaService();
        $staff = $task->assignee;

        if (!$staff) {
            return;
        }

        $asmenName = $asmen->nama_lengkap ?? 'Asisten Manajer';
        $message = "Halo, task Anda sudah diverifikasi!\n\n" .
                   "Judul Task: {$task->title}\n" .
                   "Project: {$task->project->project_name}\n" .
                   "Diverifikasi oleh: {$asmenName}\n" .
                   "Status: Menunggu approve Manajer\n\n" .
                   "Silakan cek di sistem!";

        $phoneNumber = $staff->no_wa ?: ($staff->pegawai?->no_telp);
        if ($phoneNumber) {
            $waha->sendWhatsApp($phoneNumber, $message);
        }
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
        $task = TaskModel::findOrFail($id);
        $project = $task->project;
        $task->delete();
        
        $project->updateStatus();
        
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
            'priority',
            'status',
        ]);

        $this->status = 'pending';
        $this->priority = 'medium';
    }
}
