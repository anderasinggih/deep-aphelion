<x-app-layout>
    <div class="px-0.1 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-primary">Dashboard Warga</h1>
                <p class="mt-1 text-sm text-base-content/70">Pantau laporan pengaduan yang telah Anda buat</p>
            </div>
            <div>
                <x-button label="Buat Pengaduan" icon="o-plus" class="shadow-sm btn-primary rounded-xl"
                    link="{{ route('pengaduan.create') }}" />
            </div>
        </div>


        <div class="">
            <div class="max-w-7xl mx-auto sm:px-0.1 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <livewire:profile.update-profile-information-form />
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <livewire:profile.update-password-form />
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>