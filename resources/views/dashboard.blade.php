<x-layouts::app :title="trans('Dashboard')">
    <h1 class="text-xl mb-5">Description</h1>
    <ol class="list-decimal ps-4 mb-3">
        <li>Click on the 'Issue' menu item.</li>
        <li>Select 'A' or 'B' from the select box.</li>
        <li>Select a page from the pagination.</li>
        <li>Click on the 'Dashboard' menu item.</li>
        <li>Click on the 'Issue' menu item.</li>
    </ol>
    <p>When returning to the 'Issue' page the select value is restored in both the select box and the query string.</p>
    <p>The pagination is only restored in the interface, but not in the query string.</p>
</x-layouts::app>
