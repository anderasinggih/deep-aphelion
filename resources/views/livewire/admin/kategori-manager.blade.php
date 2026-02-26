<div class="px-0.1 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-black tracking-tight text-primary">Manajemen Kategori</h1>
            <p class="mt-1 text-sm text-base-content/70">Kelola data set kategori pengaduan dan target SLA (Hari)</p>
        </div>
        <div>
            <x-button label="Tambah Kategori" icon="o-plus" class="shadow-sm btn-primary rounded-xl"
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
        <x-input placeholder="Cari kategori..." wire:model.live.debounce.500ms="search" icon="o-magnifying-glass"
            class="w-full max-w-md bg-base-100" />
    </div>

    <div class="border shadow-sm bg-base-100 rounded-2xl border-base-200">
        <div class="overflow-x-auto">
            <table class="table w-full text-xs md:text-sm">
                <thead class="bg-base-200/50 text-base-content/60 text-xs md:text-sm">
                    <tr>
                        <th class="rounded-tl-lg whitespace-nowrap">No</th>
                        <th class="whitespace-nowrap">Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th class="whitespace-nowrap">SLA (Hari)</th>
                        <th class="text-right rounded-tr-lg whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-base-200">
                    @forelse($kategoris as $index => $kategori)
                    <tr class="transition-colors hover:bg-base-200/30">
                        <td class="text-xs md:text-sm whitespace-nowrap text-base-content/80">
                            {{ $kategoris->firstItem() + $index }}
                        </td>
                        <td class="text-xs md:text-sm font-bold text-base-content whitespace-nowrap">
                            {{ $kategori->nama }}
                        </td>
                        <td class="text-xs md:text-sm text-base-content/80">
                            {{ $kategori->deskripsi ?: '-' }}
                        </td>
                        <td class="text-xs md:text-sm text-base-content/80 whitespace-nowrap">
                            <span class="font-semibold">{{ $kategori->sla_hari }}</span> Hari
                        </td>
                        <td class="text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1 md:gap-2">
                                <x-button icon="o-pencil-square"
                                    class="rounded-lg btn-xs md:btn-sm btn-ghost text-warning hover:bg-warning/10"
                                    tooltip="Edit Kategori" wire:click="edit({{ $kategori->id }})" />

                                <x-button icon="o-trash"
                                    class="rounded-lg btn-xs md:btn-sm btn-ghost text-error hover:bg-error/10"
                                    tooltip="Hapus Kategori" wire:click="delete({{ $kategori->id }})"
                                    wire:confirm="Yakin ingin menghapus kategori ini? Data yang sudah dihapus tidak bisa dikembalikan." />
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center border-b border-base-200 text-base-content/50">
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-3 mb-3 rounded-full bg-base-200">
                                    <x-icon name="o-folder-open" class="w-8 h-8 opacity-50 text-base-content/50" />
                                </div>
                                <span class="text-xs md:text-sm font-medium">Data kategori tidak ditemukan.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-base-200">
            {{ $kategoris->links() }}
        </div>
    </div>

    <!-- Modal Form Kategori -->
    <x-modal wire:model="showModal" title="{{ $isEdit ? 'Edit Kategori' : 'Tambah Kategori Baru' }}"
        subtitle="Silakan isi detail kategori di bawah ini" separator>
        <x-form wire:submit="{{ $isEdit ? 'update' : 'store' }}">
            <div class="space-y-4">
                <x-input label="Nama Kategori" wire:model="nama" placeholder="Contoh: Infrastruktur" required
                    icon="o-tag" />

                <x-input label="Target SLA (Hari)" wire:model="sla_hari" type="number" min="1" placeholder="Contoh: 3"
                    required hint="Estimasi waktu maksimal penyelesaian laporan dalam hari" icon="o-clock" />

                <x-textarea label="Deskripsi (Opsional)" wire:model="deskripsi"
                    placeholder="Penjelasan singkat mengenai kategori ini..." rows="3" />
            </div>

            <x-slot:actions>
                <div class="flex justify-end gap-3 mt-4">
                    <x-button label="Batal" wire:click="closeModal" class="btn-ghost" />
                    <x-button label="{{ $isEdit ? 'Simpan Perubahan' : 'Tambah Kategori' }}" type="submit"
                        class="btn-primary" spinner="{{ $isEdit ? 'update' : 'store' }}" />
                </div>
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>