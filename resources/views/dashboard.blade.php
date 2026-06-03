<x-app-layout>

    <div class="px-4 sm:px-6 lg:px-8 py-6">

        {{-- Card Menu --}}
        <div class="
            grid
            grid-cols-1
            sm:grid-cols-2
            gap-4
            md:gap-6
        ">

            {{-- Card Struktur Employed --}}
            <div class="
                border-2
                border-blue-300
                bg-blue-200
                rounded-xl
                shadow-lg
                hover:shadow-xl
                transition
                duration-300
            ">
                <a href="{{ route('kantor') }}" class="
                        flex flex-col items-center justify-center
                        text-center
                        p-5 sm:p-6
                        h-full
                    ">

                    <img src="{{ asset('images/social-media.png') }}" alt="Master Data"
                        class="w-20 sm:w-24 md:w-28 lg:w-32 h-auto">

                    <h3 class="text-sm sm:text-base md:text-lg mt-3 font-semibold text-slate-800">
                        Struktur Employed
                    </h3>

                </a>
            </div>

            {{-- Card To Do List --}}
            <div class="
                border-2
                border-blue-300
                bg-blue-200
                rounded-xl
                shadow-lg
                hover:shadow-xl
                transition
                duration-300
            ">
                <a href="{{ route('project') }}" class="
                        flex flex-col items-center justify-center
                        text-center
                        p-5 sm:p-6
                        h-full
                    ">

                    <img src="{{ asset('images/notebook.png') }}" alt="Transaksi"
                        class="w-20 sm:w-24 md:w-28 lg:w-32 h-auto">

                    <h3 class="text-sm sm:text-base md:text-lg mt-3 font-semibold text-slate-800">
                        To Do List
                    </h3>

                </a>
            </div>

        </div>

    </div>

</x-app-layout>