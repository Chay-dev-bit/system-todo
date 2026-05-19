<?php

namespace App\Livewire\Employed;

use Livewire\Component;
use App\Models\Unit as UnitModel;
use App\Models\Kantor as KantorModel;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Unit extends Component
{
    use WithPagination;

    /*
    |--------------------------------------------------------------------------
    | PRIMARY KEY
    |--------------------------------------------------------------------------
    */

    public $unit_id;

    /*
    |--------------------------------------------------------------------------
    | FIELD FORM
    |--------------------------------------------------------------------------
    */

    public $kantor_id;
    public $dok_bidang = '-';
    public $dok_bidang_sub = '-';
    public $dok_jenis = '-';
    public $dok_tahun = '-';
    public $dok_no = '-';
    public $kode_unit;
    public $unit_name;
    public $tingkat;

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
        $units = UnitModel::query()

            ->when($this->search, function ($query) {

                $query->where('id', 'like', '%' . $this->search . '%')

                    ->orWhere('unit_name', 'like', '%' . $this->search . '%')

                    ->orWhere('kantor_id', 'like', '%' . $this->search . '%')

                    ->orWhere('tingkat', 'like', '%' . $this->search . '%');

            })

            ->paginate($this->perPage);

        // dropdown kantor
        $kantors = KantorModel::orderBy('nama')->get();

        return view('livewire.employed.unit', [

            'units' => $units,

            'kantors' => $kantors,

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

            'kantor_id' => 'required|max:5|exists:kantor,id',

            'kode_unit' => 'required|max:6|unique:units,id',

            'unit_name' => 'required|max:100',
            'dok_bidang' => 'nullable',
            'dok_bidang_sub' => 'nullable',
            'dok_jenis' => 'nullable',
            'dok_tahun' => 'nullable',
            'dok_no' => 'nullable',

            'tingkat' => 'nullable|max:2',

        ]);

        UnitModel::create([

            'kantor_id' => $this->kantor_id,

            'id' => $this->kode_unit,

            'unit_name' => $this->unit_name,
            'dok_bidang' => $this->dok_bidang,
            'dok_bidang_sub' => $this->dok_bidang_sub,
            'dok_jenis' => $this->dok_jenis,
            'dok_tahun' => $this->dok_tahun,
            'dok_no' => $this->dok_no,

            'tingkat' => $this->tingkat,

            'created_by' => auth()->user()->nama_lengkap ?? 'system',

            'created_date' => now(),

        ]);

        session()->flash(
            'success',
            'Data unit berhasil ditambahkan'
        );

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
        $unit = UnitModel::findOrFail($id);

        $this->unit_id = $unit->id;

        $this->kantor_id = $unit->kantor_id;

        $this->kode_unit = $unit->id;

        $this->unit_name = $unit->unit_name;

        $this->tingkat = $unit->tingkat;

        $this->dok_bidang = $unit->dok_bidang;
        $this->dok_bidang_sub = $unit->dok_bidang_sub;
        $this->dok_jenis = $unit->dok_jenis;
        $this->dok_tahun = $unit->dok_tahun;
        $this->dok_no = $unit->dok_no;

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

            'kantor_id' => 'required|max:5|exists:kantor,id',

            'kode_unit' => 'required|max:6|unique:units,id,' . $this->unit_id,

            'unit_name' => 'required|max:100',

            'tingkat' => 'nullable|max:2',

            'dok_bidang' => 'nullable',
            'dok_bidang_sub' => 'nullable',
            'dok_jenis' => 'nullable',
            'dok_tahun' => 'nullable',
            'dok_no' => 'nullable',

        ]);

        $unit = UnitModel::findOrFail($this->unit_id);

        $unit->update([

            'kantor_id' => $this->kantor_id,

            'unit_name' => $this->unit_name,

            'tingkat' => $this->tingkat,

            'dok_bidang' => $this->dok_bidang,
            'dok_bidang_sub' => $this->dok_bidang_sub,
            'dok_jenis' => $this->dok_jenis,
            'dok_tahun' => $this->dok_tahun,
            'dok_no' => $this->dok_no,

            'modified_by' => auth()->user()->nama_lengkap ?? 'system',

            'modified_date' => now(),

        ]);

        session()->flash(
            'success',
            'Data unit berhasil diupdate'
        );

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
        $this->dispatch(
            'show-delete-confirmation',
            id: $id
        );
    }

    #[On('deleteConfirmed')]
    public function deleteConfirmed($id)
    {
        $this->delete($id);
    }

    public function delete($id)
    {
        try {

            UnitModel::findOrFail($id)->delete();

            session()->flash(
                'success',
                'Data unit berhasil dihapus'
            );

        } catch (\Exception $e) {

            session()->flash(
                'error',
                'Data unit tidak bisa dihapus karena masih digunakan!'
            );

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

            'unit_id',

            'kantor_id',

            'kode_unit',

            'unit_name',

            'tingkat',

            'dok_bidang',

            'dok_bidang_sub',

            'dok_jenis',

            'dok_tahun',

            'dok_no',
        ]);


        $this->dok_bidang = '-';
        $this->dok_bidang_sub = '-';
        $this->dok_jenis = '-';
        $this->dok_tahun = '-';
        $this->dok_no = '-';
    }
}
