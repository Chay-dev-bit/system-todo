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
                <input type="text" wire:model.live="search" placeholder="Search" class="w-72 rounded-lg border border-slate-300
                       px-4 py-2 shadow-sm
                       focus:ring-2 focus:ring-blue-500">

            </div>

        </div>



        <!-- ================= MODAL INPUT ROLE ================= -->
        <x-modal wire:model="confirmInput">

            <x-slot name="title">

                <span class="text-lg font-semibold text-black">
                    Tambah Data Role
                </span>

            </x-slot>

            <x-slot name="content">

                <div class="space-y-4">

                    {{-- ROLE NAME --}}
                    <div>

                        <x-input-label value="ROLE NAME" />

                        <x-input type="text" wire:model.defer="name" placeholder="Masukkan Nama Role" />

                        <x-input-error :messages="$errors->get('name')" />

                    </div>

                    {{-- DESCRIPTION --}}
                    <div>

                        <x-input-label value="DESCRIPTION" />

                        <textarea wire:model.defer="description" rows="4" class="w-full rounded-lg border border-slate-300
                               px-4 py-2 shadow-sm
                               focus:ring-2 focus:ring-blue-500" placeholder="Masukkan Deskripsi Role">
                    </textarea>

                        <x-input-error :messages="$errors->get('description')" />

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



        <!-- ================= MODAL EDIT ROLE ================= -->
        <x-modal wire:model="confirmEdit">

            <x-slot name="title">

                <span class="text-lg font-semibold text-black">
                    Edit Data Role
                </span>

            </x-slot>

            <x-slot name="content">

                <div class="space-y-4">

                    {{-- ROLE NAME --}}
                    <div>

                        <x-input-label value="ROLE NAME" />

                        <x-input type="text" wire:model.defer="name" />

                        <x-input-error :messages="$errors->get('name')" />

                    </div>

                    {{-- DESCRIPTION --}}
                    <div>

                        <x-input-label value="DESCRIPTION" />

                        <textarea wire:model.defer="description" rows="4" class="w-full rounded-lg border border-slate-300
                               px-4 py-2 shadow-sm
                               focus:ring-2 focus:ring-blue-500">
                    </textarea>

                        <x-input-error :messages="$errors->get('description')" />

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

                            <th class="px-6 py-4 text-left w-[100px]">
                                ID
                            </th>

                            <th class="px-6 py-4 text-left w-[250px]">
                                ROLE NAME
                            </th>

                            <th class="px-6 py-4 text-left min-w-[400px]">
                                DESCRIPTION
                            </th>

                            <th class="px-6 py-4 text-center w-[180px]">
                                ACTION
                            </th>

                        </tr>

                    </thead>

                    {{-- BODY --}}
                    <tbody class="bg-white divide-y divide-slate-200">

                        @forelse($roles as $role)

                            <tr class="hover:bg-blue-50 transition duration-200">

                                {{-- ID --}}
                                <td class="px-6 py-4 font-semibold text-slate-700">

                                    {{ $role->id }}

                                </td>

                                {{-- ROLE NAME --}}
                                <td class="px-6 py-4">

                                    {{ $role->name }}

                                </td>

                                {{-- DESCRIPTION --}}
                                <td class="px-6 py-4 text-slate-600">

                                    {{ $role->description ?? '-' }}

                                </td>

                                {{-- ACTION --}}
                                <td class="px-6 py-4">

                                    <div class="flex justify-center gap-2">

                                        {{-- EDIT --}}
                                        <button wire:click="edit('{{ $role->id }}')" class="bg-[#0070C0] hover:bg-[#005B9F]
                                                           text-white px-4 py-2
                                                           rounded-lg text-xs
                                                           font-semibold shadow">

                                            Edit

                                        </button>

                                        {{-- DELETE --}}
                                        <button wire:click="confirmDelete('{{ $role->id }}')" class="bg-red-500 hover:bg-red-600
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

                                <td colspan="4" class="text-center py-10 text-slate-500">

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