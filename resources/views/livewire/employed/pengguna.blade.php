<div>
    <div class="flex items-center gap-2">
        <x-nav-link :href="route('dashboard')">
            Dashboard
        </x-nav-link>
        <x-nav-link :href="route('kantor')" :active="request()->routeIs('kantor')">
            Kantor
        </x-nav-link>
        <x-nav-link :href="route('unit')" :active="request()->routeIs('unit')">
            Unit
        </x-nav-link>
        <x-nav-link :href="route('jabatan')" :active="request()->routeIs('jabatan')">
            Jabatan
        </x-nav-link>
        <x-nav-link :href="route('pegawai')" :active="request()->routeIs('pegawai')">
            Pegawai
        </x-nav-link>
        <x-nav-link :href="route('role')" :active="request()->routeIs('role')">
            Level User
        </x-nav-link>
        <x-nav-link :href="route('pengguna')" :active="request()->routeIs('pengguna')">
            Pengguna
        </x-nav-link>
          <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <x-nav-link href="#" id="logout">
                Logout
            </x-nav-link>
        </form>
    </div>

    <div class="p-6">

        {{-- HEADER --}}
        <div class="items-center mb-6">

            <div class="flex justify-between">

                {{-- BUTTON TAMBAH --}}
                <button type="button" wire:click="showDataInput" class="bg-[#0070C0] hover:bg-blue-800
                       text-white px-4 py-2 mb-4 rounded
                       w-52 transition delay-150 duration-300
                       ease-in-out hover:-translate-y-1 hover:scale-110">

                    Tambah Data

                </button>

                {{-- SEARCH --}}
                <input type="text" wire:model.debounce.500ms="search" placeholder="Search" class="w-72 rounded-lg border border-slate-300
                       px-4 py-2 shadow-sm
                       focus:ring-2 focus:ring-blue-500">

            </div>

        </div>



        <!-- ================= MODAL INPUT PENGGUNA ================= -->
        <x-modal wire:model="confirmInput">

            <x-slot name="title">

                <span class="text-lg font-semibold text-black">
                    Tambah Data Pengguna
                </span>

            </x-slot>

            <x-slot name="content">

                <div class="grid grid-cols-2 gap-4">

                    {{-- NIP --}}
                    <div>

                        <x-input-label value="NIP" />

                        <select wire:model.live="nip" class="w-full rounded-lg border border-slate-300
                               px-4 py-2 shadow-sm
                               focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                -- Pilih Pegawai --
                            </option>

                            @foreach($pegawais as $pegawai)

                                <option value="{{ $pegawai->nip }}">

                                    {{ $pegawai->nip }} - {{ $pegawai->nama }}

                                </option>

                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('nip')" />

                    </div>

                    {{-- KANTOR --}}
                    <div>

                        <x-input-label value="KANTOR ID" />

                        <x-input type="text" wire:model.defer="kantor_id" readonly />

                    </div>

                    {{-- NAMA LENGKAP --}}
                    <div>

                        <x-input-label value="NAMA LENGKAP" />

                        <x-input type="text" wire:model.defer="nama_lengkap" readonly />

                    </div>

                    {{-- NAMA AWAL --}}
                    <div>

                        <x-input-label value="NAMA AWAL" />

                        <x-input type="text" wire:model.defer="nama_awal" />

                    </div>

                    {{-- NAMA AKHIR --}}
                    <div>

                        <x-input-label value="NAMA AKHIR" />

                        <x-input type="text" wire:model.defer="nama_akhir" />

                    </div>

                    {{-- USERNAME --}}
                    <div>

                        <x-input-label value="NAMA PEMAKAI" />

                        <x-input type="text" wire:model.defer="nama_pemakai" placeholder="Masukkan Username" />

                        <x-input-error :messages="$errors->get('nama_pemakai')" />

                    </div>

                    <!-- email -->
                     <div>

                        <x-input-label
                            for="email"
                            value="EMAIL"
                        />

                        <x-input
                            type="email"
                            wire:model.defer="email"
                        />

                        <x-input-error
                            :messages="$errors->get('email')"
                        />

                    </div>

                    <!-- no wa -->
                     <div>
                            <x-input-label
                                for="no_wa"
                                value="NO WHATSAPP"
                            />

                            <x-input
                                wire:model.live="no_wa"
                                id="no_wa"
                                type="text"
                                name="no_wa"
                                class="block mt-1 w-full"
                                placeholder="628123456789"
                                maxlength="16"
                            />

                            <x-input-error
                                :messages="$errors->get('no_wa')"
                                class="mt-2"
                            />

                        </div>
                        

                    {{-- PASSWORD --}}
                    <div>

                        <x-input-label value="PASSWORD" />

                        <x-input type="password" wire:model.defer="password" placeholder="Masukkan Password" />

                        <x-input-error :messages="$errors->get('password')" />

                    </div>

                    {{-- ROLE --}}
                    <div>

                        <x-input-label value="ROLE" />

                        <select wire:model.defer="role_id" class="w-full rounded-lg border border-slate-300
                               px-4 py-2 shadow-sm
                               focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                -- Pilih Role --
                            </option>

                            @foreach($roles as $role)

                                <option value="{{ $role->id }}">

                                    {{ $role->name }}

                                </option>

                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('role_id')" />

                    </div>

                    {{-- STATUS --}}
                    <div>

                        <x-input-label value="AKTIF" />

                        <select wire:model.defer="aktif" class="w-full rounded-lg border border-slate-300
                               px-4 py-2 shadow-sm">

                            <option value="1">
                                Aktif
                            </option>

                            <option value="0">
                                Nonaktif
                            </option>

                        </select>

                    </div>

                </div>

            </x-slot>

            <x-slot name="footer">

                <x-secondary-button type="button" wire:click.prevent="closeModal">

                    Batal

                </x-secondary-button>

                <x-primary-button type="button" wire:click.prevent="save" class="ml-2 bg-[#0070C0] hover:bg-[#005B9F]">

                    Simpan Data

                </x-primary-button>

            </x-slot>

        </x-modal>



        <!-- ================= MODAL EDIT PENGGUNA ================= -->
        <x-modal wire:model="confirmEdit">

            <x-slot name="title">

                <span class="text-lg font-semibold text-black">
                    Edit Data Pengguna
                </span>

            </x-slot>

            <x-slot name="content">

                <div class="grid grid-cols-2 gap-4">

                    {{-- NIP --}}
                    <div>

                        <x-input-label value="NIP" />

                        <x-input type="text" wire:model.defer="nip" disabled />

                    </div>

                    {{-- KANTOR --}}
                    <div>

                        <x-input-label value="KANTOR ID" />

                        <x-input type="text" wire:model.defer="kantor_id" readonly />

                    </div>

                    {{-- NAMA LENGKAP --}}
                    <div>

                        <x-input-label value="NAMA LENGKAP" />

                        <x-input type="text" wire:model.defer="nama_lengkap" />

                    </div>

                    {{-- NAMA AWAL --}}
                    <div>

                        <x-input-label value="NAMA AWAL" />

                        <x-input type="text" wire:model.defer="nama_awal" />

                    </div>

                    {{-- NAMA AKHIR --}}
                    <div>

                        <x-input-label value="NAMA AKHIR" />

                        <x-input type="text" wire:model.defer="nama_akhir" />

                    </div>

                    {{-- USERNAME --}}
                    <div>

                        <x-input-label value="NAMA PEMAKAI" />

                        <x-input type="text" wire:model.defer="nama_pemakai" />

                    </div>

                    <!-- email -->
                     <div>

                        <x-input-label
                            for="email"
                            value="EMAIL"
                        />

                        <x-input
                            type="email"
                            wire:model.defer="email"
                        />

                        <x-input-error
                            :messages="$errors->get('email')"
                        />

                    </div>

                    <!-- no wa -->
                     <div>
                            <x-input-label
                                for="no_wa"
                                value="NO WHATSAPP"
                            />

                            <x-input
                                wire:model.live="no_wa"
                                id="no_wa"
                                type="text"
                                name="no_wa"
                                class="block mt-1 w-full"
                                placeholder="628123456789"
                                maxlength="16"
                            />

                            <x-input-error
                                :messages="$errors->get('no_wa')"
                                class="mt-2"
                            />

                        </div>

                    {{-- PASSWORD --}}
                    <div>

                        <x-input-label value="PASSWORD BARU" />

                        <x-input type="password" wire:model.defer="password"
                            placeholder="Kosongkan jika tidak diubah" />

                    </div>

                    {{-- ROLE --}}
                    <div>

                        <x-input-label value="ROLE" />

                        <select wire:model.defer="role_id" class="w-full rounded-lg border border-slate-300
                               px-4 py-2 shadow-sm">

                            @foreach($roles as $role)

                                <option value="{{ $role->id }}">

                                    {{ $role->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- STATUS --}}
                    <div>

                        <x-input-label value="AKTIF" />

                        <select wire:model.defer="aktif" class="w-full rounded-lg border border-slate-300
                               px-4 py-2 shadow-sm">

                            <option value="1">
                                Aktif
                            </option>

                            <option value="0">
                                Nonaktif
                            </option>

                        </select>

                    </div>

                </div>

            </x-slot>

            <x-slot name="footer">

                <x-secondary-button type="button" wire:click.prevent="closeModal">

                    Batal

                </x-secondary-button>

                <x-primary-button type="button" wire:click.prevent="update" class="ml-2 bg-[#0070C0] hover:bg-[#005B9F]">

                    Update Data

                </x-primary-button>

            </x-slot>

        </x-modal>



        {{-- TABLE --}}
        <div class="rounded-2xl overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full border-collapse">

                    {{-- HEADER --}}
                    <thead class="bg-[#0070C0] text-white">

                        <tr class="text-sm uppercase tracking-wide">

                            <th class="px-6 py-4 text-left">
                                NIP
                            </th>

                            <th class="px-6 py-4 text-left">
                                NAMA LENGKAP
                            </th>

                            <th class="px-6 py-4 text-left">
                                USERNAME
                            </th>

                            <th class="px-6 py-4 text-left">
                                EMAIL
                            </th>

                            <th class="px-6 py-4 text-left">
                                NO WA
                            </th>

                            <th class="px-6 py-4 text-left">
                                ROLE
                            </th>

                            <th class="px-6 py-4 text-center">
                                STATUS
                            </th>

                            <th class="px-6 py-4 text-left">
                                CREATED BY
                            </th>

                            <th class="px-6 py-4 text-left">
                                CREATED DATE
                            </th>

                            <th class="px-6 py-4 text-left w-[180px]">
                                Last Login
                            </th>

                            <th class="px-6 py-4 text-center">
                                ACTION
                            </th>

                        </tr>

                    </thead>

                    {{-- BODY --}}
                    <tbody class="bg-white divide-y divide-slate-200">

                        @forelse($penggunas as $pengguna)

                                            <tr class="hover:bg-blue-50 transition duration-200">

                                                {{-- NIP --}}
                                                <td class="px-6 py-4 font-semibold text-slate-700">

                                                    {{ $pengguna->nip }}

                                                </td>

                                                {{-- NAMA --}}
                                                <td class="px-6 py-4">

                                                    {{ $pengguna->nama_lengkap }}

                                                </td>

                                                {{-- USERNAME --}}
                                                <td class="px-6 py-4">

                                                    {{ $pengguna->nama_pemakai }}

                                                </td>
                                                {{-- EMAIL --}}
                                                <td class="px-6 py-4">

                                                    {{ $pengguna->email }}

                                                </td>

                                                {{-- NO WA --}}
                                                <td class="px-6 py-4">

                                                    {{ $pengguna->no_wa }}

                                                </td>

                                                {{-- ROLE --}}
                                                <td class="px-6 py-4">

                                                    {{ $pengguna->role->name ?? '-' }}

                                                </td>

                                                {{-- STATUS --}}
                                                <td class="px-6 py-4 text-center">

                                                    @if($pengguna->aktif == '1')

                                                        <span class="bg-green-100 text-green-700
                                                                                                 px-3 py-1 rounded-full
                                                                                                 text-xs font-semibold">

                                                            Aktif

                                                        </span>

                                                    @else

                                                        <span class="bg-red-100 text-red-700
                                                                                                 px-3 py-1 rounded-full
                                                                                                 text-xs font-semibold">

                                                            Nonaktif

                                                        </span>

                                                    @endif

                                                </td>

                                                {{-- CREATED BY --}}
                                                <td class="px-6 py-4">

                                                    {{ $pengguna->created_by }}

                                                </td>

                                                {{-- CREATED DATE --}}
                                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">

                                                    {{ $pengguna->created_date
                            ? \Carbon\Carbon::parse($pengguna->created_date)->format('d M Y H:i:s')
                            : '-' }}

                                                </td>

                                                <!-- last login -->
                                                 <td class="px-6 py-4 text-slate-500 whitespace-nowrap">

                                                    @if($pengguna->last_login)

                                                        {{ \Carbon\Carbon::parse(
                                                            $pengguna->last_login
                                                        )->format('d M Y H:i') }}

                                                    @else
                                                        Belum Pernah Login
                                                    @endif

                                                </td>

                                                {{-- ACTION --}}
                                                <td class="px-6 py-4">

                                                    <div class="flex justify-center gap-2">

                                                        {{-- EDIT --}}
                                                        <button wire:click="edit('{{ $pengguna->nip }}')" class="bg-[#0070C0] hover:bg-[#005B9F]
                                                                                       text-white px-4 py-2
                                                                                       rounded-lg text-xs
                                                                                       font-semibold shadow">

                                                            Edit

                                                        </button>

                                                        {{-- DELETE --}}
                                                        <button wire:click="confirmDelete('{{ $pengguna->nip }}')" class="bg-red-500 hover:bg-red-600
                                                                                       text-white px-4 py-2
                                                                                       rounded-lg text-xs
                                                                                       font-semibold shadow">

                                                            Hapus

                                                        </button>

                                                    </div>

                                                </td>

                                            </tr>

                        @empty

                            <tr>

                                <td colspan="8" class="text-center py-10 text-slate-500">

                                    Data tidak ditemukan

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>



        {{-- PAGINATION --}}
        <div class="flex items-center gap-2 mt-4">

            <label class="text-sm text-slate-600">
                Show
            </label>

            <select wire:model.live="perPage" class="border border-slate-300 rounded-md
                   px-2 py-2 text-sm">

                <option value="5">5</option>

                <option value="10">10</option>

                <option value="25">25</option>

                <option value="50">50</option>

                <option value="999999">All</option>

            </select>

            <label class="text-sm text-slate-600">
                entries
            </label>

        </div>

    </div>
</div>