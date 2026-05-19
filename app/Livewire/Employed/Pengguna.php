<?php

namespace App\Livewire\Employed;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

use App\Models\Pengguna as PenggunaModel;
use App\Models\Pegawai as PegawaiModel;
use App\Models\Kantor as KantorModel;
use App\Models\Role as RoleModel;

class Pengguna extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    /*
    |--------------------------------------------------------------------------
    | PRIMARY KEY
    |--------------------------------------------------------------------------
    */

    public $pengguna_id;

    /*
    |--------------------------------------------------------------------------
    | FIELD FORM
    |--------------------------------------------------------------------------
    */

    public $kantor_id;

    public $nip;

    public $nama_lengkap;

    public $nama_awal;

    public $nama_akhir;

    public $nama_pemakai;
    public $email;

    public $no_wa;
    public $password;

    public $role_id;
    public $last_login;
    public $aktif = '1';

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
    | AUTO FILL DARI PEGAWAI
    |--------------------------------------------------------------------------
    */

    public function updatedNip($value)
    {
        $pegawai = PegawaiModel::find($value);

        if ($pegawai) {

            $this->nama_lengkap = $pegawai->nama;

            $this->kantor_id = $pegawai->kantor_id;

            $nama = explode(' ', trim($pegawai->nama));

            $this->nama_awal = $nama[0] ?? '';

            $this->nama_akhir = end($nama) ?: '';

            $this->email = $pegawai->al_email;

        }
    }

    public function updatedNoWa()
    {
        // hapus selain angka
        $this->no_wa = preg_replace(
            '/[^0-9]/',
            '',
            $this->no_wa
        );

        // ubah 08 menjadi 62
        $this->no_wa = preg_replace(
            '/^0/',
            '62',
            $this->no_wa
        );
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
        $penggunas = PenggunaModel::query()

            ->when($this->search, function ($query) {

                $query->where('nip', 'like', '%' . $this->search . '%')

                    ->orWhere('nama_lengkap', 'like', '%' . $this->search . '%')

                    ->orWhere('nama_pemakai', 'like', '%' . $this->search . '%');

            })

            ->paginate($this->perPage);

        $pegawais = PegawaiModel::orderBy('nama')->get();

        $roles = RoleModel::orderBy('name')->get();

        $kantors = KantorModel::orderBy('nama')->get();

        return view('livewire.employed.pengguna', [

            'penggunas' => $penggunas,

            'pegawais' => $pegawais,

            'roles' => $roles,

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
        // Format no_wa sebelum validasi
        $this->no_wa = preg_replace('/[^0-9]/', '', $this->no_wa);
        $this->no_wa = preg_replace('/^0/', '62', $this->no_wa);
        $this->validate([

            'nip' => 'required|exists:pegawai,nip|unique:pengguna,nip',

            'kantor_id' => 'required|exists:kantor,id',

            'nama_lengkap' => 'required|max:100',

            'nama_awal' => 'nullable|max:50',

            'nama_akhir' => 'nullable|max:50',

            'nama_pemakai' => 'required|max:50|unique:pengguna,nama_pemakai',

            'password' => 'required|min:8',

            'role_id' => 'required|exists:roles,id',

            'aktif' => 'required',

            'email' => 'required|email|unique:pengguna,email',

            'no_wa' => ['nullable', 'regex:/^62[0-9]{8,15}$/'],

        ]);

        PenggunaModel::create([

            'kantor_id' => $this->kantor_id,

            'nip' => $this->nip,

            'nama_lengkap' => $this->nama_lengkap,

            'nama_awal' => $this->nama_awal,

            'nama_akhir' => $this->nama_akhir,

            'nama_pemakai' => $this->nama_pemakai,

            'email' => $this->email,

            'password' => Hash::make($this->password),

            'role_id' => $this->role_id,

            'aktif' => $this->aktif,

            'created_by' => auth()->user()->nama_lengkap ?? 'system',

            'created_date' => now(),

        ]);

        session()->flash(
            'success',
            'Data pengguna berhasil ditambahkan'
        );

        $this->resetForm();
        $this->search = '';
        $this->resetPage();
        $this->closeModal();
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $pengguna = PenggunaModel::findOrFail($id);

        $this->pengguna_id = $pengguna->nip;

        $this->kantor_id = $pengguna->kantor_id;

        $this->nip = $pengguna->nip;

        $this->nama_lengkap = $pengguna->nama_lengkap;

        $this->nama_awal = $pengguna->nama_awal;

        $this->nama_akhir = $pengguna->nama_akhir;

        $this->nama_pemakai = $pengguna->nama_pemakai;

        $this->email = $pengguna->email;

        $this->no_wa = $pengguna->no_wa;

        $this->role_id = $pengguna->role_id;

        $this->aktif = $pengguna->aktif;

        $this->confirmEdit = true;
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update()
    {
        // Format no_wa sebelum validasi
        $this->no_wa = preg_replace('/[^0-9]/', '', $this->no_wa);
        $this->no_wa = preg_replace('/^0/', '62', $this->no_wa);
        $this->validate([

            'kantor_id' => 'required|exists:kantor,id',

            'nama_lengkap' => 'required|max:100',

            'nama_awal' => 'nullable|max:50',

            'nama_akhir' => 'nullable|max:50',

            'nama_pemakai' => 'required|max:50|unique:pengguna,nama_pemakai,' . $this->pengguna_id . ',nip',

            'role_id' => 'required|exists:roles,id',

            'aktif' => 'required',

            'email' => 'required|email|unique:pengguna,email,' . $this->pengguna_id . ',nip',

            'no_wa' => ['required', 'regex:/^62[0-9]{8,15}$/'],

        ]);

        $pengguna = PenggunaModel::findOrFail($this->pengguna_id);

        $data = [

            'kantor_id' => $this->kantor_id,

            'nama_lengkap' => $this->nama_lengkap,

            'nama_awal' => $this->nama_awal,

            'nama_akhir' => $this->nama_akhir,

            'nama_pemakai' => $this->nama_pemakai,

            'email' => $this->email,

            'no_wa' => $this->no_wa,

            'role_id' => $this->role_id,

            'aktif' => $this->aktif,

            'modified_by' => auth()->user()->nama_lengkap ?? 'system',

            'modified_date' => now(),

        ];

        // update password hanya jika diisi
        if ($this->password) {

            $data['password'] = Hash::make($this->password);

        }

        $pengguna->update($data);

        session()->flash(
            'success',
            'Data pengguna berhasil diupdate'
        );

        $this->resetForm();
        $this->search = '';
        $this->resetPage();
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

            PenggunaModel::findOrFail($id)->delete();

            session()->flash(
                'success',
                'Data pengguna berhasil dihapus'
            );

        } catch (\Exception $e) {

            session()->flash(
                'error',
                'Data pengguna tidak bisa dihapus karena masih digunakan!'
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

            'pengguna_id',

            'kantor_id',

            'nip',

            'nama_lengkap',

            'nama_awal',

            'nama_akhir',

            'nama_pemakai',

            'email',

            'no_wa',

            'password',

            'role_id',

            'aktif',

        ]);

        $this->aktif = '1';
    }
}
