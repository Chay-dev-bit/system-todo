<div>
    <div class="flex items-center gap-2">
        <x-nav-link :href="route('dashboard')">
            Dashboard
        </x-nav-link>
        <x-nav-link :href="route('project')" :active="request()->routeIs('project')">
            Project
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
                <button type="button" wire:click="showDataInput"
                    class="bg-[#0070C0] hover:bg-blue-800 text-white px-4 py-2 mb-4 rounded w-52 transition delay-150 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110">
                    Tambah Project
                </button>
                <input type="text" wire:model.live="search" placeholder="Search"
                    class="w-72 rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- ================= MODAL INPUT PROJECT ================= -->
        <x-modal wire:model="confirmInput">
            <x-slot name="title">
                <span class="text-lg font-semibold text-black">
                    Tambah Project
                </span>
            </x-slot>
            <x-slot name="content">
                <div class="space-y-4">
                    {{-- KODE PROJECT --}}
                    <div>
                        <x-input-label for="kode_project" value="KODE PROJECT" />
                        <x-input type="text" wire:model.defer="kode_project" placeholder="Masukkan Kode Project" />
                        <x-input-error :messages="$errors->get('kode_project')" />
                    </div>

                    {{-- PROJECT NAME --}}
                    <div>
                        <x-input-label for="project_name" value="NAMA PROJECT" />
                        <x-input type="text" wire:model.defer="project_name" placeholder="Masukkan Nama Project" />
                        <x-input-error :messages="$errors->get('project_name')" />
                    </div>

                    {{-- DESCRIPTION --}}
                    <div>
                        <x-input-label for="description" value="DESKRIPSI" />
                        <textarea wire:model.defer="description" rows="3"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan Deskripsi Project"></textarea>
                        <x-input-error :messages="$errors->get('description')" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- START DATE --}}
                        <div>
                            <x-input-label for="start_date" value="TANGGAL MULAI" />
                            <x-input type="date" wire:model.defer="start_date" />
                            <x-input-error :messages="$errors->get('start_date')" />
                        </div>

                        {{-- END DATE --}}
                        <div>
                            <x-input-label for="end_date" value="TANGGAL SELESAI" />
                            <x-input type="date" wire:model.defer="end_date" />
                            <x-input-error :messages="$errors->get('end_date')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- CAPEX / OPEX --}}
                        <div>
                            <x-input-label for="capex_or_opex" value="CAPEX / OPEX" />
                            <select wire:model.defer="capex_or_opex"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                <option value="capex">CAPEX</option>
                                <option value="opex">OPEX</option>
                            </select>
                            <x-input-error :messages="$errors->get('capex_or_opex')" />
                        </div>

                        {{-- NO. REKENING --}}
                        <div>
                            <x-input-label for="no_rekening" value="NO. REKENING" />
                            <x-input type="text" wire:model.defer="no_rekening" placeholder="Masukkan No. Rekening" />
                            <x-input-error :messages="$errors->get('no_rekening')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- BIAYA --}}
                        <div>
                            <x-input-label for="biaya_formatted" value="BIAYA" />
                            <x-input type="text" wire:model.defer="biaya_formatted" placeholder="Rp 0,00" />
                            <x-input-error :messages="$errors->get('biaya_formatted')" />
                        </div>

                        {{-- VENDOR --}}
                        <div>
                            <x-input-label for="vendor" value="VENDOR" />
                            <x-input type="text" wire:model.defer="vendor" placeholder="Masukkan Vendor" />
                            <x-input-error :messages="$errors->get('vendor')" />
                        </div>
                    </div>

                    {{-- PIC --}}
                    <div>
                        <x-input-label for="pic_id" value="PIC" />
                        <select wire:model.defer="pic_id"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih PIC --</option>
                            @foreach($penggunas as $pengguna)
                                <option value="{{ $pengguna->nip }}">
                                    {{ $pengguna->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('pic_id')" />
                    </div>

                    {{-- STATUS --}}
                    <div>
                        <x-input-label for="status" value="STATUS" />
                        <select wire:model.defer="status"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                            <option value="pending">Pending</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- VERIFIED BY --}}
                        <div>
                            <x-input-label for="verified_by" value="DIVERIFIKASI OLEH" />
                            <select wire:model.defer="verified_by"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                @foreach($penggunas as $pengguna)
                                    <option value="{{ $pengguna->nip }}">
                                        {{ $pengguna->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('verified_by')" />
                        </div>

                        {{-- APPROVED BY --}}
                        <div>
                            <x-input-label for="approved_by" value="DISETUJUI OLEH" />
                            <select wire:model.defer="approved_by"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                @foreach($penggunas as $pengguna)
                                    <option value="{{ $pengguna->nip }}">
                                        {{ $pengguna->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('approved_by')" />
                        </div>
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="closeModal">
                    Batal
                </x-secondary-button>
                <x-primary-button wire:click="save" class="ml-2 bg-[#0070C0] hover:bg-[#005B9F]">
                    Simpan
                </x-primary-button>
            </x-slot>
        </x-modal>

        <!-- ================= MODAL EDIT PROJECT ================= -->
        <x-modal wire:model="confirmEdit">
            <x-slot name="title">
                <span class="text-lg font-semibold text-black">
                    Edit Project
                </span>
            </x-slot>
            <x-slot name="content">
                <div class="space-y-4">
                    {{-- KODE PROJECT --}}
                    <div>
                        <x-input-label for="kode_project" value="KODE PROJECT" />
                        <x-input type="text" wire:model.defer="kode_project" placeholder="Masukkan Kode Project" />
                        <x-input-error :messages="$errors->get('kode_project')" />
                    </div>

                    {{-- PROJECT NAME --}}
                    <div>
                        <x-input-label for="project_name" value="NAMA PROJECT" />
                        <x-input type="text" wire:model.defer="project_name" placeholder="Masukkan Nama Project" />
                        <x-input-error :messages="$errors->get('project_name')" />
                    </div>

                    {{-- DESCRIPTION --}}
                    <div>
                        <x-input-label for="description" value="DESKRIPSI" />
                        <textarea wire:model.defer="description" rows="3"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan Deskripsi Project"></textarea>
                        <x-input-error :messages="$errors->get('description')" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- START DATE --}}
                        <div>
                            <x-input-label for="start_date" value="TANGGAL MULAI" />
                            <x-input type="date" wire:model.defer="start_date" />
                            <x-input-error :messages="$errors->get('start_date')" />
                        </div>

                        {{-- END DATE --}}
                        <div>
                            <x-input-label for="end_date" value="TANGGAL SELESAI" />
                            <x-input type="date" wire:model.defer="end_date" />
                            <x-input-error :messages="$errors->get('end_date')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- CAPEX / OPEX --}}
                        <div>
                            <x-input-label for="capex_or_opex" value="CAPEX / OPEX" />
                            <select wire:model.defer="capex_or_opex"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                <option value="capex">CAPEX</option>
                                <option value="opex">OPEX</option>
                            </select>
                            <x-input-error :messages="$errors->get('capex_or_opex')" />
                        </div>

                        {{-- NO. REKENING --}}
                        <div>
                            <x-input-label for="no_rekening" value="NO. REKENING" />
                            <x-input type="text" wire:model.defer="no_rekening" placeholder="Masukkan No. Rekening" />
                            <x-input-error :messages="$errors->get('no_rekening')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- BIAYA --}}
                        <div>
                            <x-input-label for="biaya_formatted" value="BIAYA" />
                            <x-input type="text" wire:model.defer="biaya_formatted" placeholder="Rp 0,00" />
                            <x-input-error :messages="$errors->get('biaya_formatted')" />
                        </div>

                        {{-- VENDOR --}}
                        <div>
                            <x-input-label for="vendor" value="VENDOR" />
                            <x-input type="text" wire:model.defer="vendor" placeholder="Masukkan Vendor" />
                            <x-input-error :messages="$errors->get('vendor')" />
                        </div>
                    </div>

                    {{-- PIC --}}
                    <div>
                        <x-input-label for="pic_id" value="PIC" />
                        <select wire:model.defer="pic_id"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih PIC --</option>
                            @foreach($penggunas as $pengguna)
                                <option value="{{ $pengguna->nip }}">
                                    {{ $pengguna->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('pic_id')" />
                    </div>

                    {{-- STATUS --}}
                    <div>
                        <x-input-label for="status" value="STATUS" />
                        <select wire:model.defer="status"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                            <option value="pending">Pending</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- VERIFIED BY --}}
                        <div>
                            <x-input-label for="verified_by" value="DIVERIFIKASI OLEH" />
                            <select wire:model.defer="verified_by"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                @foreach($penggunas as $pengguna)
                                    <option value="{{ $pengguna->nip }}">
                                        {{ $pengguna->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('verified_by')" />
                        </div>

                        {{-- APPROVED BY --}}
                        <div>
                            <x-input-label for="approved_by" value="DISETUJUI OLEH" />
                            <select wire:model.defer="approved_by"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                @foreach($penggunas as $pengguna)
                                    <option value="{{ $pengguna->nip }}">
                                        {{ $pengguna->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('approved_by')" />
                        </div>
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="closeModal">
                    Batal
                </x-secondary-button>
                <x-primary-button wire:click="update" class="ml-2 bg-[#0070C0] hover:bg-[#005B9F]">
                    Update
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
                            <th class="px-6 py-4 text-left">Kode Project</th>
                            <th class="px-6 py-4 text-left">Nama Project</th>
                            <th class="px-6 py-4 text-left" style="min-width: 300px;">Deskripsi</th>
                            <th class="px-6 py-4 text-left">Tanggal Mulai</th>
                            <th class="px-6 py-4 text-left">Tanggal Selesai</th>
                            <th class="px-6 py-4 text-left">Capex/Opex</th>
                            <th class="px-6 py-4 text-left">Biaya</th>
                            <th class="px-6 py-4 text-left">Vendor</th>
                            <th class="px-6 py-4 text-left">PIC</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-left">Verified By</th>
                            <th class="px-6 py-4 text-left">Approved By</th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>
                    {{-- BODY --}}
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($projects as $key => $project)
                            <tr class="hover:bg-blue-50 transition duration-200">
                                <td class="px-6 py-4 font-semibold text-slate-700">{{ $project->kode_project ?? '-' }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $project->project_name }}</td>
                                <td class="px-6 py-4">{{ Str::limit($project->description, 100) }}</td>
                                <td class="px-6 py-4">{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : '-' }}</td>
                                <td class="px-6 py-4">{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('d M Y') : '-' }}</td>
                                <td class="px-6 py-4">{{ $project->capex_or_opex ? strtoupper($project->capex_or_opex) : '-' }}</td>
                                <td class="px-6 py-4">{{ $project->biaya ? 'Rp ' . number_format($project->biaya, 2, ',', '.') : '-' }}</td>
                                <td class="px-6 py-4">{{ $project->vendor ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $project->pic->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($project->status == 'pending') bg-yellow-100 text-yellow-800 
                                        @elseif($project->status == 'ongoing') bg-blue-100 text-blue-800 
                                        @elseif($project->status == 'completed') bg-green-100 text-green-800 
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($project->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $project->verifier->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $project->approver->nama_lengkap ?? '-' }}</td>
                                {{-- ACTION --}}
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('task', $project->id) }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg text-sm font-semibold shadow min-w-[100px] text-center">
                                            Lihat Task
                                        </a>
                                        <button wire:click="edit('{{ $project->id }}')" class="bg-[#0070C0] hover:bg-[#005B9F] text-white px-4 py-2 rounded-lg text-xs font-semibold shadow">
                                            Edit
                                        </button>
                                        <button wire:click="confirmDelete('{{ $project->id }}')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center py-10 text-slate-500">
                                    Data tidak ditemukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center gap-2">
                <label class="text-sm text-slate-600">Show</label>
                <select wire:model.live="perPage" class="border border-slate-300 rounded-md px-2 py-2 text-sm">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <label class="text-sm text-slate-600">entries</label>
            </div>
            {{ $projects->links() }}
        </div>
    </div>
</div>
