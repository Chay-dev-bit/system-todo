<?php

namespace App\Livewire\Employed;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

use App\Models\Role as RoleModel;
class Role extends Component
{
    use WithPagination;

    /*
    |--------------------------------------------------------------------------
    | PRIMARY KEY
    |--------------------------------------------------------------------------
    */

    public $role_id;

    /*
    |--------------------------------------------------------------------------
    | FIELD FORM
    |--------------------------------------------------------------------------
    */

    public $name;

    public $description;

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
        if (!auth()->user()->isAdmin()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk menambah data role');
            return;
        }
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
        $roles = RoleModel::query()

            ->when($this->search, function ($query) {

                $query->where('name', 'like', '%' . $this->search . '%')

                    ->orWhere('description', 'like', '%' . $this->search . '%');

            })

            ->orderBy('id', 'desc')

            ->paginate($this->perPage);

        $user = auth()->user();
        if ($user && !$user->relationLoaded('role')) {
            $user->load('role');
        }

        return view('livewire.employed.role', [

            'roles' => $roles,
            'currentUser' => $user

        ])->layout('layouts.app');
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE
    |--------------------------------------------------------------------------
    */

    public function save()
    {
        if (!auth()->user()->isAdmin()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk menambah data role');
            return;
        }
        $this->validate([

            'name' => 'required|max:100|unique:roles,name',

            'description' => 'nullable|max:255',

        ]);

        RoleModel::create([

            'name' => $this->name,

            'description' => $this->description,

        ]);

        session()->flash(
            'success',
            'Data role berhasil ditambahkan'
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
        if (!auth()->user()->isAdmin()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk mengubah data role');
            return;
        }
        $role = RoleModel::findOrFail($id);

        $this->role_id = $role->id;

        $this->name = $role->name;

        $this->description = $role->description;

        $this->confirmEdit = true;
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update()
    {
        if (!auth()->user()->isAdmin()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk mengubah data role');
            return;
        }
        $this->validate([

            'name' => 'required|max:100|unique:roles,name,' . $this->role_id,

            'description' => 'nullable|max:255',

        ]);

        $role = RoleModel::findOrFail($this->role_id);

        $role->update([

            'name' => $this->name,

            'description' => $this->description,

        ]);

        session()->flash(
            'success',
            'Data role berhasil diupdate'
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
        if (!auth()->user()->isAdmin()) {
            session()->flash('error', 'Anda tidak memiliki akses untuk menghapus data role');
            return;
        }
        try {

            RoleModel::findOrFail($id)->delete();

            session()->flash(
                'success',
                'Data role berhasil dihapus'
            );

        } catch (\Exception $e) {

            session()->flash(
                'error',
                'Data role tidak bisa dihapus karena masih digunakan!'
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

            'role_id',

            'name',

            'description',

        ]);
    }
}
