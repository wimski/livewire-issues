<x-layouts::app :title="trans('Dashboard')">
    <h1 class="text-xl mb-5">Description</h1>
    <ol class="list-decimal ps-4 mb-3">
        <li>Click on the 'Issue' menu item.</li>
        <li>Select 'A' or 'B' from the select box.</li>
        <li>Click on the 'Dashboard' menu item.</li>
        <li>Click on the 'Issue' menu item.</li>
    </ol>
    <p>When initially selecting the value on the 'Issue' page the query string is updated accordingly.</p>
    <p>When subsequently returning to the 'Issue' page the value is still selected, but the query string is not updated.</p>
    <p class="mt-3">Even when you would add <code class="font-mono dark:bg-zinc-900 p-1 rounded-sm">keep: true</code>
        to the <code class="font-mono dark:bg-zinc-900 p-1 rounded-sm">#[Url]</code> attribute, the issue persists.
    <br>It will show an empty value in the query string, although a value is selected.</p>
</x-layouts::app>
