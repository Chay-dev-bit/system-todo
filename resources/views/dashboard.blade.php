<x-app-layout>
    <div class="card-menu flex gap-4 mt-6 px-6">
        <div class="card border-2 p-4 border-blue-300 bg-blue-200 rounded-lg shadow-lg flex-shrink-0">
            <a href="{{ route('kantor') }}" class="flex flex-col items-center text-center">
                <img src="{{ asset('images/social-media.png') }}" alt="Master Data" width="40%">
                <h3 class="text-md mt-2 font-semibold">Struktur Employed</h3>
            </a>
        </div>
        <div class="card border-2 p-4 border-blue-300 bg-blue-200 rounded-lg shadow-lg flex-shrink-0">
            <a href="{{ route('project') }}" class="flex flex-col items-center text-center">
                <img src="{{ asset('images/notebook.png') }}" alt="Transaksi" width="40%">
                <h3 class="text-md mt-2 font-semibold">To Do List</h3>
            </a>
        </div>
    </div>
</x-app-layout>