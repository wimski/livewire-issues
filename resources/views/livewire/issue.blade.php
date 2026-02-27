<div>
    <h1 class="text-xl mb-5">Issue</h1>
    <flux:select wire:model.live="value" placeholder="Select">
        <flux:select.option value="a">A</flux:select.option>
        <flux:select.option value="b">B</flux:select.option>
    </flux:select>
    <flux:pagination :paginator="$items" />
</div>
