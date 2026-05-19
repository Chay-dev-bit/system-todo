<?php

namespace App\Livewire\Employed;

use Livewire\Component;
use App\Models\Kantor as KantorModel;
use Livewire\WithPagination;
use Livewire\Attributes\On;
class Kantor extends Component
{
    use WithPagination;
    public $kantor_id;

    // ini id 
    public $kode_kantor;
    public $nama;
    public $alamat;
    public $kota;
    public $telp;
    public $email;
    public $kantor_induk;
    public $perPage = 5;
    public $search = '';
    public $confirmInput = false;
    public $confirmEdit = false;
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }
    // untuk menampilkan modal input
    public function showDataInput()
    {
        $this->resetForm();
        $this->confirmInput = true;
    }

    // untuk menutup modal input
    public function closeModal()
    {
        $this->confirmInput = false;
        $this->confirmEdit = false;
    }
    public function render()
    {
        // fungsi search
        $kantors = KantorModel::query()
            ->when($this->search, function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                    ->orWhere('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('kota', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%');
            })
            ->paginate($this->perPage);

        return view('livewire.employed.kantor', [
            'kantors' => $kantors
        ])->layout('layouts.app');
    }
    public function save()
    {
        $this->validate([
            'kode_kantor' => 'required|max:5|unique:kantor,id',
            'nama' => 'required|max:50',
            'alamat' => 'required|max:50',
            'kota' => 'required|max:30',
            'telp' => 'required|max:15',
            'email' => 'required|email|max:50',
            'kantor_induk' => 'required|max:50',
        ]);

        KantorModel::create([
            'id' => $this->kode_kantor,
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'kota' => $this->kota,
            'telp' => $this->telp,
            'email' => $this->email,
            'created_date' => now(),
            'created_by' => auth()->user()->nama_lengkap ?? 'system',
            'kantor_induk' => $this->kantor_induk,
        ]);

        session()->flash(
            'success',
            'Data kantor berhasil ditambahkan'
        );

        $this->resetForm();
        $this->closeModal();
    }

    public function edit($id)
    {
        $kantor = KantorModel::findOrFail($id);

        $this->kantor_id = $kantor->id;

        $this->kode_kantor = $kantor->id;
        $this->nama = $kantor->nama;
        $this->alamat = $kantor->alamat;
        $this->kota = $kantor->kota;
        $this->telp = $kantor->telp;
        $this->email = $kantor->email;
        $this->kantor_induk = $kantor->kantor_induk;

        $this->confirmEdit = true;
    }

    public function update()
    {
        $this->validate([
            'kode_kantor' => 'required|max:5|unique:kantor,id,' . $this->kantor_id,
            'nama' => 'required|max:50',
            'alamat' => 'required|max:50',
            'kota' => 'required|max:30',
            'telp' => 'required|max:15',
            'email' => 'required|email|max:50',
            'kantor_induk' => 'required|max:50',
        ]);

        $kantor = KantorModel::findOrFail($this->kantor_id);

        $kantor->update([
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'kota' => $this->kota,
            'telp' => $this->telp,
            'email' => $this->email,
            'modified_date' => now(),
            'modified_by' => auth()->user()->nama_lengkap ?? 'system',
            'kantor_induk' => $this->kantor_induk,
        ]);

        session()->flash(
            'success',
            'Data kantor berhasil diupdate'
        );

        $this->resetForm();
        $this->closeModal();
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
        $kantor = KantorModel::findOrFail($id);
        $kantor->delete();
        session()->flash(
            'success',
            'Data kantor berhasil dihapus'
        );

        $this->resetPage();
    }

    public function resetForm()
    {
        $this->reset([
            'kantor_id',
            'kode_kantor',
            'nama',
            'alamat',
            'kota',
            'telp',
            'email',
            'kantor_induk'
        ]);
    }
}
