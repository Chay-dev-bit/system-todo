<div>
    <div class="flex items-center gap-2">
        <x-nav-link :href="route('dashboard')">
            Dashboard
        </x-nav-link>
        <x-nav-link :href="route('project')">
            Project
        </x-nav-link>
        <x-nav-link :active="true">
            Task ({{ $project->project_name }})
        </x-nav-link>
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <x-nav-link href="#" id="logout">
                Logout
            </x-nav-link>
        </form>
    </div>
    <div class="p-6">
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <h2 class="text-2xl font-bold text-blue-800">{{ $project->project_name }}</h2>
            <p class="text-sm text-slate-600 mt-1">{{ $project->description }}</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="items-center mb-6">
            <div class="flex justify-between">
                <button type="button" wire:click="showDataInput"
                    class="bg-[#0070C0] hover:bg-blue-800 text-white px-4 py-2 mb-4 rounded w-52 transition delay-150 duration-300 ease-in-out hover:-translate-y-1 hover:scale-110">
                    Tambah Task
                </button>
                <input type="text" wire:model.live="search" placeholder="Search"
                    class="w-72 rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- ================= MODAL INPUT TASK ================= -->
        <x-modal wire:model="confirmInput">
            <x-slot name="title">
                <span class="text-lg font-semibold text-black">
                    Tambah Task
                </span>
            </x-slot>
            <x-slot name="content">
                <div class="space-y-4">
                    <div>
                        <x-input-label for="title" value="NAMA TASK" />
                        <x-input type="text" wire:model.defer="title" placeholder="Masukkan Nama Task" />
                        <x-input-error :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="description" value="DESKRIPSI" />
                        <textarea wire:model.defer="description" rows="3"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan Deskripsi Task"></textarea>
                        <x-input-error :messages="$errors->get('description')" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="assigned_to" value="DI ASSIGN KE" />
                            <select wire:model.defer="assigned_to"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                @foreach($penggunas as $pengguna)
                                    <option value="{{ $pengguna->nip }}">
                                        {{ $pengguna->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('assigned_to')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="priority" value="PRIORITAS" />
                            <select wire:model.defer="priority"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                            <x-input-error :messages="$errors->get('priority')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="status" value="STATUS" />
                            <select wire:model.defer="status"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" />
                        </div>
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="closeModal">
                    Cancel
                </x-secondary-button>
                <x-primary-button wire:click="save" class="ml-2 bg-[#0070C0] hover:bg-[#005B9F]">
                    Simpan
                </x-primary-button>
            </x-slot>
        </x-modal>

        <!-- ================= MODAL EDIT TASK ================= -->
        <x-modal wire:model="confirmEdit">
            <x-slot name="title">
                <span class="text-lg font-semibold text-black">
                    Edit Task
                </span>
            </x-slot>
            <x-slot name="content">
                <div class="space-y-4">
                    <div>
                        <x-input-label for="title" value="NAMA TASK" />
                        <x-input type="text" wire:model.defer="title" placeholder="Masukkan Nama Task" />
                        <x-input-error :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="description" value="DESKRIPSI" />
                        <textarea wire:model.defer="description" rows="3"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan Deskripsi Task"></textarea>
                        <x-input-error :messages="$errors->get('description')" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="assigned_to" value="DI ASSIGN KE" />
                            <select wire:model.defer="assigned_to"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">-- Pilih --</option>
                                @foreach($penggunas as $pengguna)
                                    <option value="{{ $pengguna->nip }}">
                                        {{ $pengguna->nama_lengkap }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('assigned_to')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="priority" value="PRIORITAS" />
                            <select wire:model.defer="priority"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                            <x-input-error :messages="$errors->get('priority')" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="status" value="STATUS" />
                            <select wire:model.defer="status"
                                class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" />
                        </div>
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="closeModal">
                    Cancel
                </x-secondary-button>
                <x-primary-button wire:click="update" class="ml-2 bg-[#0070C0] hover:bg-[#005B9F]">
                    Update
                </x-primary-button>
            </x-slot>
        </x-modal>

        <!-- ================= MODAL UPLOAD FILE ================= -->
        <x-modal wire:model="confirmUpload">
            <x-slot name="title">
                <span class="text-lg font-semibold text-black">
                    Kirim Hasil Task
                </span>
            </x-slot>
            <x-slot name="content">
                <div class="space-y-4">
                    <div>
                        <x-input-label for="attachment" value="UPLOAD FILE HASIL KERJA" />
                        <x-input type="file" wire:model.defer="attachment" />
                        <x-input-error :messages="$errors->get('attachment')" />
                    </div>

                    <div>
                        <x-input-label for="revision_notes" value="KETERANGAN (Opsional)" />
                        <textarea wire:model.defer="revision_notes" rows="3"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-blue-500"
                            placeholder="Masukkan keterangan tambahan..."></textarea>
                        <x-input-error :messages="$errors->get('revision_notes')" />
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="closeModal">
                    Cancel
                </x-secondary-button>
                <x-primary-button wire:click="uploadFile" class="ml-2 bg-[#0070C0] hover:bg-[#005B9F]">
                    Kirim
                </x-primary-button>
            </x-slot>
        </x-modal>

        <!-- ================= MODAL REJECT TASK ================= -->
        <x-modal wire:model="confirmReject">
            <x-slot name="title">
                <span class="text-lg font-semibold text-red-700">
                    Tolak Task
                </span>
            </x-slot>
            <x-slot name="content">
                <div class="space-y-4">
                    <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                        <p class="text-sm text-red-700">
                            <strong>Peringatan:</strong> Task yang ditolak akan dikembalikan ke staff dengan catatan Anda.
                        </p>
                    </div>

                    <div>
                        <x-input-label for="rejection_note" value="CATATAN PENOLAKAN (Wajib)" />
                        <textarea wire:model.defer="rejection_note" rows="5"
                            class="w-full rounded-lg border border-red-300 px-4 py-2 shadow-sm focus:ring-2 focus:ring-red-500"
                            placeholder="Masukkan alasan mengapa task ini ditolak... (minimal 10 karakter)"></textarea>
                        <x-input-error :messages="$errors->get('rejection_note')" />
                    </div>
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-secondary-button wire:click="closeModal">
                    Cancel
                </x-secondary-button>
                <button wire:click="reject" class="ml-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow">
                    Tolak Task
                </button>
            </x-slot>
        </x-modal>

        {{-- TABLE --}}
        <div class="rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead class="bg-[#0070C0] text-white">
                        <tr class="text-sm uppercase tracking-wide">
                            <th class="px-6 py-4 text-left">No</th>
                            <th class="px-6 py-4 text-left" style="min-width: 180px;">Nama Task</th>
                            <th class="px-6 py-4 text-left" style="min-width: 350px;">Deskripsi</th>
                            <th class="px-6 py-4 text-left" style="min-width: 150px;">Assigned To</th>
                            <th class="px-6 py-4 text-left">Prioritas</th>
                            <th class="px-6 py-4 text-left">Upload File</th>
                            <th class="px-6 py-4 text-left" style="min-width: 180px;">Tanggal Upload</th>
                            <th class="px-6 py-4 text-left" style="min-width: 150px;">Status</th>
                            <th class="px-6 py-4 text-left" style="min-width: 180px;">Verified By</th>
                            <th class="px-6 py-4 text-left" style="min-width: 180px;">Approved By</th>
                            <th class="px-6 py-4 text-center" style="min-width: 450px;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($tasks as $key => $task)
                            <tr class="hover:bg-blue-50 transition duration-200">
                                <td class="px-6 py-4">{{ $tasks->firstItem() + $key }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $task->title }}</td>
                                <td class="px-6 py-4">{{ Str::limit($task->description, 100) }}</td>
                                <td class="px-6 py-4">{{ $task->assignee->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($task->priority == 'low') bg-green-100 text-green-800 
                                        @elseif($task->priority == 'medium') bg-yellow-100 text-yellow-800 
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if(in_array($task->status, ['pending', 'in_progress']) && $task->assigned_to == auth()->user()->nip)
                                            <button wire:click="showUploadFile('{{ $task->id }}')" class="bg-[#0070C0] hover:bg-[#005B9F] text-white px-4 py-2 rounded-lg text-xs font-semibold shadow">
                                                Upload File
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($task->submitted_at)
                                        {{ \Carbon\Carbon::parse($task->submitted_at)->format('d-m-Y H:i') }}
                                    @else
                                        <span class="text-slate-400 text-sm">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <span class="px-2 py-1 text-xs rounded-full inline-block
                                            @if($task->status == 'pending') bg-yellow-100 text-yellow-800 
                                            @elseif($task->status == 'in_progress') bg-blue-100 text-blue-800 
                                            @elseif($task->status == 'submitted') bg-orange-100 text-orange-800 
                                            @elseif($task->status == 'verified') bg-purple-100 text-purple-800 
                                            @elseif($task->status == 'approved') bg-green-100 text-green-800 
                                            @elseif($task->status == 'rejected') bg-red-100 text-red-800 
                                            @else bg-slate-100 text-slate-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>

                                        @if($task->status == 'rejected' && $task->rejection_note)
                                            <div class="text-xs text-red-700 bg-red-50 p-2 rounded border border-red-200">
                                                <strong>Catatan:</strong> {{ $task->rejection_note }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($task->verifier)
                                        <div>{{ $task->verifier->nama_lengkap }}</div>
                                        <div class="text-xs text-slate-500">{{ $task->verified_at ? \Carbon\Carbon::parse($task->verified_at)->format('d M Y H:i') : '-' }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($task->approver)
                                        <div>{{ $task->approver->nama_lengkap }}</div>
                                        <div class="text-xs text-slate-500">{{ $task->approved_at ? \Carbon\Carbon::parse($task->approved_at)->format('d M Y H:i') : '-' }}</div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center items-center gap-2 flex-nowrap">
                                        <button wire:click="edit('{{ $task->id }}')" class="bg-[#0070C0] hover:bg-[#005B9F] text-white px-4 py-2 rounded-lg text-xs font-semibold shadow">
                                            Edit
                                        </button>

                                        @if($task->attachment)
                                            <a href="{{ Storage::url($task->attachment) }}" target="_blank" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow">
                                                Lihat File
                                            </a>
                                        @endif

                                        @if($task->status == 'submitted' && auth()->user()?->isAsmen())
                                            <button wire:click="verifikasi('{{ $task->id }}')" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow">
                                                Verifikasi
                                            </button>
                                            <button wire:click="showReject('{{ $task->id }}')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow">
                                                Tolak
                                            </button>
                                        @endif

                                        @if($task->status == 'verified' && auth()->user()?->isManajer())
                                            <button wire:click="approve('{{ $task->id }}')" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow">
                                                Approve
                                            </button>
                                            <button wire:click="showReject('{{ $task->id }}')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow">
                                                Tolak
                                            </button>
                                        @endif

                                        <button wire:click="confirmDelete('{{ $task->id }}')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-xs font-semibold shadow">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-10 text-slate-500">
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
            {{ $tasks->links() }}
        </div>
    </div>
</div>
