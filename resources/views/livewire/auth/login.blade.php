@php
    use App\Enums\RouteNameEnum;
@endphp
<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="trans('Log in to your account')" :description="trans('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route(RouteNameEnum::LOGIN_STORE) }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="trans('Email address')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="trans('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="trans('Password')"
                    viewable
                />

                @if (Route::has(RouteNameEnum::PASSWORD_REQUEST->value))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route(RouteNameEnum::PASSWORD_REQUEST)" wire:navigate>
                        {{ trans('Forgot your password?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="trans('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ trans('Log in') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has(RouteNameEnum::REGISTER->value))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ trans('Don\'t have an account?') }}</span>
                <flux:link :href="route(RouteNameEnum::REGISTER)" wire:navigate>{{ trans('Sign up') }}</flux:link>
            </div>
        @endif
    </div>
</x-layouts::auth>
