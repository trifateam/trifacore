@props([
    'headers' => [],
    'striped' => true,
    'hoverable' => true,
    'compact' => false,
])

<div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $header)
                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        {{ $header }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200 {{ $striped ? 'even-zebra' : '' }} {{ $hoverable ? 'table-hoverable' : '' }}">
            {{ $slot }}
        </tbody>
    </table>
    
    @isset($empty)
        @if(trim((string) $slot) === '')
            {{ $empty }}
        @endif
    @endisset
</div>

<style>
    .even-zebra tr:nth-child(even) {
        background-color: #f9fafb;
    }
    .table-hoverable tr:hover {
        background-color: #eef2ff80;
    }
</style>
