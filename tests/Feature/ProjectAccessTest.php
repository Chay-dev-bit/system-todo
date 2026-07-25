<?php

use App\Livewire\Todo\Project;
use App\Models\Pengguna;
use App\Models\Project as ProjectModel;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('prevents admin from creating a project', function () {
    $role = Role::create([
        'name' => 'Admin',
        'description' => 'Administrator',
    ]);

    $admin = Pengguna::create([
        'nip' => 'ADMIN001',
        'nama_lengkap' => 'Admin Test',
        'nama_awal' => 'Admin',
        'nama_akhir' => 'Test',
        'nama_pemakai' => 'admin',
        'email' => 'admin@example.com',
        'no_wa' => '081234567890',
        'password' => bcrypt('password'),
        'role_id' => $role->id,
        'aktif' => true,
    ]);

    $this->actingAs($admin);

    Livewire::test(Project::class)
        ->set('kode_project', 'PRJ001')
        ->set('project_name', 'Project Test')
        ->set('pic_id', $admin->nip)
        ->call('save')
        ->assertSessionHas('error', 'Role admin tidak dapat membuat project.');

    expect(ProjectModel::count())->toBe(0);
});
