<x-app-layout>
    <div class="w-full max-w-7xl mx-auto px-1.5 lg:px-2 py-8">
        <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-black tracking-tight text-primary">Pengaturan Profil</h1>
                <p class="mt-1 text-sm text-base-content/70">Perbarui informasi profil dan kata sandi akun Anda.</p>
            </div>
        </div>

        <div class="space-y-6">
            <x-card class="border shadow-sm bg-base-100 rounded-2xl border-base-200">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </x-card>

            <x-card class="border shadow-sm bg-base-100 rounded-2xl border-base-200">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>