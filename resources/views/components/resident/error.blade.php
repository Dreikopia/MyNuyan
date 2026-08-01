@props(['name'])

@error($name)
    <p {{ $attributes->merge(['class' => 'text-error text-sm mt-1']) }}>
        {{ $message }}
    </p>
@enderror
