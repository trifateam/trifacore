@props([
    'headers' => [],
    'striped' => true,
    'hoverable' => true,
    'compact' => false,
])

<div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50 dark:bg-gray-700/50">
            @isset($thead)
                {{ $thead }}
            @else
                <tr>
                    @foreach($headers as $header)
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            @endisset
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700 {{ $striped ? 'even-zebra' : '' }} {{ $hoverable ? 'table-hoverable' : '' }}">
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
        background-color: rgba(255,153,0,0.05);
    }
</style>
