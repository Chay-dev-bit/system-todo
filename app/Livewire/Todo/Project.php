<?php

namespace App\Livewire\Todo;

use Livewire\Component;
use App\Models\Project as ProjectModel;
use App\Models\Pengguna;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Services\WahaService;

class Project extends Component
{
    use WithPagination;

    /*
    |--------------------------------------------------------------------------
    | PRIMARY KEY
    |--------------------------------------------------------------------------
    */

    public $project_id;

    /*
    |--------------------------------------------------------------------------
    | FIELD FORM
    |--------------------------------------------------------------------------
    */

    public $kode_project;
    public $project_name;
    public $description;
    public $start_date;
    public $end_date;
    public $capex_or_opex;
    public $no_rekening;
    public $biaya;
    public $biaya_formatted;
    public $vendor;
    public $pic_id;
    public $asmen_id;
    public $manajer_id;
    public $status;
    public $verified_by;
    public $approved_by;
    public $project_id_for_reject;
    public $rejection_note;

    /*
    |--------------------------------------------------------------------------
    | TABLE CONFIG
    |--------------------------------------------------------------------------
    */

    public $perPage = 5;
    public $search = '';

    /*
    |--------------------------------------------------------------------------
    | MODAL
    |--------------------------------------------------------------------------
    */

    public $confirmInput = false;
    public $confirmEdit = false;
    public $confirmReject = false;

    /*
    |--------------------------------------------------------------------------
    | SEARCH & PAGINATION
    |--------------------------------------------------------------------------
    */

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | MODAL
    |--------------------------------------------------------------------------
    */

    public function showDataInput()
    {
        $this->resetForm();
        $this->status = 'pending';
        $this->confirmInput = true;
    }

    public function closeModal()
    {
        $this->confirmInput = false;
        $this->confirmEdit = false;
        $this->confirmReject = false;
        $this->project_id_for_reject = null;
        $this->rejection_note = null;
    }

    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $projects = ProjectModel::query()
            ->with(['creator', 'pic', 'asmen', 'manajer', 'verifier', 'approver', 'rejector'])
            ->when($this->search, function ($query) {
                $query->where('kode_project', 'like', '%' . $this->search . '%')
                    ->orWhere('project_name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->paginate($this->perPage);

        $penggunas = Pengguna::all();

        return view('livewire.todo.project', [
            'projects' => $projects,
            'penggunas' => $penggunas,
        ])->layout('layouts.app');
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE
    |--------------------------------------------------------------------------
    */

    public function save()
    {
        $this->validate([
            'kode_project' => 'required|unique:projects,kode_project|max:255',
            'project_name' => 'required|max:255',
            'description' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'capex_or_opex' => 'nullable|in:capex,opex',
            'no_rekening' => 'nullable|max:255',
            'biaya_formatted' => 'nullable',
            'vendor' => 'nullable|max:255',
            'pic_id' => 'required|exists:pengguna,nip',
            'asmen_id' => 'nullable|exists:pengguna,nip',
            'manajer_id' => 'nullable|exists:pengguna,nip',
            'status' => 'required|in:pending,ongoing,completed,cancelled',
            'verified_by' => 'nullable|exists:pengguna,nip',
            'approved_by' => 'nullable|exists:pengguna,nip',
        ]);

        $this->biaya = $this->parseRupiah($this->biaya_formatted);

        $project = ProjectModel::create([
            'kode_project' => $this->kode_project,
            'project_name' => $this->project_name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'capex_or_opex' => $this->capex_or_opex,
            'no_rekening' => $this->no_rekening,
            'biaya' => $this->biaya,
            'vendor' => $this->vendor,
            'pic_id' => $this->pic_id,
            'asmen_id' => $this->asmen_id,
            'manajer_id' => $this->manajer_id,
            'status' => $this->status ?? 'pending',
            'approval_status' => 'pending',
            'verified_by' => null,
            'verified_at' => null,
            'approved_by' => null,
            'approved_at' => null,
            'rejection_note' => null,
            'rejected_by' => null,
            'rejected_at' => null,
            'created_by' => auth()->user()->nip ?? null,
        ]);

        $project->updateStatus();
        $this->sendNotificationProjectToAsmen($project);

        session()->flash('success', 'Project berhasil ditambahkan');

        $this->resetForm();
        $this->closeModal();
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $project = ProjectModel::findOrFail($id);

        $this->project_id = $project->id;
        $this->kode_project = $project->kode_project;
        $this->project_name = $project->project_name;
        $this->description = $project->description;
        $this->start_date = $project->start_date;
        $this->end_date = $project->end_date;
        $this->capex_or_opex = $project->capex_or_opex;
        $this->no_rekening = $project->no_rekening;
        $this->biaya = $project->biaya;
        $this->biaya_formatted = $this->formatRupiah($project->biaya);
        $this->vendor = $project->vendor;
        $this->pic_id = $project->pic_id;
        $this->asmen_id = $project->asmen_id;
        $this->manajer_id = $project->manajer_id;
        $this->status = $project->status;
        $this->verified_by = $project->verified_by;
        $this->approved_by = $project->approved_by;

        $this->confirmEdit = true;
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update()
    {
        $this->validate([
            'kode_project' => 'required|max:255|unique:projects,kode_project,' . $this->project_id,
            'project_name' => 'required|max:255',
            'description' => 'nullable',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'capex_or_opex' => 'nullable|in:capex,opex',
            'no_rekening' => 'nullable|max:255',
            'biaya_formatted' => 'nullable',
            'vendor' => 'nullable|max:255',
            'pic_id' => 'required|exists:pengguna,nip',
            'asmen_id' => 'nullable|exists:pengguna,nip',
            'manajer_id' => 'nullable|exists:pengguna,nip',
            'status' => 'required|in:pending,ongoing,completed,cancelled',
            'verified_by' => 'nullable|exists:pengguna,nip',
            'approved_by' => 'nullable|exists:pengguna,nip',
        ]);

        $this->biaya = $this->parseRupiah($this->biaya_formatted);

        $project = ProjectModel::findOrFail($this->project_id);

        $project->update([
            'kode_project' => $this->kode_project,
            'project_name' => $this->project_name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'capex_or_opex' => $this->capex_or_opex,
            'no_rekening' => $this->no_rekening,
            'biaya' => $this->biaya,
            'vendor' => $this->vendor,
            'pic_id' => $this->pic_id,
            'asmen_id' => $this->asmen_id,
            'manajer_id' => $this->manajer_id,
            'status' => $this->status,
            'verified_by' => $project->verified_by,
            'approved_by' => $project->approved_by,
            'updated_by' => auth()->user()->nip ?? null,
        ]);

        if ($project->approval_status === 'rejected' && (auth()->user()->nip ?? null) === $project->created_by) {
            $project->update([
                'approval_status' => 'pending',
                'verified_by' => null,
                'verified_at' => null,
                'approved_by' => null,
                'approved_at' => null,
                'rejection_note' => null,
                'rejected_by' => null,
                'rejected_at' => null,
            ]);
            $this->sendNotificationProjectToAsmen($project);
        }

        session()->flash('success', 'Project berhasil diupdate');

        $this->resetForm();
        $this->closeModal();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

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
        try {
            ProjectModel::findOrFail($id)->delete();
            session()->flash('success', 'Project berhasil dihapus');
        } catch (\Exception $e) {
            session()->flash('error', 'Project tidak bisa dihapus karena masih memiliki task!');
        }

        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | RESET FORM
    |--------------------------------------------------------------------------
    */

    public function resetForm()
    {
        $this->reset([
            'project_id',
            'kode_project',
            'project_name',
            'description',
            'start_date',
            'end_date',
            'capex_or_opex',
            'no_rekening',
            'biaya',
            'biaya_formatted',
            'vendor',
            'pic_id',
            'asmen_id',
            'manajer_id',
            'verified_by',
            'approved_by',
            'project_id_for_reject',
            'rejection_note',
        ]);
        
        $this->status = 'pending';
    }

    public function verifyProject($id)
    {
        try {
            $project = ProjectModel::findOrFail($id);
            $user = auth()->user();

            if (!$user) {
                session()->flash('error', 'Anda harus login terlebih dahulu!');
                return;
            }

            if (!$user->isAsmen() || ($project->asmen_id && $user->nip !== $project->asmen_id)) {
                session()->flash('error', 'Anda tidak memiliki akses verifikasi project ini!');
                return;
            }

            if ($project->approval_status !== 'pending') {
                session()->flash('error', 'Project ini tidak dalam status menunggu verifikasi!');
                return;
            }

            $project->update([
                'approval_status' => 'verified',
                'verified_by' => $user->nip ?? null,
                'verified_at' => now(),
                'approved_by' => null,
                'approved_at' => null,
                'rejection_note' => null,
                'rejected_by' => null,
                'rejected_at' => null,
                'updated_by' => $user->nip ?? null,
            ]);

            $this->sendNotificationProjectToManajer($project, $user);
            session()->flash('success', 'Project berhasil diverifikasi!');
            $this->resetPage();
        } catch (\Throwable $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function approveProject($id)
    {
        try {
            $project = ProjectModel::findOrFail($id);
            $user = auth()->user();

            if (!$user) {
                session()->flash('error', 'Anda harus login terlebih dahulu!');
                return;
            }

            if (!$user->isManajer() || ($project->manajer_id && $user->nip !== $project->manajer_id)) {
                session()->flash('error', 'Anda tidak memiliki akses approve project ini!');
                return;
            }

            if ($project->approval_status !== 'verified') {
                session()->flash('error', 'Project ini tidak dalam status menunggu approve!');
                return;
            }

            $project->update([
                'approval_status' => 'approved',
                'approved_by' => $user->nip ?? null,
                'approved_at' => now(),
                'rejection_note' => null,
                'rejected_by' => null,
                'rejected_at' => null,
                'updated_by' => $user->nip ?? null,
            ]);

            $this->sendNotificationProjectApprovedToCreator($project, $user);
            session()->flash('success', 'Project berhasil diapprove!');
            $this->resetPage();
        } catch (\Throwable $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showRejectProject($id)
    {
        $this->project_id_for_reject = $id;
        $this->rejection_note = null;
        $this->confirmReject = true;
    }

    public function rejectProject()
    {
        $this->validate([
            'rejection_note' => 'required|min:10|max:500',
        ]);

        try {
            $project = ProjectModel::findOrFail($this->project_id_for_reject);
            $user = auth()->user();

            if (!$user) {
                session()->flash('error', 'Anda harus login terlebih dahulu!');
                return;
            }

            $isAsmenReject = $user->isAsmen() && ($project->asmen_id ? $user->nip === $project->asmen_id : true) && $project->approval_status === 'pending';
            $isManajerReject = $user->isManajer() && ($project->manajer_id ? $user->nip === $project->manajer_id : true) && $project->approval_status === 'verified';

            if (!$isAsmenReject && !$isManajerReject) {
                session()->flash('error', 'Anda tidak memiliki akses menolak project ini!');
                return;
            }

            $payload = [
                'approval_status' => 'rejected',
                'rejection_note' => $this->rejection_note,
                'rejected_by' => $user->nip ?? null,
                'rejected_at' => now(),
                'approved_by' => null,
                'approved_at' => null,
                'updated_by' => $user->nip ?? null,
            ];

            if ($isAsmenReject) {
                $payload['verified_by'] = null;
                $payload['verified_at'] = null;
            }

            $project->update($payload);
            $this->sendNotificationProjectRejectedToCreator($project, $user, $this->rejection_note);

            session()->flash('success', 'Project berhasil ditolak! Pesan akan dikirim ke pembuat project.');
            $this->closeModal();
            $this->resetPage();
        } catch (\Throwable $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    protected function sendNotificationProjectToAsmen(ProjectModel $project): void
    {
        $asmen = $project->asmen;
        if (!$asmen) {
            return;
        }

        $creatorName = $project->creator->nama_lengkap ?? 'Pembuat Project';
        $message = "Halo, ada project baru yang menunggu verifikasi!\n\n" .
            "Kode: " . ($project->kode_project ?? '-') . "\n" .
            "Nama: {$project->project_name}\n" .
            "Dibuat oleh: {$creatorName}\n" .
            "Silakan cek di sistem.";

        $phoneNumber = $asmen->no_wa ?: ($asmen->pegawai?->no_telp);
        if (!$phoneNumber) {
            return;
        }

        (new WahaService())->sendWhatsApp($phoneNumber, $message);
    }

    protected function sendNotificationProjectToManajer(ProjectModel $project, Pengguna $asmen): void
    {
        $manajer = $project->manajer;
        if (!$manajer) {
            return;
        }

        $asmenName = $asmen->nama_lengkap ?? 'Asisten Manajer';
        $message = "Halo, ada project baru yang menunggu approve!\n\n" .
            "Kode: " . ($project->kode_project ?? '-') . "\n" .
            "Nama: {$project->project_name}\n" .
            "Diverifikasi oleh: {$asmenName}\n" .
            "Silakan cek di sistem.";

        $phoneNumber = $manajer->no_wa ?: ($manajer->pegawai?->no_telp);
        if (!$phoneNumber) {
            return;
        }

        (new WahaService())->sendWhatsApp($phoneNumber, $message);
    }

    protected function sendNotificationProjectRejectedToCreator(ProjectModel $project, Pengguna $rejector, string $note): void
    {
        $creator = $project->creator;
        if (!$creator) {
            return;
        }

        $rejectorName = $rejector->nama_lengkap ?? 'User';
        $message = "Halo, project Anda ditolak!\n\n" .
            "Kode: " . ($project->kode_project ?? '-') . "\n" .
            "Nama: {$project->project_name}\n" .
            "Ditolak oleh: {$rejectorName}\n" .
            "Alasan: {$note}\n" .
            "Silakan revisi lalu simpan ulang project.";

        $phoneNumber = $creator->no_wa ?: ($creator->pegawai?->no_telp);
        if (!$phoneNumber) {
            return;
        }

        (new WahaService())->sendWhatsApp($phoneNumber, $message);
    }

    protected function sendNotificationProjectApprovedToCreator(ProjectModel $project, Pengguna $approver): void
    {
        $creator = $project->creator;
        if (!$creator) {
            return;
        }

        $approverName = $approver->nama_lengkap ?? 'Manajer';
        $message = "Halo, project Anda sudah diapprove!\n\n" .
            "Kode: " . ($project->kode_project ?? '-') . "\n" .
            "Nama: {$project->project_name}\n" .
            "Diapprove oleh: {$approverName}\n" .
            "Silakan cek di sistem.";

        $phoneNumber = $creator->no_wa ?: ($creator->pegawai?->no_telp);
        if (!$phoneNumber) {
            return;
        }

        (new WahaService())->sendWhatsApp($phoneNumber, $message);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER FORMAT & PARSE RUPIAH
    |--------------------------------------------------------------------------
    */

    private function formatRupiah($number)
    {
        if (!$number) return '';
        return 'Rp ' . number_format($number, 2, ',', '.');
    }

    private function parseRupiah($formatted)
    {
        if (!$formatted) return null;
        $number = preg_replace('/[^0-9,]/', '', $formatted);
        $number = str_replace(',', '.', $number);
        return (float) $number;
    }
}
