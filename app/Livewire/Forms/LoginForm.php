<?php

namespace App\Livewire\Forms;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    | Bisa login menggunakan:
    | - NIP
    | - Email
    |--------------------------------------------------------------------------
    */

    #[Validate('required|string')]
    public string $login = '';

    /*
    |--------------------------------------------------------------------------
    | PASSWORD
    |--------------------------------------------------------------------------
    */

    #[Validate('required|string')]
    public string $password = '';

    /*
    |--------------------------------------------------------------------------
    | REMEMBER ME
    |--------------------------------------------------------------------------
    */

    #[Validate('boolean')]
    public bool $remember = false;

    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATE
    |--------------------------------------------------------------------------
    */

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        /*
        |--------------------------------------------------------------------------
        | DETEKSI LOGIN
        |--------------------------------------------------------------------------
        | Jika format email -> login pakai email
        | Jika bukan -> login pakai nip
        |--------------------------------------------------------------------------
        */

        $field = filter_var(
            $this->login,
            FILTER_VALIDATE_EMAIL
        )
            ? 'email'
            : 'nip';

        /*
        |--------------------------------------------------------------------------
        | ATTEMPT LOGIN
        |--------------------------------------------------------------------------
        */

        if (
            !Auth::attempt([

                $field => $this->login,

                'password' => $this->password,

                'aktif' => '1',

            ], $this->remember)
        ) {

            RateLimiter::hit(
                $this->throttleKey()
            );

            throw ValidationException::withMessages([

                'form.login' => trans('auth.failed'),

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CLEAR LIMITER
        |--------------------------------------------------------------------------
        */

        RateLimiter::clear(
            $this->throttleKey()
        );

        auth()->user()->update([
            'last_login' => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RATE LIMIT
    |--------------------------------------------------------------------------
    */

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (
            !RateLimiter::tooManyAttempts(
                $this->throttleKey(),
                5
            )
        ) {

            return;
        }

        event(
            new Lockout(request())
        );

        $seconds = RateLimiter::availableIn(
            $this->throttleKey()
        );

        throw ValidationException::withMessages([

            'form.login' => trans('auth.throttle', [

                'seconds' => $seconds,

                'minutes' => ceil($seconds / 60),

            ]),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | THROTTLE KEY
    |--------------------------------------------------------------------------
    */

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(

            Str::lower($this->login)

            . '|' .

            request()->ip()

        );
    }
}
