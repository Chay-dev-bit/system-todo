<div>
    <div class="flex flex-wrap items-center gap-2 overflow-x-auto pb-2">
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

    <div class="p-3 sm:p-6">

        {{-- HEADER --}}
        <div class="items-center mb-6">

            <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">

                {{-- BUTTON TAMBAH --}}
                @if($currentUser && $currentUser->isAdmin())
                    <button type="button" wire:click="showDataInput"
                        class="bg-[#0070C0] hover:bg-blue-800
                        text-white px-4 py-2 mb-4 rounded
                        w-52 transition delay-150 duration-300
                        ease-in-out hover:-translate-y-1 hover:scale-110">

                        Tambah Data

                    </button>
                @endif

                {{-- SEARCH --}}
                <input type="text" wire:model.live="search" placeholder="Search" class="w-full sm:w-72 rounded-lg border border-slate-300
                px-4 py-2 shadow-sm
                focus:ring-2 focus:ring-blue-500">
            </div>
        </div>



        <!-- ================= MODAL INPUT UNIT ================= -->
        <x-modal wire:model="confirmInput">

            <x-slot name="title">

                <span class="text-lg font-semibold text-black">
                    Tambah Data Unit
                </span>

            </x-slot>

            <x-slot name="content">

                <div class="space-y-4 w-full">

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

                    {{-- ID --}}
                    <div>

                        <x-input-label for="kode_unit" value="ID" />

                        <x-input type="text" wire:model.defer="kode_unit" placeholder="Masukkan ID Unit" />

                        <x-input-error :messages="$errors->get('kode_unit')" />

                    </div>

                    {{-- UNIT NAME --}}
                    <div>

                        <x-input-label for="unit_name" value="UNIT NAME" />

                        <x-input type="text" wire:model.defer="unit_name" placeholder="Masukkan Nama Unit" />

                        <x-input-error :messages="$errors->get('unit_name')" />

                    </div>

                    {{-- TINGKAT --}}
                    <div>

                        <x-input-label for="tingkat" value="TINGKAT" />

                        <x-input type="text" wire:model.defer="tingkat" placeholder="Masukkan Tingkat" />

                        <x-input-error :messages="$errors->get('tingkat')" />

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



        <!-- ================= MODAL EDIT UNIT ================= -->
        <x-modal wire:model="confirmEdit">

            <x-slot name="title">

                <span class="text-lg font-semibold text-black">
                    Edit Data Unit
                </span>

            </x-slot>

            <x-slot name="content">

                <div class="space-y-4 w-full">

                    {{-- KANTOR ID --}}
                    <div wire:ignore>

                        <x-input-label for="kantor_id" value="KANTOR ID" />

                        <select wire:model.defer="kantor_id" class="w-full rounded-lg border border-slate-300
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

                    {{-- ID --}}
                    <div>

                        <x-input-label for="kode_unit" value="ID" />

                        <x-input type="text" wire:model.defer="kode_unit" disabled />

                    </div>

                    {{-- UNIT NAME --}}
                    <div>

                        <x-input-label for="unit_name" value="UNIT NAME" />

                        <x-input type="text" wire:model.defer="unit_name" />

                        <x-input-error :messages="$errors->get('unit_name')" />

                    </div>

                    {{-- TINGKAT --}}
                    <div>

                        <x-input-label for="tingkat" value="TINGKAT" />

                        <x-input type="text" wire:model.defer="tingkat" />

                        <x-input-error :messages="$errors->get('tingkat')" />

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
        <div class="rounded-2xl overflow-hidden bg-white shadow">

            <div class="overflow-x-auto w-full">

                <table class="min-w-[1000px] w-full border-collapse text-sm">

                    {{-- HEADER --}}
                    <thead class="bg-[#0070C0] text-white">

                        <tr class="text-sm uppercase tracking-wide">

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                KANTOR ID
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                ID
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                UNIT NAME
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                TINGKAT
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                CREATED DATE
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                CREATED BY
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                MODIFIED DATE
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                MODIFIED BY
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center whitespace-nowrap">
                                ACTION
                            </th>

                        </tr>

                    </thead>

                    {{-- BODY --}}
                    <tbody class="bg-white divide-y divide-slate-200">

                        @forelse($units as $unit)

                                            <tr class="hover:bg-blue-50 transition duration-200">

                                                <td class="px-6 py-4">
                                                    {{ $unit->kantor_id }}
                                                </td>

                                                <td class="px-6 py-4 font-semibold text-slate-700">
                                                    {{ $unit->id }}
                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $unit->unit_name }}
                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $unit->tingkat }}
                                                </td>

                                                <td class="px-6 py-4 text-slate-500 whitespace-nowrap">

                                                    {{ $unit->created_date
                            ? \Carbon\Carbon::parse($unit->created_date)->format('d M Y H:i:s')
                            : '-' }}

                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $unit->created_by }}
                                                </td>

                                                <td class="px-6 py-4 text-slate-500 whitespace-nowrap">

                                                    {{ $unit->modified_date
                            ? \Carbon\Carbon::parse($unit->modified_date)->format('d M Y H:i:s')
                            : '-' }}

                                                </td>

                                                <td class="px-6 py-4">
                                                    {{ $unit->modified_by }}
                                                </td>

                                                {{-- ACTION --}}
                                                <td class="px-6 py-4">

                                                    <div class="flex justify-center gap-2">

                                                        {{-- EDIT --}}
                                                        <button wire:click="edit('{{ $unit->id }}')"
                                                            @disabled(!$currentUser || !$currentUser->isAdmin())
                                                            class="px-4 py-2 rounded-lg text-xs font-semibold shadow {{ $currentUser && $currentUser->isAdmin() ? 'bg-[#0070C0] hover:bg-[#005B9F] text-white cursor-pointer' : 'bg-gray-400 text-gray-600 cursor-not-allowed opacity-60' }}">

                                                            Edit

                                                        </button>

                                                        {{-- DELETE --}}
                                                        <button wire:click="confirmDelete('{{ $unit->id }}')"
                                                            @disabled(!$currentUser || !$currentUser->isAdmin())
                                                            class="px-4 py-2 rounded-lg text-xs font-semibold shadow {{ $currentUser && $currentUser->isAdmin() ? 'bg-red-500 hover:bg-red-600 text-white cursor-pointer' : 'bg-gray-400 text-gray-600 cursor-not-allowed opacity-60' }}">

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