<?php

namespace App\Livewire\Todo;

use Livewire\Component;
use App\Models\Project as ProjectModel;
use App\Models\Pengguna;
use Livewire\WithPagination;
use Livewire\Attributes\On;

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
    public $status;
    public $verified_by;
    public $approved_by;

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
    }

    /*
    |--------------------------------------------------------------------------
    | RENDER
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        $projects = ProjectModel::query()
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
            'status' => 'required|in:pending,ongoing,completed,cancelled',
            'verified_by' => 'nullable|exists:pengguna,nip',
            'approved_by' => 'nullable|exists:pengguna,nip',
        ]);

        $this->biaya = $this->parseRupiah($this->biaya_formatted);

        ProjectModel::create([
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
            'status' => $this->status ?? 'pending',
            'verified_by' => $this->verified_by,
            'approved_by' => $this->approved_by,
            'created_by' => auth()->user()->nip ?? null,
        ]);

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
            'status' => $this->status,
            'verified_by' => $this->verified_by,
            'approved_by' => $this->approved_by,
            'updated_by' => auth()->user()->nip ?? null,
        ]);

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
            'verified_by',
            'approved_by',
        ]);
        
        $this->status = 'pending';
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
