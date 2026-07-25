<?php

use App\Livewire\Todo\Task;
use App\Models\Pengguna;
use App\Models\Project;
use App\Models\Task as TaskModel;
use App\Services\WahaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Mockery;

beforeEach(function () {
    Artisan::call('migrate:fresh', ['--seed' => true]);
});

it('sends whatsapp notification to asmen when task proof is uploaded', function () {
    $asmen = Pengguna::create([
        'nip' => 'ASMEN001',
        'nama_lengkap' => 'Asmen Tester',
        'email' => 'asmen@example.com',
        'no_wa' => '081234567890',
        'password' => bcrypt('password'),
    ]);

    $staff = Pengguna::create([
        'nip' => 'STAFF001',
        'nama_lengkap' => 'Staff Tester',
        'email' => 'staff@example.com',
        'no_wa' => '081234567891',
        'password' => bcrypt('password'),
    ]);

    $pic = Pengguna::create([
        'nip' => 'PIC001',
        'nama_lengkap' => 'PIC Tester',
        'email' => 'pic@example.com',
        'no_wa' => '081234567892',
        'password' => bcrypt('password'),
    ]);

    $project = Project::create([
        'project_name' => 'Project Test',
        'pic_id' => $pic->nip,
        'asmen_id' => $asmen->nip,
        'status' => 'approved',
        'created_by' => $pic->nip,
    ]);

    $task = TaskModel::create([
        'project_id' => $project->id,
        'title' => 'Task Test',
        'assigned_to' => $staff->nip,
        'status' => 'in_progress',
        'created_by' => $pic->nip,
    ]);

    $serviceMock = Mockery::mock(WahaService::class);
    $serviceMock->shouldReceive('sendWhatsApp')
        ->once()
        ->withArgs(function ($phoneNumber, $message) use ($asmen, $task) {
            expect($phoneNumber)->toBe($asmen->no_wa);
            expect($message)->toContain('menunggu verifikasi');
            expect($message)->toContain($task->title);
            return true;
        });

    $this->app->instance(WahaService::class, $serviceMock);

    $this->actingAs($staff);

    $component = Livewire::test(Task::class, ['projectId' => $project->id]);
    $component->set('task_id_for_upload', $task->id);
    $component->set('attachment', UploadedFile::fake()->create('proof.pdf', 100, 'application/pdf'));
    $component->call('uploadFile');
});
