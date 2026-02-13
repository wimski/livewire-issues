<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ trans('Appearance Settings') }}</flux:heading>

    <x-settings.layout :heading="trans('Appearance')" :subheading=" trans('Update the appearance settings for your account')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ trans('Light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ trans('Dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ trans('System') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</section>
