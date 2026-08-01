@props(['name', 'label' => null, 'type' => 'text'])

<div>
    @if ($label)
        <label for="{{ $name }}" class="label">
            {{ $label }}
        </label>
    @endif

    <input
        {{ $attributes->merge([
            'type' => $type,
            'name' => $name,
            'id' => $name,
            'value' => old($name),
            'class' => 'input' . ($errors->has($name) ? 'input-error' : ''),
        ]) }}>

</div>

@error($name)
    <span class="error">
        {{ $message }}
    </span>
@enderror
