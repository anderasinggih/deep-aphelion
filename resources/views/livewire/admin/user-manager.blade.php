<div class="px-0.1 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Manajemen Pengguna</h1>
            <p class="mt-1 text-sm text-base-content/70">Kelola data warga, petugas, dan admin sistem</p>
        </div>
        <div>
            <x-button label="Tambah Pengguna" icon="o-user-plus" class="shadow-sm btn-primary rounded-xl"
                wire:click="create" />
        </div>
    </div>

    @if (session()->has('success'))
    <x-alert icon="o-check-circle" class="mb-5 shadow-sm alert-success rounded-xl">
        {{ session('success') }}
    </x-alert>
    @endif

    @if (session()->has('error'))
    <x-alert icon="o-exclamation-triangle" class="mb-5 shadow-sm alert-error rounded-xl">
        {{ session('error') }}
    </x-alert>
    @endif

    <div class="mb-6">
        <x-input placeholder="Cari nama, NIK, atau email..." wire:model.live.debounce.500ms="search"
            icon="o-magnifying-glass" class="w-full max-w-md bg-base-100" />
    </div>

    <div class="border shadow-sm bg-base-100 rounded-2xl border-base-200">
        <div class="overflow-x-auto">
            <table class="table w-full text-xs md:text-sm">
                <thead class="bg-base-200/50 text-base-content/60 text-xs md:text-sm">
                    <tr>
                        <th class="rounded-tl-lg whitespace-nowrap">Nama Identitas</th>
                        <th class="whitespace-nowrap">Kontak</th>
                        <th>Peran (Role)</th>
                        <th class="text-right rounded-tr-lg whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200">
                    @forelse($users as $user)
                    <tr class="transition-colors hover:bg-base-200/30">
                        <td class="text-xs md:text-sm text-base-content whitespace-nowrap">
                            <div class="font-bold">{{ $user->name }}</div>
                            <div class="text-xs opacity-70">NIK: {{ $user->nik }}</div>
                        </td>
                        <td class="text-xs md:text-sm text-base-content/80 whitespace-nowrap">
                            <div class="flex items-center gap-1"><x-icon name="o-envelope" class="w-3.5 h-3.5" /> {{
                                $user->email ?: '-' }}</div>
                            <div class="flex items-center gap-1 mt-0.5"><x-icon name="o-phone" class="w-3.5 h-3.5" /> {{
                                $user->no_wa }}</div>
                        </td>
                        <td class="text-xs md:text-sm text-base-content/80 whitespace-nowrap">
                            @if($user->role === 'admin')
                            <x-badge value="Admin" class="badge-error badge-sm" />
                            @elseif($user->role === 'petugas')
                            <x-badge value="Petugas" class="badge-info badge-sm" />
                            @else
                            <x-badge value="Warga" class="badge-success badge-sm" />
                            @endif
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1 md:gap-2">
                                <x-button icon="o-pencil-square"
                                    class="rounded-lg btn-xs md:btn-sm btn-ghost text-warning hover:bg-warning/10"
                                    tooltip="Edit Pengguna" wire:click="edit({{ $user->id }})" />

                                <x-button icon="o-trash"
                                    class="rounded-lg btn-xs md:btn-sm btn-ghost text-error hover:bg-error/10"
                                    tooltip="Hapus Pengguna" wire:click="delete({{ $user->id }})"
                                    wire:confirm="Yakin ingin menghapus pengguna ini? Semua data terkait (termasuk laporan jika ada) bisa terpengaruh." />
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center border-b border-base-200 text-base-content/50">
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-3 mb-3 rounded-full bg-base-200">
                                    <x-icon name="o-users" class="w-8 h-8 opacity-50 text-base-content/50" />
                                </div>
                                <span class="text-xs md:text-sm font-medium">Data pengguna tidak ditemukan.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-base-200">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Form User -->
    <x-modal wire:model="showModal" title="{{ $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}"
        subtitle="Silakan isi form profil dan hak akses di bawah ini" separator>
        <x-form wire:submit="{{ $isEdit ? 'update' : 'store' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <x-input label="Nama Lengkap" wire:model="name" placeholder="Sesuai KTP" required icon="o-user" />
                <x-input label="NIK" wire:model="nik" type="number" placeholder="16 digit angka" required
                    icon="o-identification" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <x-input label="No WhatsApp" wire:model="no_wa" type="number" placeholder="08..." required
                    icon="o-phone" />
                <x-input label="Alamat Email (Opsional)" wire:model="email" type="email" placeholder="example@mail.com"
                    icon="o-envelope" />
            </div>

            <div class="mb-4">
                <x-select label="Peran (Role)" wire:model="role"
                    :options="[['id' => 'warga', 'name' => 'Warga'], ['id' => 'petugas', 'name' => 'Petugas'], ['id' => 'admin', 'name' => 'Admin']]"
                    option-value="id" option-label="name" required icon="o-shield-check" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <x-input label="{{ $isEdit ? 'Password Baru (Kosongkan jika tidak diubah)' : 'Password' }}"
                    wire:model="password" type="password" required="{{ !$isEdit }}" icon="o-lock-closed" />
                <x-input label="Konfirmasi Password" wire:model="password_confirmation" type="password"
                    required="{{ !$isEdit }}" icon="o-check-circle" />
            </div>
            @if(!$isEdit || $password)
            <p class="text-xs text-base-content/60 pb-2">Minimal 8 karakter, wajib kombinasi huruf dan angka.</p>
            @endif

            <x-slot:actions>
                <div class="flex justify-end gap-3 mt-4">
                    <x-button label="Batal" wire:click="closeModal" class="btn-ghost" />
                    <x-button label="{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Pengguna' }}" type="submit"
                        class="btn-primary" spinner="{{ $isEdit ? 'update' : 'store' }}" />
                </div>
            </x-slot:actions>
        </x-form>
    </x-modal>
    <style>
        /* Sembunyikan panah atas-bawah di input type="number" */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</div>