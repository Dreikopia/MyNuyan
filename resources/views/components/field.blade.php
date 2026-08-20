@props(['name', 'label' => null, 'type' => 'text', 'value' => null])

<div class="form-control w-full">

    @if ($label)
        <div class="p-2">
            <label for="{{ $name }}" class="label text-sm">
                <span class="label-text">{{ $label }}</span>
            </label>
        </div>
    @endif

    @if ($type === 'select')
        <select name="{{ $name }}" id="{{ $name }}"
            {{ $attributes->merge([
                'class' => 'select select-bordered w-full',
                ':class' => "{ 'select-error': errors.{$name} }",
            ]) }}>
            {{ $slot }}
        </select>
    @elseif ($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $name }}"
            {{ $attributes->merge([
                'class' => 'textarea textarea-bordered w-full',
                ':class' => "{ 'textarea-error': errors.{$name} }",
            ]) }}>{{ old($name, $value) }}</textarea>
    @else
        <input
            {{ $attributes->merge([
                'type' => $type,
                'name' => $name,
                'id' => $name,
                'value' => old($name, $value),
                'class' => 'input input-bordered w-full',
                ':class' => "{ 'input-error': errors.{$name} }",
            ]) }}>
    @endif

    <template x-if="errors.{{ $name }}">
        <label class="label">
            <span class="label-text-alt text-error" x-text="errors.{{ $name }}[0]"></span>
        </label>
    </template>

</div>
