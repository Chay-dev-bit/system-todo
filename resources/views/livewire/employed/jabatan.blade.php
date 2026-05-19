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
        <form method="POST" action="{{ route('logout') }}">
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

        </div>


        <!-- ================= MODAL INPUT JABATAN ================= -->
        <x-modal wire:model="confirmInput">

            <x-slot name="title">

                <span class="text-lg font-semibold text-black">
                    Tambah Data Jabatan
                </span>

            </x-slot>

            <x-slot name="content">

                <div class="space-y-4">

                    {{-- KANTOR ID --}}
                    <div wire:ignore>

                        <x-input-label for="kantor_id" value="KANTOR ID" />

                        <select wire:model.defer="kantor_id" class="tom-select w-full rounded-lg border border-slate-300
                               px-4 py-2 shadow-sm
                               focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                -- Pilih Kantor --
                            </option>

                            @foreach($kantors as $kantor)

                                <option value="{{ $kantor->id }}">

                                    {{ $kantor->id }} - {{ $kantor->nama }}

                                </option>

                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('kantor_id')" />

                    </div>

                    {{-- UNIT ID --}}
                    <div wire:ignore>

                        <x-input-label for="unit_id" value="UNIT ID" />

                        <select wire:model.defer="unit_id" class="tom-select w-full rounded-lg border border-slate-300
                               px-4 py-2 shadow-sm
                               focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                -- Pilih Unit --
                            </option>

                            @foreach($units as $unit)

                                <option value="{{ $unit->id }}">

                                    {{ $unit->id }} - {{ $unit->unit_name }}

                                </option>

                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('unit_id')" />

                    </div>

                    {{-- ID --}}
                    <div>

                        <x-input-label for="kode_jabatan" value="ID" />

                        <x-input type="text" wire:model.defer="kode_jabatan" placeholder="Masukkan ID Jabatan" />

                        <x-input-error :messages="$errors->get('kode_jabatan')" />

                    </div>

                    {{-- NAMA JABATAN --}}
                    <div>

                        <x-input-label for="nama_jabatan" value="NAMA JABATAN" />

                        <x-input type="text" wire:model.defer="nama_jabatan" placeholder="Masukkan Nama Jabatan" />

                        <x-input-error :messages="$errors->get('nama_jabatan')" />

                    </div>

                    {{-- TINGKAT --}}
                    <div>

                        <x-input-label for="tingkat" value="TINGKAT" />

                        <x-input type="text" wire:model.defer="tingkat" placeholder="Masukkan Tingkat" />

                        <x-input-error :messages="$errors->get('tingkat')" />

                    </div>

                    {{-- KLS JAB --}}
                    <div>

                        <x-input-label for="kls_jab" value="KLS JAB" />

                        <x-input type="text" wire:model.defer="kls_jab" placeholder="Masukkan Kelas Jabatan" />

                        <x-input-error :messages="$errors->get('kls_jab')" />

                    </div>

                    {{-- ATASAN BID --}}
                    <div>

                        <x-input-label for="atasan_bid" value="ATASAN BID" />

                        <x-input type="text" wire:model.defer="atasan_bid" placeholder="Masukkan Atasan Bidang" />

                        <x-input-error :messages="$errors->get('atasan_bid')" />

                    </div>

                    {{-- ATASAN JAB --}}
                    <div wire:ignore> 

                        <x-input-label for="atasan_jab" value="ATASAN JAB" />

                        <select wire:model.defer="atasan_jab" class="tom-select w-full rounded-lg border border-slate-300
                               px-4 py-2 shadow-sm
                               focus:ring-2 focus:ring-blue-500">

                            <option value="">
                                -- Pilih Atasan Jabatan --
                            </option>

                            @foreach($atasanJabatan as $atasan)

                                <option value="{{ $atasan->id }}">

                                    {{ $atasan->id }} - {{ $atasan->nama_jabatan }}

                                </option>

                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('atasan_jab')" />

                    </div>

                </div>

            </x-slot>

            <x-slot name="footer">

                <x-secondary-button wire:click="closeModal">

                    Batal

                </x-secondary-button>

                <x-primary-button wire:click="save" class="ml-2 bg-[#0070C0] hover:bg-[#005B9F]">

                    Simpan Data

                </x-primary-button>

            </x-slot>

        </x-modal>



        <!-- ================= MODAL EDIT JABATAN ================= -->
        <x-modal wire:model="confirmEdit">

            <x-slot name="title">

                <span class="text-lg font-semibold text-black">
                    Edit Data Jabatan
                </span>

            </x-slot>

            <x-slot name="content">

                <div class="space-y-4">

                    {{-- KANTOR ID --}}
                    <div wire:ignore>

                        <x-input-label for="kantor_id" value="KANTOR ID" />

                        <select wire:model.defer="kantor_id" class="tom-select w-full rounded-lg border border-slate-300
                               px-4 py-2 shadow-sm">

                            @foreach($kantors as $kantor)

                                <option value="{{ $kantor->id }}">

                                    {{ $kantor->id }} - {{ $kantor->nama }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- UNIT ID --}}
                    <div wire:ignore>

                        <x-input-label for="unit_id" value="UNIT ID" />

                        <select wire:model.defer="unit_id" class="tom-select w-full rounded-lg border border-slate-300
                               px-4 py-2 shadow-sm">

                            @foreach($units as $unit)

                                <option value="{{ $unit->id }}">

                                    {{ $unit->id }} - {{ $unit->unit_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    {{-- ID --}}
                    <div>

                        <x-input-label for="kode_jabatan" value="ID" />

                        <x-input type="text" wire:model.defer="kode_jabatan" disabled />

                    </div>

                    {{-- NAMA JABATAN --}}
                    <div>

                        <x-input-label for="nama_jabatan" value="NAMA JABATAN" />

                        <x-input type="text" wire:model.defer="nama_jabatan" />

                    </div>

                    {{-- TINGKAT --}}
                    <div>

                        <x-input-label for="tingkat" value="TINGKAT" />

                        <x-input type="text" wire:model.defer="tingkat" />

                    </div>

                    {{-- KLS JAB --}}
                    <div>

                        <x-input-label for="kls_jab" value="KLS JAB" />

                        <x-input type="text" wire:model.defer="kls_jab" />

                    </div>

                    {{-- ATASAN BID --}}
                    <div>

                        <x-input-label for="atasan_bid" value="ATASAN BID" />

                        <x-input type="text" wire:model.defer="atasan_bid" />

                    </div>

                    {{-- ATASAN JAB --}}
                    <div wire:ignore>

                        <x-input-label for="atasan_jab" value="ATASAN JAB" />

                        <select wire:model.defer="atasan_jab" class="tom-select w-full rounded-lg border border-slate-300
                               px-4 py-2 shadow-sm">

                            @foreach($atasanJabatan as $atasan)

                                <option value="{{ $atasan->id }}">

                                    {{ $atasan->id }} - {{ $atasan->nama_jabatan }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </x-slot>

            <x-slot name="footer">

                <x-secondary-button wire:click="closeModal">

                    Batal

                </x-secondary-button>

                <x-primary-button wire:click="update" class="ml-2 bg-[#0070C0] hover:bg-[#005B9F]">

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
                            <th class="px-6 py-4 text-left min-w-[150px]">
                                Kantor ID
                            </th>

                            <th class="px-6 py-4 text-left w-[120px]">
                                Unit ID
                            </th>

                            <th class="px-6 py-4 text-left w-[100px]">
                                ID
                            </th>

                            <th class="px-6 py-4 text-left min-w-[300px]">
                                Nama Jabatan
                            </th>

                            <th class="px-6 py-4 text-center w-[100px]">
                                Tingkat
                            </th>

                            <th class="px-6 py-4 text-center w-[100px]">
                                Kls Jab
                            </th>

                            <th class="px-6 py-4 text-left w-[150px]">
                                Atasan Bid
                            </th>

                            <th class="px-6 py-4 text-left w-[150px]">
                                Atasan Jab
                            </th>

                            <th class="px-6 py-4 text-left w-[150px]">
                                Created Date
                            </th>

                            <th class="px-6 py-4 text-left w-[150px]">
                                Created By
                            </th>
                            <th class="px-6 py-4 text-left w-[150px]">
                                Modified Date
                            </th>
                            <th class="px-6 py-4 text-left w-[150px]">
                                Modified By
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

                        @forelse($jabatans as $jabatan)

                            <tr class="hover:bg-blue-50 transition duration-200">


                                <td class="px-6 py-4">
                                    {{ $jabatan->kantor_id }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $jabatan->unit_id }}
                                </td>

                                <td class="px-6 py-4 font-semibold text-slate-700">
                                    {{ $jabatan->id }}
                                </td>

                                <td class="px-6 py-4">

                                    <div class="font-semibold text-slate-800 leading-relaxed">
                                        {{ $jabatan->nama_jabatan }}
                                    </div>

                                </td>

                                <td class="px-6 py-4 text-center">
                                    {{ $jabatan->tingkat }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    {{ $jabatan->kls_jab }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $jabatan->atasan_bid }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $jabatan->atasan_jab }}
                                </td>

                                <td class="px-6 py-4 text-slate-500 whitespace-nowrap">

                                    {{ \Carbon\Carbon::parse($jabatan->created_date)->format('d M Y H:i:s') }}

                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $jabatan->created_by }}
                                </td>

                                <td class="px-6 py-4 text-slate-500 whitespace-nowrap">

                                    {{ \Carbon\Carbon::parse($jabatan->modified_date)->format('d M Y H:i:s') }}

                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $jabatan->modified_by }}
                                </td>


                                <td class="px-6 py-4">
                                    {{ $jabatan->approved_by }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 whitespace-nowrap">

                                    {{ \Carbon\Carbon::parse($jabatan->approved_date)->format('d M Y H:i:s') }}

                                </td>

                                {{-- ACTION --}}
                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-2">

                                        <button wire:click="edit('{{ $jabatan->id }}')" class="bg-[#0070C0] hover:bg-[#005B9F]
                                                                                   text-white px-4 py-2
                                                                                   rounded-lg text-xs
                                                                                   font-semibold shadow">

                                            Edit

                                        </button>

                                        <button wire:click="confirmDelete('{{ $jabatan->id }}')" class="bg-red-500 hover:bg-red-600
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

                                <td colspan="9" class="text-center py-10 text-slate-500">

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