@props([
    'headers' => [],
    'striped' => true,
    'hoverable' => true,
    'compact' => false,
])

<div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm custom-table-wrapper">
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
    
    /* Enforce Global Padding and Text Wrapping for all Table Data */
    .custom-table-wrapper tbody td {
        padding: 0.75rem 1rem !important; /* Setara dengan px-4 py-3 */
        max-width: 20rem; /* Batasi lebar maksimal (setara max-w-xs) */
        white-space: normal; /* Biarkan teks membungkus ke bawah */
        word-break: break-word; /* Potong kata jika terlalu panjang */
    }

    /* Pengecualian untuk kolom yang sengaja dibuat nowrap (seperti kolom Aksi/Tombol) */
    .custom-table-wrapper tbody td.whitespace-nowrap {
        white-space: nowrap !important;
    }
</style>
