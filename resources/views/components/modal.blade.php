@props(['id', 'name', 'boxClass' => ''])

<button {{ $attributes->merge(['class' => 'btn btn-primary btn-sm rounded-sm']) }}
    onclick="document.getElementById('{{ $id }}').showModal()">
    {{ $name }}
</button>

<dialog id="{{ $id }}" class="modal min-h-50">
    <div @class(['modal-box', $boxClass])>
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">
                ✕
            </button>
        </form>

        {{ $slot }}
    </div>
</dialog>
