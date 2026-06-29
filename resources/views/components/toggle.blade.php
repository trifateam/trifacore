@props([
    'label',
    'name',
    'checked' => false,
    'disabled' => false,
])

<div class="flex items-center" x-data="{ checked: {{ old($name, $checked) ? 'true' : 'false' }} }">
    <input type="hidden" name="{{ $name }}" :value="checked ? '1' : '0'">
    <button 
        type="button" 
        role="switch" 
        :aria-checked="checked.toString()" 
        @click="if(!{{ $disabled ? 'true' : 'false' }}) checked = !checked"
        @class([
            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#ff9900] focus:ring-offset-2',
            'opacity-50 cursor-not-allowed' => $disabled
        ])
        :class="checked ? 'bg-[#ff9900]' : 'bg-gray-200'"
        @disabled($disabled)
    >
        <span 
            aria-hidden="true" 
            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white dark:bg-gray-800 shadow ring-0 transition duration-200 ease-in-out"
            :class="checked ? 'translate-x-5' : 'translate-x-0'"
        ></span>
    </button>
    <label class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer" @click="if(!{{ $disabled ? 'true' : 'false' }}) checked = !checked">
        {{ $label }}
    </label>
</div>
