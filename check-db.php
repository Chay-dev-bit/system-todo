<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== ROLES ===\n";
$roles = DB::table('roles')->get();
foreach ($roles as $r) {
    echo "ID: {$r->id}, Name: {$r->name}\n";
}

echo "\n=== PENGGUNA DUMMY ===\n";
$penggunas = DB::table('pengguna')->whereIn('nip', ['111111', '222222', '333333'])->get();
foreach ($penggunas as $p) {
    $role = DB::table('roles')->find($p->role_id);
    echo "NIP: {$p->nip}, Nama: {$p->nama_lengkap}, Role ID: {$p->role_id}, Role Name: " . ($role ? $role->name : '-') . "\n";
}
