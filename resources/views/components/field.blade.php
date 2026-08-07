@props(['name', 'label' => null, 'type' => 'text', 'value' => null])

<div class="form-control w-full">

    @if ($label)
        <label for="{{ $name }}" class="label">
            <span class="label-text">
                {{ $label }}
            </span>
        </label>
    @endif

    @if ($type === 'select')
        <select name="{{ $name }}" id="{{ $name }}"
            {{ $attributes->merge([
                'class' => 'select select-bordered w-full' . ($errors->has($name) ? ' select-error' : ''),
            ]) }}>
            {{ $slot }}
        </select>
    @else
        <input
            {{ $attributes->merge([
                'type' => $type,
                'name' => $name,
                'id' => $name,
                'value' => old($name, $value),
                'class' => 'input input-bordered w-full' . ($errors->has($name) ? ' input-error' : ''),
            ]) }}>
    @endif

    @error($name)
        <label class="label">
            <span class="label-text-alt text-error">
                {{ $message }}
            </span>
        </label>
    @enderror

</div>
