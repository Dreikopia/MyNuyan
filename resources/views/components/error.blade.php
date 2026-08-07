@props(['name'])

@error($name)
    <p class="text-sm error">{{ $message }}</p>
@enderror
