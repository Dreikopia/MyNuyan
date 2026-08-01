@props(['name', 'label' => false, 'type' => 'text', 'preserveValue' => true, 'value' => null])

<div class="space-y-2">

    @if ($label)
        <label for="{{ $name }}" class="label block">{{ $label }}</label>
    @endif

    @if ($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $name }}" class="textarea focus:ring-2 focus:ring-primary"
            {{ $attributes }}>{{ old($name, $value) }}</textarea>
    @else
        <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" class="input w-full"
            value="{{ $preserveValue ? old($name, $value) : '' }}" {{ $attributes }}>
    @endif

    <div>
        <x-admin.error :name="$name" />
    </div>

</div>
