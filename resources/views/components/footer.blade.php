<footer class="bg-neutral text-neutral-content p-4 mt-1 z-20 relative">
    <div class="container mx-auto max-w-7xl">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8  pt-8">
            {{-- Bagian Kiri: Logo & Info Instansi --}}
            <div class="flex flex-col gap-4 max-w-sm">
                <div class="flex items-center gap-3">
                    {{-- Logo Banyumas --}}
                    <div class="w-12 h-12 flex items-center justify-center">
                        <img src="{{ asset('storage/assets/logobanyumas.png') }}" alt="Logo Banyumas"
                            class="w-full h-full object-contain"
                            onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Lambang_Kabupaten_Banyumas.png/600px-Lambang_Kabupaten_Banyumas.png'">
                    </div>
                    {{-- Logo Kominfo/Lapor --}}
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center p-1.5 shadow-sm">
                        <img src="{{ asset('images/logo-kominfo.png') }}" alt="Logo Lapor"
                            class="w-full h-full object-contain"
                            onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/thumb/1/11/Logo_Kominfo.svg/600px-Logo_Kominfo.svg.png'">
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-sm md:text-base leading-tight">Kembaran Ngadu</h3>
                    <h3 class="font-bold text-sm md:text-base leading-tight">Kecamatan Kembaran</h3>
                    <p class="text-[13px] text-neutral-content/70 mt-2">Jl. Kyai Kembar No. 17, Kembaran, Kec. Kembaran,
                        Kabupaten Banyumas, Jawa Tengah 53182</p>
                </div>
            </div>

            {{-- Bagian Kanan: Sosial Media --}}
            <div class="flex flex-col gap-3 md:items-end">
                <h4 class="font-bold text-sm text-neutral-content/70 uppercase tracking-widest">Social</h4>
                <div class="flex items-center gap-5">
                    {{-- Twitter --}}
                    <a href="#" class="text-neutral-content hover:text-white transition-colors">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                        </svg>
                    </a>
                    {{-- YouTube --}}
                    <a href="#" class="text-neutral-content hover:text-white transition-colors">
                        <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
                        </svg>
                    </a>
                    {{-- Facebook --}}
                    <a href="#" class="text-neutral-content hover:text-white transition-colors">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path
                                d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>