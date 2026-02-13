@php
    use App\Enums\RouteNameEnum;
@endphp
<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="trans('Forgot password')" :description="trans('Enter your email to receive a password reset link')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route(RouteNameEnum::PASSWORD_EMAIL) }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="trans('Email Address')"
                type="email"
                required
                autofocus
                placeholder="email@example.com"
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                {{ trans('Email password reset link') }}
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>{{ trans('Or, return to') }}</span>
            <flux:link :href="route(RouteNameEnum::LOGIN)" wire:navigate>{{ trans('log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
