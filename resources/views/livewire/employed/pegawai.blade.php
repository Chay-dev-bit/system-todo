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
            {{-- SEARCH --}}
            <div class="flex justify-between">
                <!-- tambah data -->
                <button type="button" wire:click="showDataInput"
                    class="bg-[#0070C0] hover:bg-blue-800 text-white px-4 py-2 mb-4 rounded w-52 transition delay-150 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110">
                    Tambah Data
                </button>
                <input type="text" wire:model.live="search" placeholder="Search"
                    class="w-72 rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
            </div>

            @if (session()->has('success'))
                <div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-700 p-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-700 p-4">
                    {{ session('error') }}
                </div>
            @endif


        </div>
<!-- ================= MODAL INPUT PEGAWAI ================= -->
<x-modal wire:model="confirmInput">

    <x-slot name="title">

        <span class="text-lg font-semibold text-black">
            Tambah Data Pegawai
        </span>

    </x-slot>

    <x-slot name="content">

        <div class="grid grid-cols-2 gap-4">

            {{-- NIP --}}
            <div>

                <x-input-label value="NIP" />

                <x-input
                    type="text"
                    wire:model.defer="nip"
                    placeholder="Masukkan NIP"
                />

                <x-input-error :messages="$errors->get('nip')" />

            </div>

            {{-- NAMA --}}
            <div>

                <x-input-label value="NAMA" />

                <x-input
                    type="text"
                    wire:model.defer="nama"
                    placeholder="Masukkan Nama Pegawai"
                />

                <x-input-error :messages="$errors->get('nama')" />

            </div>

            {{-- TEMPAT LAHIR --}}
            <div>

                <x-input-label value="TEMPAT LAHIR" />

                <x-input
                    type="text"
                    wire:model.defer="tempat_lahir"
                />

            </div>

            {{-- TANGGAL LAHIR --}}
            <div>

                <x-input-label value="TANGGAL LAHIR" />

                <x-input
                    type="date"
                    wire:model.defer="tanggal_lahir"
                />

            </div>

            {{-- JENIS KELAMIN --}}
            <div>

                <x-input-label value="JENIS KEL" />

                <select
                    wire:model.defer="jen_kel"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2">

                    <option value="">
                        -- Pilih --
                    </option>

                    <option value="L">
                        Laki-Laki
                    </option>

                    <option value="P">
                        Perempuan
                    </option>

                </select>

            </div>

            {{-- AGAMA --}}
            <div>

                <x-input-label value="AGAMA" />
                <select
                            wire:model.defer="agama"
                            class="w-full rounded-lg border border-slate-300
                                px-4 py-2 shadow-sm
                                focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                -- Pilih Agama --
                            </option>

                            <option value="Islam">
                                Islam
                            </option>

                            <option value="Kristen">
                                Kristen
                            </option>

                            <option value="Katolik">
                                Katolik
                            </option>

                            <option value="Hindu">
                                Hindu
                            </option>

                            <option value="Buddha">
                                Buddha
                            </option>

                            <option value="Konghucu">
                                Konghucu
                            </option>

                        </select>

                        <x-input-error :messages="$errors->get('agama')" />

            </div>

            {{-- STATUS --}}
            <div>

                <x-input-label value="STATUS PERKAWINAN" />

                <select
                            wire:model.defer="status"
                            class="w-full rounded-lg border border-slate-300
                                px-4 py-2 shadow-sm
                                focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                -- Pilih Status Perkawinan --
                            </option>

                            <option value="Kawin">
                                K
                            </option>

                            <option value="Belum Kawin">
                                B
                            </option>

                </select>

            </div>

            {{-- TELEPON --}}
            <div>

                <x-input-label value="NO TELP" />

                <x-input
                    type="text"
                    wire:model.defer="no_telp"
                />

            </div>

            {{-- EMAIL --}}
            <div class="col-span-2">

                <x-input-label value="AL EMAIL" />

                <x-input
                    type="email"
                    wire:model.defer="al_email"
                />

            </div>

            {{-- ALAMAT --}}
            <div class="col-span-2">

                <x-input-label value="ALAMAT" />

                <textarea
                    wire:model.defer="alamat"
                    rows="3"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2">
                </textarea>

            </div>

            {{-- KANTOR --}}
            <div wire:ignore>

                <x-input-label value="KANTOR ID" />

                <select
                    wire:model.defer="kantor_id"
                    class="tom-select w-full rounded-lg border border-slate-300 px-4 py-2">

                    <option value="">
                        -- Pilih Kantor --
                    </option>

                    @foreach($kantors as $kantor)

                        <option value="{{ $kantor->id }}">

                            {{ $kantor->id }} - {{ $kantor->nama }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- UNIT --}}
            <div wire:ignore>

                <x-input-label value="UNIT ID" />

                <select
                    wire:model.defer="unit_id"
                    class="tom-select w-full rounded-lg border border-slate-300 px-4 py-2">

                    <option value="">
                        -- Pilih Unit --
                    </option>

                    @foreach($units as $unit)

                        <option value="{{ $unit->id }}">

                            {{ $unit->id }} - {{ $unit->unit_name }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- JABATAN --}}
            <div wire:ignore>

                <x-input-label value="JABATAN ID" />

                <select
                    wire:model.defer="jabatan_id"
                    class="tom-select w-full rounded-lg border border-slate-300 px-4 py-2">

                    <option value="">
                        -- Pilih Jabatan --
                    </option>

                    @foreach($jabatans as $jabatan)

                        <option value="{{ $jabatan->id }}">

                            {{ $jabatan->id }} - {{ $jabatan->nama_jabatan }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- STATUS AKTIF --}}
            <div>

                <x-input-label value="AKTIF" />

                <select
                    wire:model.defer="aktif"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2">

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

        <x-secondary-button wire:click="closeModal">

            Batal

        </x-secondary-button>

        <x-primary-button
            wire:click="save"
            class="ml-2 bg-[#0070C0] hover:bg-[#005B9F]">

            Simpan Data

        </x-primary-button>

    </x-slot>

</x-modal>
<!-- ================= MODAL EDIT PEGAWAI ================= -->
<x-modal wire:model="confirmEdit">

    <x-slot name="title">

        <span class="text-lg font-semibold text-black">
            Edit Data Pegawai
        </span>

    </x-slot>

    <x-slot name="content">

        <div class="grid grid-cols-2 gap-4">

            {{-- NIP --}}
            <div>

                <x-input-label value="NIP" />

                <x-input
                    type="text"
                    wire:model.defer="nip"
                    disabled
                />

                <input type="hidden" wire:model.defer="nip" />

            </div>

            {{-- NAMA --}}
            <div>

                <x-input-label value="NAMA" />

                <x-input
                    type="text"
                    wire:model.defer="nama"
                />

                <x-input-error :messages="$errors->get('nama')" />

            </div>

            {{-- TEMPAT LAHIR --}}
            <div>

                <x-input-label value="TEMPAT LAHIR" />

                <x-input
                    type="text"
                    wire:model.defer="tempat_lahir"
                />

            </div>

            {{-- TANGGAL LAHIR --}}
            <div>

                <x-input-label value="TANGGAL LAHIR" />

                <x-input
                    type="date"
                    wire:model.defer="tanggal_lahir"
                />

            </div>

            {{-- JENIS KELAMIN --}}
            <div>

                <x-input-label value="JENIS KEL" />

                <select
                    wire:model.defer="jen_kel"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2">

                    <option value="L">
                        Laki-Laki
                    </option>

                    <option value="P">
                        Perempuan
                    </option>

                </select>

            </div>

            {{-- AGAMA --}}
            <div>

                <x-input-label value="AGAMA" />
                <select
                            wire:model.defer="agama"
                            class="w-full rounded-lg border border-slate-300
                                px-4 py-2 shadow-sm
                                focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                -- Pilih Agama --
                            </option>

                            <option value="Islam">
                                Islam
                            </option>

                            <option value="Kristen">
                                Kristen
                            </option>

                            <option value="Katolik">
                                Katolik
                            </option>

                            <option value="Hindu">
                                Hindu
                            </option>

                            <option value="Buddha">
                                Buddha
                            </option>

                            <option value="Konghucu">
                                Konghucu
                            </option>

                        </select>

                        <x-input-error :messages="$errors->get('agama')" />

            </div>

            {{-- STATUS --}}
            <div>

                <x-input-label value="STATUS PERKAWINAN" />

                <select
                            wire:model.defer="status"
                            class="w-full rounded-lg border border-slate-300
                                px-4 py-2 shadow-sm
                                focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                -- Pilih Status Perkawinan --
                            </option>

                            <option value="Kawin">
                                K
                            </option>

                            <option value="Belum Kawin">
                                B
                            </option>

                </select>

            </div>

            {{-- TELEPON --}}
            <div>

                <x-input-label value="NO TELP" />

                <x-input
                    type="text"
                    wire:model.defer="no_telp"
                />

            </div>

            {{-- EMAIL --}}
            <div class="col-span-2">

                <x-input-label value="AL EMAIL" />

                <x-input
                    type="email"
                    wire:model.defer="al_email"
                />

            </div>

            {{-- ALAMAT --}}
            <div class="col-span-2">

                <x-input-label value="ALAMAT" />

                <textarea
                    wire:model.defer="alamat"
                    rows="3"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2">
                </textarea>

            </div>

            {{-- KANTOR --}}
            <div wire:ignore>

                <x-input-label value="KANTOR ID" />

                <select
                    wire:model.defer="kantor_id"
                    class="tom-select w-full rounded-lg border border-slate-300 px-4 py-2">
                    
                    @foreach($kantors as $kantor)

                        <option value="{{ $kantor->id }}">

                            {{ $kantor->id }} - {{ $kantor->nama }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- UNIT --}}
            <div wire:ignore>

                <x-input-label value="UNIT ID" />

                <select
                    wire:model.defer="unit_id"
                    class="tom-select w-full rounded-lg border border-slate-300 px-4 py-2">

                    @foreach($units as $unit)

                        <option value="{{ $unit->id }}">

                            {{ $unit->id }} - {{ $unit->unit_name }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- JABATAN --}}
            <div wire:ignore>

                <x-input-label value="JABATAN ID" />

                <select
                    wire:model.defer="jabatan_id"
                    class="tom-select w-full rounded-lg border border-slate-300 px-4 py-2">

                    @foreach($jabatans as $jabatan)

                        <option value="{{ $jabatan->id }}">

                            {{ $jabatan->id }} - {{ $jabatan->nama_jabatan }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- STATUS AKTIF --}}
            <div>

                <x-input-label value="AKTIF" />

                <select
                    wire:model.defer="aktif"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2">

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

        <x-secondary-button wire:click="closeModal">

            Batal

        </x-secondary-button>

        <button
            type="button"
            wire:click.prevent="update"
            wire:loading.attr="disabled"
            wire:target="update"
            class="ml-2 inline-flex items-center px-4 py-2 bg-[#0070C0] text-white rounded-lg hover:bg-[#005B9F] focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed">

            <span wire:loading.remove wire:target="update">Update Data</span>
            <span wire:loading wire:target="update">Updating...</span>

        </button>

    </x-slot>

</x-modal>
        {{-- TABLE --}}
        <div class="rounded-2xl overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full border-collapse">

                    {{-- HEADER --}}
                    <thead class="bg-[#0070C0] text-white">

                        <tr class="text-sm uppercase tracking-wide">

                            <th class="px-6 py-4 text-left w-[100px]">
                                NIP
                            </th>

                            <th class="px-6 py-4 text-left w-[100px]">
                                Nama
                            </th>

                            <th class="px-6 py-4 text-left w-[120px]">
                                Tempat Lahir
                            </th>

                            <th class="px-6 py-4 text-left min-w-[300px]">
                                Tanggal Lahir
                            </th>

                            <th class="px-6 py-4 text-center w-[100px]">
                                Jenis Kel
                            </th>

                            <th class="px-6 py-4 text-center w-[100px]">
                                Alamat
                            </th>
                            <th class="px-6 py-4 text-center w-[100px]">
                                Agama
                            </th>
                            <th class="px-6 py-4 text-center w-[100px]">
                                Status Perkawinan
                            </th>
                            <th class="px-6 py-4 text-center w-[100px]">
                                No telepon
                            </th>
                            <th class="px-6 py-4 text-center w-[100px]">
                                Alamat Email
                            </th>
                            <th class="px-6 py-4 text-center w-[100px]">
                                AKtif
                            </th>
                            <th class="px-6 py-4 text-center w-[100px]">
                                Kantor ID
                            </th>
                            <th class="px-6 py-4 text-center w-[100px]">
                                Unit ID
                            </th>
                            <th class="px-6 py-4 text-center w-[100px]">
                                Jabatan ID
                            </th>
                            <th class="px-6 py-4 text-left w-[150px]">
                                Created By
                            </th>

                            <th class="px-6 py-4 text-left w-[150px]">
                                Created Date
                            </th>

                            <th class="px-6 py-4 text-left w-[150px]">
                                Modified By
                            </th>
                            <th class="px-6 py-4 text-left w-[150px]">
                                Modified Date
                            </th>
                            <th class="px-6 py-4 text-left w-[150px]">
                                Approved By
                            </th>
                            <th class="px-6 py-4 text-left w-[150px]">
                                Approved Date
                            </th>
                            <th class="px-6 py-4 text-center w-[180px]">
                                Action
                            </th>

                        </tr>

                    </thead>

                    {{-- BODY --}}
                    <tbody class="bg-white divide-y divide-slate-200">

                        @forelse($pegawais as $pegawai)

                                            <tr class="hover:bg-blue-50 transition duration-200">

                                                <td class="px-6 py-4 font-semibold text-slate-700">
                                                    {{ $pegawai->nip }}
                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $pegawai->nama }}
                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $pegawai->tempat_lahir }}
                                                </td>

                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('d M Y') }}
                                                </td>

                                                <td class="px-6 py-4 text-center">
                                                    {{ $pegawai->jen_kel }}
                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $pegawai->alamat }}
                                                </td>

                                                <td class="px-6 py-4 text-center">
                                                    {{ $pegawai->agama }}
                                                </td>

                                                <td class="px-6 py-4 text-center">
                                                    {{ $pegawai->status_perkawinan }}
                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $pegawai->no_telp }}
                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $pegawai->al_email }}
                                                </td>

                                                <td class="px-6 py-4 text-center">

                                                    @if($pegawai->aktif == '1' || $pegawai->aktif == 'A')

                                                        <span class="bg-green-100 text-green-700
                                                                                 px-3 py-1 rounded-full text-xs font-semibold">

                                                            Aktif

                                                        </span>

                                                    @else

                                                        <span class="bg-red-100 text-red-700
                                                                                 px-3 py-1 rounded-full text-xs font-semibold">

                                                            Nonaktif

                                                        </span>

                                                    @endif

                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $pegawai->kantor_id }}
                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $pegawai->jabatan_unit_id }}
                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $pegawai->jabatan_id }}
                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $pegawai->created_by }}
                                                </td>

                                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">

                                                    {{ $pegawai->created_date
                            ? \Carbon\Carbon::parse($pegawai->created_date)->format('d M Y H:i:s')
                            : '-' }}

                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $pegawai->modified_by ?? '-' }}
                                                </td>

                                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">

                                                    {{ $pegawai->modified_date
                            ? \Carbon\Carbon::parse($pegawai->modified_date)->format('d M Y H:i:s')
                            : '-' }}

                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $pegawai->approved_by ?? '-' }}
                                                </td>

                                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">

                                                    {{ $pegawai->approved_date
                            ? \Carbon\Carbon::parse($pegawai->approved_date)->format('d M Y H:i:s')
                            : '-' }}

                                                </td>

                                                {{-- ACTION --}}
                                                <td class="px-6 py-4">

                                                    <div class="flex justify-center gap-2">

                                                        <button wire:click="edit('{{ $pegawai->nip }}')" class="bg-[#0070C0] hover:bg-[#005B9F]
                                                                   text-white px-4 py-2
                                                                   rounded-lg text-xs
                                                                   font-semibold shadow">

                                                            Edit

                                                        </button>

                                                        <button wire:click="confirmDelete('{{ $pegawai->nip }}')" class="bg-red-500 hover:bg-red-600
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

                                <td colspan="21" class="text-center py-10 text-slate-500">

                                    Data tidak ditemukan

                                </td>

                            </tr>

                        @endforelse

                    </tbody>
                </table>

            </div>

        </div>

        {{-- PAGINATION --}}
        <div class="flex items-center gap-2">

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