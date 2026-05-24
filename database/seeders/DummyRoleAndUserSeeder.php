<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DummyRoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // Insert kantor dummy if not exists
            $kantor = DB::table('kantor')->first();
            if (!$kantor) {
                $kantorId = DB::table('kantor')->insertGetId([
                    'nama' => 'Kantor Pusat',
                    'alamat' => 'Jl. Contoh No. 123',
                    'telepon' => '021-1234567',
                ]);
                $kantor = DB::table('kantor')->find($kantorId);
            }

            // Insert roles if not exists
            $roles = [
                ['name' => 'Staff', 'description' => 'Staff biasa'],
                ['name' => 'Asisten Manajer', 'description' => 'Asisten Manajer yang bisa verifikasi task'],
                ['name' => 'Manajer', 'description' => 'Manajer yang bisa approve task'],
            ];

            foreach ($roles as $role) {
                DB::table('roles')->updateOrInsert(
                    ['name' => $role['name']],
                    $role
                );
            }

            // Get role IDs
            $roleStaff = DB::table('roles')->where('name', 'Staff')->first();
            $roleAsmen = DB::table('roles')->where('name', 'Asisten Manajer')->first();
            $roleManajer = DB::table('roles')->where('name', 'Manajer')->first();

            // Insert dummy users
            $users = [
                [
                    'kantor_id' => $kantor->id,
                    'nip' => '111111',
                    'nama_lengkap' => 'Staff Test',
                    'nama_awal' => 'Staff',
                    'nama_akhir' => 'Test',
                    'nama_pemakai' => 'staff',
                    'email' => 'staff@test.com',
                    'no_wa' => '081234567890',
                    'password' => Hash::make('password123'),
                    'role_id' => $roleStaff->id,
                    'aktif' => 1,
                    'created_by' => '111111',
                    'created_date' => now(),
                ],
                [
                    'kantor_id' => $kantor->id,
                    'nip' => '222222',
                    'nama_lengkap' => 'Asisten Manajer Test',
                    'nama_awal' => 'Asisten',
                    'nama_akhir' => 'Manajer',
                    'nama_pemakai' => 'asmen',
                    'email' => 'asmen@test.com',
                    'no_wa' => '081234567891',
                    'password' => Hash::make('password123'),
                    'role_id' => $roleAsmen->id,
                    'aktif' => 1,
                    'created_by' => '222222',
                    'created_date' => now(),
                ],
                [
                    'kantor_id' => $kantor->id,
                    'nip' => '333333',
                    'nama_lengkap' => 'Manajer Test',
                    'nama_awal' => 'Manajer',
                    'nama_akhir' => 'Test',
                    'nama_pemakai' => 'manajer',
                    'email' => 'manajer@test.com',
                    'no_wa' => '081234567892',
                    'password' => Hash::make('password123'),
                    'role_id' => $roleManajer->id,
                    'aktif' => 1,
                    'created_by' => '333333',
                    'created_date' => now(),
                ],
            ];

            foreach ($users as $user) {
                DB::table('pengguna')->updateOrInsert(
                    ['nip' => $user['nip']],
                    $user
                );
            }

            $this->command->info('✅ Dummy data berhasil dibuat!');
            $this->command->info('');
            $this->command->info('📋 Dummy Akun:');
            $this->command->info('');
            $this->command->info('👤 Staff:');
            $this->command->info('   - NIP: 111111');
            $this->command->info('   - Username: staff');
            $this->command->info('   - Password: password123');
            $this->command->info('');
            $this->command->info('👤 Asisten Manajer:');
            $this->command->info('   - NIP: 222222');
            $this->command->info('   - Username: asmen');
            $this->command->info('   - Password: password123');
            $this->command->info('');
            $this->command->info('👤 Manajer:');
            $this->command->info('   - NIP: 333333');
            $this->command->info('   - Username: manajer');
            $this->command->info('   - Password: password123');
        });
    }
}
