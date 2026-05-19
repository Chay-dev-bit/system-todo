<?php

namespace App\Livewire\Employed;

use Livewire\Component;

use App\Models\Jabatan as JabatanModel;
use App\Models\Kantor as KantorModel;
use App\Models\Unit as UnitModel;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Jabatan extends Component
{
    use WithPagination;

    /*
    |--------------------------------------------------------------------------
    | PRIMARY KEY
    |--------------------------------------------------------------------------
    */

    public $jabatan_id;

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
    public $unit_id;
    public $kode_jabatan;
    public $nama_jabatan;
    public $tingkat;
    public $kls_jab;
    public $atasan_bid;
    public $atasan_jab;

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
        $jabatans = JabatanModel::query()

            ->when($this->search, function ($query) {

                $query->where('id', 'like', '%' . $this->search . '%')

                    ->orWhere('nama_jabatan', 'like', '%' . $this->search . '%')

                    ->orWhere('kantor_id', 'like', '%' . $this->search . '%')

                    ->orWhere('unit_id', 'like', '%' . $this->search . '%');

            })

            ->paginate($this->perPage);

        // dropdown kantor
        $kantors = KantorModel::orderBy('nama')->get();

        // dropdown unit
        $units = UnitModel::orderBy('unit_name')->get();

        // dropdown atasan jabatan
        $atasanJabatan = JabatanModel::orderBy('nama_jabatan')->get();

        return view('livewire.employed.jabatan', [

            'jabatans' => $jabatans,

            'kantors' => $kantors,

            'units' => $units,

            'atasanJabatan' => $atasanJabatan,

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

            'kantor_id' => 'required|exists:kantor,id',

            'unit_id' => 'required|exists:units,id',

            'kode_jabatan' => 'required|max:6|unique:jabatan,id',

            'nama_jabatan' => 'required|max:100',

            'tingkat' => 'nullable|max:2',

            'kls_jab' => 'nullable|max:2',

            'atasan_bid' => 'nullable|max:6',

            'atasan_jab' => 'nullable|max:6',

            'dok_bidang' => 'nullable',
            'dok_bidang_sub' => 'nullable',
            'dok_jenis' => 'nullable',
            'dok_tahun' => 'nullable',
            'dok_no' => 'nullable',

        ]);
        $unit = UnitModel::findOrFail($this->unit_id);
        JabatanModel::create([

            'kantor_id' => $this->kantor_id,

            'unit_id' => $this->unit_id,

            'id' => $this->kode_jabatan,

            'nama_jabatan' => $this->nama_jabatan,

            'tingkat' => $this->tingkat,

            'kls_jab' => $this->kls_jab,

            'atasan_bid' => $this->atasan_bid,

            'atasan_jab' => $this->atasan_jab,

            // WAJIB MATCH DENGAN UNITS
            'dok_bidang' => $unit->dok_bidang,

            'dok_bidang_sub' => $unit->dok_bidang_sub,

            'dok_jenis' => $unit->dok_jenis,

            'dok_tahun' => $unit->dok_tahun,

            'dok_no' => $unit->dok_no,

            'created_by' => auth()->user()->nama_lengkap ?? 'system',

            'created_date' => now(),

        ]);

        session()->flash(
            'success',
            'Data jabatan berhasil ditambahkan'
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
        $jabatan = JabatanModel::findOrFail($id);

        $this->jabatan_id = $jabatan->id;

        $this->kantor_id = $jabatan->kantor_id;

        $this->dok_bidang = $jabatan->dok_bidang;
        $this->dok_bidang_sub = $jabatan->dok_bidang_sub;
        $this->dok_jenis = $jabatan->dok_jenis;
        $this->dok_tahun = $jabatan->dok_tahun;
        $this->dok_no = $jabatan->dok_no;

        $this->unit_id = $jabatan->unit_id;

        $this->kode_jabatan = $jabatan->id;

        $this->nama_jabatan = $jabatan->nama_jabatan;

        $this->tingkat = $jabatan->tingkat;

        $this->kls_jab = $jabatan->kls_jab;

        $this->atasan_bid = $jabatan->atasan_bid;

        $this->atasan_jab = $jabatan->atasan_jab;

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

            'kantor_id' => 'required|exists:kantor,id',

            'unit_id' => 'required|exists:units,id',

            'kode_jabatan' => 'required|max:6|unique:jabatan,id,' . $this->jabatan_id,

            'dok_bidang' => 'nullable',
            'dok_bidang_sub' => 'nullable',
            'dok_jenis' => 'nullable',
            'dok_tahun' => 'nullable',
            'dok_no' => 'nullable',

            'nama_jabatan' => 'required|max:100',

            'tingkat' => 'nullable|max:2',

            'kls_jab' => 'nullable|max:2',

            'atasan_bid' => 'nullable|max:6',

            'atasan_jab' => 'nullable|max:6',

        ]);

        $jabatan = JabatanModel::findOrFail($this->jabatan_id);
        $unit = UnitModel::findOrFail($this->unit_id);

        $jabatan->update([

            'kantor_id' => $this->kantor_id,

            'dok_bidang' => $unit->dok_bidang,

            'dok_bidang_sub' => $unit->dok_bidang_sub,

            'dok_jenis' => $unit->dok_jenis,

            'dok_tahun' => $unit->dok_tahun,

            'dok_no' => $unit->dok_no,

            'unit_id' => $this->unit_id,

            'nama_jabatan' => $this->nama_jabatan,

            'tingkat' => $this->tingkat,

            'kls_jab' => $this->kls_jab,

            'atasan_bid' => $this->atasan_bid,

            'atasan_jab' => $this->atasan_jab,

            'modified_by' => auth()->user()->nama_lengkap ?? 'system',

            'modified_date' => now(),

        ]);

        session()->flash(
            'success',
            'Data jabatan berhasil diupdate'
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

            JabatanModel::findOrFail($id)->delete();

            session()->flash(
                'success',
                'Data jabatan berhasil dihapus'
            );

        } catch (\Exception $e) {

            session()->flash(
                'error',
                'Data jabatan tidak bisa dihapus karena masih digunakan!'
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

            'jabatan_id',

            'kantor_id',

            'unit_id',

            'kode_jabatan',

            'nama_jabatan',

            'tingkat',

            'kls_jab',

            'atasan_bid',

            'atasan_jab',

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