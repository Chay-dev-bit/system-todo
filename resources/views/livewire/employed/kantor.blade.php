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
            {{-- SEARCH --}}
            <div class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <!-- tambah data -->
                <button type="button" wire:click="showDataInput"
                    class="bg-[#0070C0] hover:bg-blue-800 text-white px-4 py-2 rounded-lg w-full sm:w-52 transition duration-300">
                    Tambah Data
                </button>
                <input type="text" wire:model.live="search" placeholder="Search"
                    class="w-full sm:w-72 rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
            </div>

        </div>

        <!-- ================ MODAL INPUT KANTOR ================ -->
        <x-modal wire:model="confirmInput">

            <x-slot name="title">
                <span class="text-lg font-semibold text-black">
                    Tambah Data Kantor
                </span>
            </x-slot>

            <x-slot name="content">

                <div class="space-y-4">

                    <div>
                        <x-input-label for="kode_kantor" value="ID Kantor" />

                        <x-input type="text" autocomplete="off" wire:model.defer="kode_kantor"
                            placeholder="Masukkan ID Kantor Max 6" maxlength="6" />

                        <x-input-error :messages="$errors->get('kode_kantor')" />
                    </div>

                    <div>
                        <x-input-label for="nama" value="Nama Kantor" />

                        <x-input type="text" wire:model.defer="nama" placeholder="Masukkan Nama Kantor" />

                        <x-input-error :messages="$errors->get('nama')" />
                    </div>

                    <div>
                        <x-input-label for="alamat" value="Alamat" />

                        <x-input type="text" wire:model.defer="alamat" placeholder="Masukkan Alamat" />

                        <x-input-error :messages="$errors->get('alamat')" />
                    </div>

                    <div>
                        <x-input-label for="kota" value="Kota" />

                        <x-input type="text" wire:model.defer="kota" placeholder="Masukkan Kota" />

                        <x-input-error :messages="$errors->get('kota')" />
                    </div>

                    <div>
                        <x-input-label for="telp" value="Telpon" />

                        <x-input type="text" wire:model.defer="telp" placeholder="Masukkan Telpon" />

                        <x-input-error :messages="$errors->get('telp')" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />

                        <x-input type="email" wire:model.defer="email" placeholder="Masukkan Email" />

                        <x-input-error :messages="$errors->get('email')" />
                    </div>

                    <div>
                        <x-input-label for="kantor_induk" value="kantor induk" />

                        <x-input type="text" wire:model.defer="kantor_induk" placeholder="Masukkan Kantor Induk" />

                        <x-input-error :messages="$errors->get('kantor_induk')" />
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

        <!-- ================ MODAL EDIT KANTOR ================ -->
        <x-modal wire:model="confirmEdit">

            <x-slot name="title">
                <span class="text-lg font-semibold text-black">
                    Edit Data Kantor
                </span>
            </x-slot>

            <x-slot name="content">

                <div class="space-y-4">

                    {{-- ID KANTOR --}}
                    <div>

                        <x-input-label for="kode_kantor" value="ID Kantor" />

                        <x-input type="text" wire:model.defer="kode_kantor" disabled />

                    </div>

                    {{-- NAMA --}}
                    <div>

                        <x-input-label for="nama" value="Nama Kantor" />

                        <x-input type="text" wire:model.defer="nama" placeholder="Masukkan Nama Kantor" />

                        <x-input-error :messages="$errors->get('nama')" />

                    </div>

                    {{-- ALAMAT --}}
                    <div>

                        <x-input-label for="alamat" value="Alamat" />

                        <x-input type="text" wire:model.defer="alamat" placeholder="Masukkan Alamat" />

                        <x-input-error :messages="$errors->get('alamat')" />

                    </div>

                    {{-- KOTA --}}
                    <div>

                        <x-input-label for="kota" value="Kota" />

                        <x-input type="text" wire:model.defer="kota" placeholder="Masukkan Kota" />

                        <x-input-error :messages="$errors->get('kota')" />

                    </div>

                    {{-- TELP --}}
                    <div>

                        <x-input-label for="telp" value="Telpon" />

                        <x-input type="text" wire:model.defer="telp" placeholder="Masukkan Telpon" />

                        <x-input-error :messages="$errors->get('telp')" />

                    </div>

                    {{-- EMAIL --}}
                    <div>

                        <x-input-label for="email" value="Email" />

                        <x-input type="email" wire:model.defer="email" placeholder="Masukkan Email" />

                        <x-input-error :messages="$errors->get('email')" />

                    </div>

                    {{-- KANTOR INDUK --}}
                    <div>

                        <x-input-label for="kantor_induk" value="Kantor Induk" />

                        <x-input type="text" wire:model.defer="kantor_induk" placeholder="Masukkan Kantor Induk" />

                        <x-input-error :messages="$errors->get('kantor_induk')" />

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

            <div class="overflow-x-auto">

                <table class="min-w-[1200px] w-full border-collapse text-sm">

                    {{-- HEADER --}}
                    <thead class="bg-[#0070C0] text-white">

                        <tr class="text-sm uppercase tracking-wide">

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                ID
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                Nama
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                Alamat
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                Kota
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center whitespace-nowrap">
                                Telpon
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center whitespace-nowrap">
                                Email
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                Created Date
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                Created By
                            </th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                Modified Date
                            </th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                Modified By
                            </th>
                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-left whitespace-nowrap">
                                Kantor Induk
                            </th>

                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center whitespace-nowrap">
                                Action
                            </th>

                        </tr>

                    </thead>

                    {{-- BODY --}}
                    <tbody class="bg-white divide-y divide-slate-200">

                        @forelse($kantors as $kantor)

                            <tr class="hover:bg-blue-50 transition duration-200">

                                <td class="px-3 sm:px-6 py-3 sm:py-4 font-semibold text-slate-700">
                                    {{ $kantor->id }}
                                </td>

                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    {{ $kantor->nama }}
                                </td>

                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    {{ $kantor->alamat }}
                                </td>

                                <td class="px-3 sm:px-6 py-3 sm:py-4">

                                    <div class="font-semibold text-slate-800 leading-relaxed">
                                        {{ $kantor->kota }}
                                    </div>

                                </td>

                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                                    {{ $kantor->telp }}
                                </td>

                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                                    {{ $kantor->email }}
                                </td>


                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-slate-500 whitespace-nowrap">

                                    {{ \Carbon\Carbon::parse($kantor->created_date)->format('d M Y H:i:s') }}

                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                                    {{ $kantor->created_by}}
                                </td>

                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-slate-500 whitespace-nowrap">

                                    {{ \Carbon\Carbon::parse($kantor->modified_date)->format('d M Y H:i:s') }}

                                </td>
                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-center">
                                    {{ $kantor->modified_by}}
                                </td>


                                <td class="px-3 sm:px-6 py-3 sm:py-4">
                                    {{ $kantor->kantor_induk }}
                                </td>

                                {{-- ACTION --}}
                                <td class="px-3 sm:px-6 py-3 sm:py-4">

                                    <div class="flex justify-center gap-2">

                                        <button wire:click="edit('{{ $kantor->id }}')"
                                            class="bg-[#0070C0] hover:bg-[#005B9F] text-white px-4 py-2 rounded-lg text-xs font-semibold shadow">
                                            Edit
                                        </button>

                                        <button wire:click="confirmDelete('{{ $kantor->id }}')"
                                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow">
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
        <div class="flex flex-col sm:flex-row items-center gap-2 mt-4">

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