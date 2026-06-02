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

    // menyimpan id project yang sedang diedit atau ditolak
    public $project_id;

    // field form input project
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

    //    digunakan untuk status project awal
    public $status;

    // digunakan saat task sudah berjalan
    public $approval_status;

    // approval info
    public $verified_by;
    public $approved_by;

    // untuk reject
    public $project_id_for_reject;
    public $rejection_note;

    // tabel config
    public $perPage = 5;
    public $search = '';

    // modal control
    public $confirmInput = false;
    public $confirmEdit = false;
    public $confirmReject = false;

    // untuk cancel project
    public $cancel_note;
    public $confirmCancel = false;
    public $project_id_for_cancel;

    // setup pagination theme
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    // menampilkan modal input project
    public function showDataInput()
    {
        $this->resetForm();
        // $this->status = 'pending';
        $this->confirmInput = true;
    }

    // menutup semua modal
    public function closeModal()
    {
        $this->confirmInput = false;
        $this->confirmEdit = false;
        $this->confirmReject = false;
        $this->project_id_for_reject = null;
        $this->rejection_note = null;
        $this->confirmCancel = false;
        $this->project_id_for_cancel = null;
        $this->cancel_note = null;
    }
    // render view dengan data projects yang sudah difilter berdasarkan search dan pagination
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

        $asmen = Pengguna::whereHas('pegawai', function ($query) {
            $query->where('jabatan_id', 'ASMEN');
        })->get();
        $manajers = Pengguna::whereHas('pegawai', function ($query) {
            $query->where('jabatan_id', 'MANAGR');
        })->get();
        return view('livewire.todo.project', [
            'projects' => $projects,
            'penggunas' => $penggunas,
            'asmens' => $asmen,
            'manajers' => $manajers,
        ])->layout('layouts.app');
    }

    // menyimpan data project baru ke database setelah validasi
    public function save()
    {
        // validasi inpur
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
        ]);
        // convert rupiah
        $this->biaya = $this->parseRupiah($this->biaya_formatted);

        // create project
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
            // Approval awal project 
            'status' => 'ongoing',
            // Progress task project 
            'approval_status' => 'progress',
            'created_by' => auth()->user()->nip ?? null,
        ]);

        // kirim notifikasi ke asmen jika project dibuat oleh user biasa
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

        // isi form edit
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
        $this->approval_status = $project->approval_status;

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
        ]);

        $project = ProjectModel::findOrFail($this->project_id);

        // project approved tidak boleh diedit
        if (
            in_array($project->status, [
                'verified',
                'approved',
                'cancelled'
            ])
        )
            $resetStatus =
                $project->verified_by
                ? 'verified'
                : 'ongoing';
        // hanya creator project yang boleh edit project rejected
        if (
            $project->status === 'rejected' &&
            (auth()->user()->nip ?? null) !== $project->created_by
        ) {
            session()->flash(
                'error',
                'Hanya pembuat project yang dapat merevisi project rejected!'
            );

            return;
        }
        $this->biaya = $this->parseRupiah($this->biaya_formatted);

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
            'updated_by' => auth()->user()->nip ?? null,
        ]);

        // jika project sebelumnya di rejected maka reset menjeadi ongoing lagi
        if ($project->status === 'rejected' && (auth()->user()->nip ?? null) === $project->created_by) {
            $project->update([
                'status' => $resetStatus,
                'approval_status' => 'progress',
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
        $project = ProjectModel::findOrFail($id);
        // hanya sipembuat yang boleh hapus
        if (
            $project->created_by !== (auth()->user()->nip ?? null)
        ) {

            session()->flash(
                'error',
                'Hanya pembuat project yang dapat menghapus project!'
            );

            return;
        }

        if (
            in_array($project->status, [
                'verified',
                'approved',
                'cancelled'
            ])
        ) {
            session()->flash(
                'error',
                'Project yang sudah berjalan tidak dapat dihapus!'
            );

            return;
        }
        try {
            $project->delete();
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
            'status',
            'approval_status',
            'verified_by',
            'approved_by',
            'project_id_for_reject',
            'rejection_note',
        ]);

        // $this->status = 'pending';
    }

    // asmen memverifikasi project 
    public function verifyProject($id)
    {
        try {

            $project = ProjectModel::findOrFail($id);
            $user = auth()->user();

            // cek login
            if (!$user) {
                session()->flash('error', 'Anda harus login terlebih dahulu!');
                return;
            }

            // cek role asmen
            if (!$user->isAsmen() || ($project->asmen_id && $user->nip !== $project->asmen_id)) {
                session()->flash('error', 'Anda tidak memiliki akses verifikasi project ini!');
                return;
            }
            // project yang sudah diverifikasi tidak dapat dibatalkan
            if ($project->status === 'cancelled') {
                session()->flash(
                    'error',
                    'Project yang dibatalkan tidak dapat diverifikasi!'
                );
                return;
            }
            // hanya project ongoing yang bisa diverifikasi
            if ($project->status !== 'ongoing') {
                session()->flash('error', 'Project ini tidak dalam status menunggu verifikasi!');
                return;
            }

            // update status project menjadi verified dan simpan info verifikator
            $project->update([
                'status' => 'verified',
                'verified_by' => $user->nip ?? null,
                'verified_at' => now(),
                'updated_by' => $user->nip ?? null,
            ]);

            // kirim notifikasi
            $this->sendNotificationProjectToManajer($project, $user);
            $this->sendNotificationProjectVerifiedToCreator($project, $user);
            session()->flash('success', 'Project berhasil diverifikasi!');
            $this->resetPage();
        } catch (\Throwable $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // manajer mengapprove project yang sudah diverifikasi asmen
    public function approveProject($id)
    {
        try {
            $project = ProjectModel::findOrFail($id);
            $user = auth()->user();

            // cek login
            if (!$user) {
                session()->flash('error', 'Anda harus login terlebih dahulu!');
                return;
            }

            // cek role manejer
            if (!$user->isManajer() || ($project->manajer_id && $user->nip !== $project->manajer_id)) {
                session()->flash('error', 'Anda tidak memiliki akses approve project ini!');
                return;
            }
            // project yang sudah di approve tidak boleh di canceller
            if ($project->status === 'cancelled') {

                session()->flash(
                    'error',
                    'Project yang dibatalkan tidak dapat diapprove!'
                );

                return;
            }
            // hanya project yang sudah diverifikasi asmen yang bisa diapprove manajer
            if ($project->status !== 'verified') {
                session()->flash('error', 'Project ini tidak dalam status menunggu approve!');
                return;
            }

            $project->update([
                // Project aktif 
                'status' => 'approved',
                // Progress task dimulai
                'approval_status' => 'progress',
                'approved_by' => $user->nip ?? null,
                'approved_at' => now(),
                'updated_by' => $user->nip ?? null,
            ]);

            // kirim notifikasi
            $this->sendNotificationProjectApprovedToCreator($project, $user);
            session()->flash('success', 'Project berhasil diapprove!');
            $this->resetPage();
        } catch (\Throwable $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // show modal reject project dengan menyimpan id project yang akan ditolak
    public function showRejectProject($id)
    {
        $this->project_id_for_reject = $id;
        $this->rejection_note = null;
        $this->confirmReject = true;
    }

    // asmen atau manajer menolak project dengan menyimpan catatan penolakan dan mengirim notifikasi ke pembuat project
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

            // rejected oleh asmen
            $isAsmenReject = $user->isAsmen() && ($project->asmen_id ? $user->nip === $project->asmen_id : true) && $project->status === 'ongoing';
            // rejected oleh manajer
            $isManajerReject = $user->isManajer() && ($project->manajer_id ? $user->nip === $project->manajer_id : true) && $project->status === 'verified';
            // jika tidak memiliki akses reject
            if (!$isAsmenReject && !$isManajerReject) {
                session()->flash('error', 'Anda tidak memiliki akses menolak project ini!');
                return;
            }

            // rejected project
            $payload = [
                'status' => 'rejected',
                'rejection_note' => $this->rejection_note,
                'rejected_by' => $user->nip ?? null,
                'rejected_at' => now(),
                'approved_by' => null,
                'approved_at' => null,
                'updated_by' => $user->nip ?? null,
            ];

            // reset verfier jika reject oleh asmen
            if ($isAsmenReject) {
                $payload['verified_by'] = null;
                $payload['verified_at'] = null;
            }

            $project->update($payload);

            // krim notifikasi rejected
            $this->sendNotificationProjectRejectedToCreator($project, $user, $this->rejection_note);
            session()->flash('success', 'Project berhasil ditolak!');
            $this->closeModal();
            $this->resetPage();
        } catch (\Throwable $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // cancelled project
    public function cancelProject()
    {
        $this->validate([
            'cancel_note' => 'required|min:10|max:500',
        ]);
        try {

            $project = ProjectModel::findOrFail(
                $this->project_id_for_cancel
            );

            $user = auth()->user();

            // cek login
            if (!$user) {

                session()->flash(
                    'error',
                    'Anda harus login terlebih dahulu!'
                );

                return;
            }
            // hanya creator project atau manejer yang boleh cancel project
            $canCancel =

                ($project->created_by === ($user->nip ?? null))

                ||

                ($project->asmen_id === ($user->nip ?? null))

                ||

                ($project->manajer_id === ($user->nip ?? null));

            if (!$canCancel) {

                session()->flash(
                    'error',
                    'Anda tidak memiliki akses membatalkan project ini!'
                );

                return;
            }
            // hanya project yang belum approved yang bisa dibatalkan
            if ($project->approval_status === 'completed') {

                session()->flash(
                    'error',
                    'Project yang sudah completed tidak dapat dibatalkan!'
                );

                return;
            }
            // cancelled project
            $project->update([
                // Cancel approval awal 
                'status' => 'cancelled',
                'rejection_note' => $this->cancel_note,
                'rejected_by' => auth()->user()->nip ?? null,
                'rejected_at' => now(),
            ]);
            $this->sendNotificationProjectCancelled(
                $project,
                $user,
                $this->cancel_note
            );
            session()->flash(
                'success',
                'Project berhasil dibatalkan!'
            );
        } catch (\Throwable $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
        $this->closeModal();
        $this->resetPage();
    }
    public function showCancelProject($id)
    {
        $this->project_id_for_cancel = $id;

        $this->cancel_note = null;

        $this->confirmCancel = true;
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

    public function verifyFinalProject($id)
    {
        $project = ProjectModel::findOrFail($id);

        $user = auth()->user();

        // hanya asmen
        if (
            !$user->isAsmen()
            ||
            $user->nip != $project->asmen_id
        ) {

            session()->flash(
                'error',
                'Anda tidak memiliki akses!'
            );

            return;
        }

        // progress harus 100%
        if ($project->progress_percentage < 100) {

            session()->flash(
                'error',
                'Progress task belum 100%'
            );

            return;
        }

        $project->update([
            'approval_status' => 'verified',
        ]);

        // notif ke manajer
        $this->sendNotificationFinalProjectToManajer(
            $project,
            $user
        );

        session()->flash(
            'success',
            'Final project berhasil diverifikasi!'
        );
    }
    public function completeProject($id)
    {
        $project = ProjectModel::findOrFail($id);

        $user = auth()->user();

        // hanya manajer
        if (
            !$user->isManajer()
            ||
            $user->nip != $project->manajer_id
        ) {

            session()->flash(
                'error',
                'Anda tidak memiliki akses!'
            );

            return;
        }

        // harus verified final
        if (
            $project->approval_status != 'verified'
        ) {

            session()->flash(
                'error',
                'Project belum diverifikasi final!'
            );

            return;
        }

        $project->update([
            'approval_status' => 'completed',
        ]);

        // notif creator
        $this->sendNotificationProjectCompleted(
            $project,
            $user
        );

        session()->flash(
            'success',
            'Project berhasil diselesaikan!'
        );
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

    protected function sendNotificationProjectVerifiedToCreator(ProjectModel $project, Pengguna $asmen): void
    {
        $creator = $project->creator;
        if (!$creator) {
            return;
        }

        $asmenName = $asmen->nama_lengkap ?? 'Asisten Manajer';
        $message = "Halo, project Anda sudah diverifikasi!\n\n" .
            "Kode: " . ($project->kode_project ?? '-') . "\n" .
            "Nama: {$project->project_name}\n" .
            "Diverifikasi oleh: {$asmenName}\n" .
            "Status: Menunggu approve Manajer\n\n" .
            "Silakan cek di sistem.";

        $phoneNumber = $creator->no_wa ?: ($creator->pegawai?->no_telp);
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

    protected function sendNotificationProjectCancelled(
        ProjectModel $project,
        Pengguna $cancelBy,
        string $note
    ): void {

        $creator = $project->creator;

        if (!$creator) {
            return;
        }

        $cancelName =
            $cancelBy->nama_lengkap
            ?? 'User';

        $message =
            "Halo, project Anda dibatalkan!\n\n" .

            "Kode: " .
            ($project->kode_project ?? '-') .
            "\n" .

            "Nama: {$project->project_name}\n" .

            "Dibatalkan oleh: {$cancelName}\n" .

            "Alasan: {$note}\n\n" .

            "Silakan cek sistem untuk detail lebih lanjut.";

        $phoneNumber =
            $creator->no_wa
            ?: ($creator->pegawai?->no_telp);

        if (!$phoneNumber) {
            return;
        }

        (new WahaService())->sendWhatsApp(
            $phoneNumber,
            $message
        );
    }
    protected function sendNotificationFinalProjectToManajer(
        ProjectModel $project,
        Pengguna $asmen
    ): void {

        $manajer = $project->manajer;

        if (!$manajer) {
            return;
        }

        $message =
            "Halo, seluruh task project sudah selesai dan menunggu final approve.\n\n" .

            "Kode: {$project->kode_project}\n" .

            "Nama: {$project->project_name}\n" .

            "Diverifikasi oleh: {$asmen->nama_lengkap}\n\n" .

            "Silakan cek sistem.";

        $phone =
            $manajer->no_wa
            ?: ($manajer->pegawai?->no_telp);

        if (!$phone) {
            return;
        }

        (new WahaService())
            ->sendWhatsApp($phone, $message);
    }
    protected function sendNotificationProjectCompleted(
        ProjectModel $project,
        Pengguna $manajer
    ): void {

        $creator = $project->creator;

        if (!$creator) {
            return;
        }

        $message =
            "Halo, project Anda telah selesai.\n\n" .

            "Kode: {$project->kode_project}\n" .

            "Nama: {$project->project_name}\n" .

            "Completed oleh: {$manajer->nama_lengkap}\n\n" .

            "Status project: COMPLETED";

        $phone =
            $creator->no_wa
            ?: ($creator->pegawai?->no_telp);

        if (!$phone) {
            return;
        }

        (new WahaService())
            ->sendWhatsApp($phone, $message);
    }
    /*
    |--------------------------------------------------------------------------
    | HELPER FORMAT & PARSE RUPIAH
    |--------------------------------------------------------------------------
    */

    private function formatRupiah($number)
    {
        if (!$number)
            return '';
        return 'Rp ' . number_format($number, 2, ',', '.');
    }

    private function parseRupiah($formatted)
    {
        if (!$formatted)
            return null;
        $number = preg_replace('/[^0-9,]/', '', $formatted);
        $number = str_replace(',', '.', $number);
        return (float) $number;
    }
}
