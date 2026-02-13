@php
    use App\Enums\RouteNameEnum;
@endphp
<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="trans('Create an account')" :description="trans('Enter your details below to create your account')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route(RouteNameEnum::REGISTER_STORE) }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="trans('Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="trans('Full name')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="trans('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="trans('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="trans('Password')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="trans('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="trans('Confirm password')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ trans('Create account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ trans('Already have an account?') }}</span>
            <flux:link :href="route(RouteNameEnum::LOGIN)" wire:navigate>{{ trans('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
