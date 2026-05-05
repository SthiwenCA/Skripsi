@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-[#4a3219] focus:ring-[#4a3219] rounded-full shadow-sm px-4 py-2.5 w-full text-gray-900 transition duration-150']) !!}>