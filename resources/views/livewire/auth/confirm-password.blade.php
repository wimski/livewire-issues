@php
    use App\Enums\RouteNameEnum;
@endphp
<x-layouts::auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
                :title="trans('Confirm password')"
                :description="trans('This is a secure area of the application. Please confirm your password before continuing.')"
        />

        <x-auth-session-status class="text-center"
                               :status="session('status')"/>

        <form method="POST"
              action="{{ route(RouteNameEnum::PASSWORD_CONFIRM_STORE) }}"
              class="flex flex-col gap-6">
            @csrf

            <flux:input
                    name="password"
                    :label="trans('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="trans('Password')"
                    viewable
            />

            <flux:button variant="primary"
                         type="submit"
                         class="w-full"
                         data-test="confirm-password-button">
                {{ trans('Confirm') }}
            </flux:button>
        </form>
    </div>
</x-layouts::auth>
