<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex bg-[#071c2f] overflow-hidden">

    <!-- LEFT SIDE -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">

        <!-- Background -->
        <div class="absolute inset-0">

            <img src="{{ asset('images/gambar pelindo.jpg') }}" alt="Pelabuhan" class="w-full h-full object-cover">

            <!-- Overlay -->
            <div class="absolute inset-0 bg-[#031525]/85"></div>

        </div>

        <!-- Glow -->
        <div class="absolute -bottom-52 -left-52 w-[500px] h-[500px] rounded-full bg-cyan-500/10 blur-3xl"></div>

        <!-- Circle -->
        <div class="absolute top-[-100px] right-[-100px] w-[400px] h-[400px] border border-cyan-400/10 rounded-full">
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-between h-full px-16 py-14 text-white">

            <!-- Logo -->
            <div>

                <img src="{{ asset('images/logo_pmt.png') }}" alt="Logo" class="w-72">

            </div>

            <!-- Text -->
            <div class="max-w-xl mb-20">

                <!-- Badge -->
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-cyan-400/20 bg-cyan-400/10 text-cyan-300 text-sm mb-8">

                    <div class="w-2 h-2 rounded-full bg-cyan-400"></div>

                    Internal Company System

                </div>

                <!-- Title -->
                <h1 class="text-5xl font-bold leading-tight mb-6">

                    Sistem Informasi
                    <span class="text-cyan-400">
                        Terminal Petikemas
                    </span>

                </h1>

                <!-- Description -->
                <p class="text-slate-300 text-lg leading-relaxed">

                    Platform internal perusahaan untuk mendukung
                    pengelolaan data operasional, pegawai,
                    pengguna, dan aktivitas sistem secara
                    terintegrasi secara aman dan modern.

                </p>

                <!-- Feature -->
                <div class="mt-10 flex gap-8 text-sm text-slate-400">

                    <div class="flex items-center gap-2">

                        <div class="w-2 h-2 rounded-full bg-cyan-400"></div>

                        Secure Access

                    </div>

                    <div class="flex items-center gap-2">

                        <div class="w-2 h-2 rounded-full bg-cyan-400"></div>

                        Internal Network

                    </div>

                </div>

            </div>

            <!-- Footer -->
            <div class="text-sm text-slate-500">

                © {{ date('Y') }}
                PT Pelindo Terminal Petikemas

            </div>

        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div
        class="w-full lg:w-1/2 flex items-center justify-center px-6 py-10 bg-gradient-to-br from-[#0b2440] via-[#082038] to-[#06182c]">

        <!-- Card -->
        <div
            class="w-full max-w-md bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl p-10 text-white">

            <!-- Mobile Logo -->
            <div class="lg:hidden flex justify-center mb-8">

                <img src="{{ asset('images/logo_pmt.png') }}" alt="Logo" class="w-56">

            </div>

            <!-- Title -->
            <div class="mb-10 text-center">

                <h2 class="text-3xl font-bold mb-2">

                    Selamat Datang

                </h2>

                <p class="text-slate-400 text-sm">

                    Silakan login untuk melanjutkan akses sistem.

                </p>

            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Form -->
            <form wire:submit="login" class="space-y-6">

                <!-- Login -->
                <div>

                    <x-label for="login" value="NIP / Email" class="text-slate-200 mb-2" />

                    <x-text-input wire:model="form.login" id="login" type="text" name="login" required autofocus
                        autocomplete="username" placeholder="Masukkan NIP atau Email"
                        class="w-full rounded-xl border border-slate-700 bg-slate-900/80 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400" />

                    <x-input-error :messages="$errors->get('form.login')" class="mt-2" />

                </div>

                <!-- Password -->
                <div>

                    <x-label for="password" value="Password" class="text-slate-200 mb-2" />

                    <x-text-input wire:model="form.password" id="password" type="password" name="password" required
                        autocomplete="current-password" placeholder="Masukkan Password"
                        class="w-full rounded-xl border border-slate-700 bg-slate-900/80 text-white placeholder:text-slate-500 focus:border-cyan-400 focus:ring-cyan-400" />

                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />

                </div>

                <!-- Remember -->
                <div class="flex items-center justify-between text-sm">

                    <label class="flex items-center gap-2 text-slate-400">

                        <input wire:model="form.remember" type="checkbox"
                            class="rounded border-slate-600 bg-slate-800 text-cyan-500 focus:ring-cyan-500">

                        Remember me

                    </label>

                    @if (Route::has('password.request'))

                        <a href="{{ route('password.request') }}" class="text-cyan-400 hover:text-cyan-300 transition"
                            wire:navigate>
                            Lupa Password?
                        </a>

                    @endif

                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full bg-cyan-500 hover:bg-cyan-400 transition-all duration-300 text-slate-950 font-bold py-4 rounded-xl shadow-lg shadow-cyan-500/20">

                    LOGIN

                </button>

            </form>

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-white/10 text-center text-xs text-slate-500 leading-relaxed">

                Sistem Internal Perusahaan<br>
                PT Pelindo Terminal Petikemas

            </div>

        </div>

    </div>

</div>