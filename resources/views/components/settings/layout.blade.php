@php
    use App\Enums\RouteNameEnum;
@endphp
<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist aria-label="{{ trans('Settings') }}">
            <flux:navlist.item :href="route(RouteNameEnum::PROFILE_EDIT)" wire:navigate>{{ trans('Profile') }}</flux:navlist.item>
            <flux:navlist.item :href="route(RouteNameEnum::USER_PASSWORD_EDIT)" wire:navigate>{{ trans('Password') }}</flux:navlist.item>
            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <flux:navlist.item :href="route(RouteNameEnum::TWO_FACTOR_SHOW)" wire:navigate>{{ trans('Two-Factor Auth') }}</flux:navlist.item>
            @endif
            <flux:navlist.item :href="route(RouteNameEnum::APPEARANCE_EDIT)" wire:navigate>{{ trans('Appearance') }}</flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
