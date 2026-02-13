@php
    use App\Enums\RouteNameEnum;
@endphp
<x-layouts::auth>
    <div class="mt-4 flex flex-col gap-6">
        <flux:text class="text-center">
            {{ trans('Please verify your email address by clicking on the link we just emailed to you.') }}
        </flux:text>

        @if (session('status') == 'verification-link-sent')
            <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600">
                {{ trans('A new verification link has been sent to the email address you provided during registration.') }}
            </flux:text>
        @endif

        <div class="flex flex-col items-center justify-between space-y-3">
            <form method="POST" action="{{ route(RouteNameEnum::VERIFICATION_SEND) }}">
                @csrf
                <flux:button type="submit" variant="primary" class="w-full">
                    {{ trans('Resend verification email') }}
                </flux:button>
            </form>

            <form method="POST" action="{{ route(RouteNameEnum::LOGOUT) }}">
                @csrf
                <flux:button variant="ghost" type="submit" class="text-sm cursor-pointer" data-test="logout-button">
                    {{ trans('Log out') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::auth>
