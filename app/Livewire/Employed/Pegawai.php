<?php

namespace App\Livewire\Employed;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

use App\Models\Pegawai as PegawaiModel;
use App\Models\Kantor as KantorModel;
use App\Models\Unit as UnitModel;
use App\Models\Jabatan as JabatanModel;


class Pegawai extends Component
{
    use WithPagination;

    /*
    |--------------------------------------------------------------------------
    | PRIMARY KEY
    |--------------------------------------------------------------------------
    */

    public $pegawai_id;

    /*
    |--------------------------------------------------------------------------
    | FIELD FORM
    |--------------------------------------------------------------------------
    */

    public $nip;

    public $nama;

    public $tempat_lahir;

    public $tanggal_lahir;

    public $jen_kel;

    public $alamat;

    public $agama;

    public $status_perkawinan;

    public $no_telp;

    public $al_email;

    public $aktif = 'Y';

    public $kantor_id;

    public $unit_id;

    public $jabatan_id;

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
    | PAGINATION
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
        $pegawais = PegawaiModel::query()

            ->when($this->search, function ($query) {

                $query->where('nip', 'like', '%' . $this->search . '%')

                    ->orWhere('nama', 'like', '%' . $this->search . '%')

                    ->orWhere('kantor_id', 'like', '%' . $this->search . '%')

                    ->orWhere('jabatan_unit_id', 'like', '%' . $this->search . '%')

                    ->orWhere('jabatan_id', 'like', '%' . $this->search . '%');

            })

            ->paginate($this->perPage);

        $kantors = KantorModel::orderBy('nama')->get();

        $units = UnitModel::orderBy('unit_name')->get();

        $jabatans = JabatanModel::orderBy('nama_jabatan')->get();

        return view('livewire.employed.pegawai', [

            'pegawais' => $pegawais,

            'kantors' => $kantors,

            'units' => $units,

            'jabatans' => $jabatans,

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

            'nip' => 'required|max:12|unique:pegawai,nip',

            'nama' => 'required|max:50',

            'tempat_lahir' => 'nullable|max:20',

            'tanggal_lahir' => 'nullable|date',

            'jen_kel' => 'nullable|max:1',

            'alamat' => 'nullable|max:500',

            'agama' => 'nullable|max:12',

            'status_perkawinan' => 'nullable|max:5',

            'no_telp' => 'nullable|max:20',

            'al_email' => 'nullable|email|max:55',

            'aktif' => 'nullable|max:50',

            'kantor_id' => 'required|exists:kantor,id',

            'unit_id' => 'required',

            'jabatan_id' => 'required',

        ]);

        PegawaiModel::create([

            'nip' => $this->nip,

            'nama' => $this->nama,

            'tempat_lahir' => $this->tempat_lahir,

            'tanggal_lahir' => $this->tanggal_lahir,

            'jen_kel' => $this->jen_kel,

            'alamat' => $this->alamat,

            'agama' => $this->agama,

            'status_perkawinan' => $this->status_perkawinan,

            'no_telp' => $this->no_telp,

            'al_email' => $this->al_email,

            'aktif' => $this->aktif,

            'kantor_id' => $this->kantor_id,

            'jabatan_unit_id' => $this->unit_id,

            'jabatan_id' => $this->jabatan_id,

            'created_by' => auth()->user()->nama_lengkap ?? 'system',

            'created_date' => now(),

        ]);

        session()->flash(
            'success',
            'Data pegawai berhasil ditambahkan'
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
        // $pegawai = PegawaiModel::findOrFail($id);
        // Gunakan where('nip') bukan findOrFail($id)
        $pegawai = PegawaiModel::where('nip', $id)->firstOrFail();

        // $this->pegawai_id = $pegawai->nip;
        // $this->nip = $pegawai->nip;
        // ... sisanya sama

        $this->pegawai_id = $pegawai->nip;

        $this->nip = $pegawai->nip;

        $this->nama = $pegawai->nama;

        $this->tempat_lahir = $pegawai->tempat_lahir;

        $this->tanggal_lahir = $pegawai->tanggal_lahir;

        $this->jen_kel = $pegawai->jen_kel;

        $this->alamat = $pegawai->alamat;

        $this->agama = $pegawai->agama;

        $this->status_perkawinan = $pegawai->status_perkawinan;

        $this->no_telp = $pegawai->no_telp;

        $this->al_email = $pegawai->al_email;

        $this->aktif = $pegawai->aktif;

        $this->kantor_id = $pegawai->kantor_id;

        $this->unit_id = $pegawai->jabatan_unit_id;

        $this->jabatan_id = $pegawai->jabatan_id;

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

            'nip' => 'required|max:12|unique:pegawai,nip,' . $this->pegawai_id . ',nip',

            'nama' => 'required|max:50',

            'tempat_lahir' => 'nullable|max:20',

            'tanggal_lahir' => 'nullable|date',

            'jen_kel' => 'nullable|max:1',

            'alamat' => 'nullable|max:500',

            'agama' => 'nullable|max:12',

            'status_perkawinan' => 'nullable|max:5',

            'no_telp' => 'nullable|max:20',

            'al_email' => 'nullable|email|max:55',

            'aktif' => 'nullable|max:50',

            'kantor_id' => 'required|exists:kantor,id',

            'unit_id' => 'required',

            'jabatan_id' => 'required',

        ]);

        session()->flash('debug', "update called pegawai_id={$this->pegawai_id} nip={$this->nip}");

        $pegawai = PegawaiModel::where('nip', $this->pegawai_id)->firstOrFail();

        $pegawai->update([

            'nama' => $this->nama,

            'tempat_lahir' => $this->tempat_lahir,

            'tanggal_lahir' => $this->tanggal_lahir,

            'jen_kel' => $this->jen_kel,

            'alamat' => $this->alamat,

            'agama' => $this->agama,

            'status_perkawinan' => $this->status_perkawinan,

            'no_telp' => $this->no_telp,

            'al_email' => $this->al_email,

            'aktif' => $this->aktif,

            'kantor_id' => $this->kantor_id,

            'jabatan_unit_id' => $this->unit_id,

            'jabatan_id' => $this->jabatan_id,

            'modified_by' => auth()->user()->nama_lengkap ?? 'system',

            'modified_date' => now(),

        ]);

        session()->flash(
            'success',
            'Data pegawai berhasil diupdate'
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

            PegawaiModel::where('nip', $id)->firstOrFail()->delete();

            session()->flash(
                'success',
                'Data pegawai berhasil dihapus'
            );

        } catch (\Exception $e) {

            session()->flash(
                'error',
                'Data pegawai tidak bisa dihapus karena masih digunakan!'
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

            'pegawai_id',

            'nip',

            'nama',

            'tempat_lahir',

            'tanggal_lahir',

            'jen_kel',

            'alamat',

            'agama',

            'status_perkawinan',

            'no_telp',

            'al_email',

            'aktif',

            'kantor_id',

            'unit_id',

            'jabatan_id',

        ]);

        $this->aktif = 'A';
    }
}
