<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ trans('Password Settings') }}</flux:heading>

    <x-settings.layout :heading="trans('Update password')" :subheading="trans('Ensure your account is using a long, random password to stay secure')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="trans('Current password')"
                type="password"
                required
                autocomplete="current-password"
            />
            <flux:input
                wire:model="password"
                :label="trans('New password')"
                type="password"
                required
                autocomplete="new-password"
            />
            <flux:input
                wire:model="password_confirmation"
                :label="trans('Confirm Password')"
                type="password"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ trans('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ trans('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
