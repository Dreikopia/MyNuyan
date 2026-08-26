@props(['id', 'name' => '', 'boxClass' => '', 'trigger' => true])

@if ($trigger)
    <button
        {{ $attributes->merge([
            'type' => 'button',
            'class' => 'btn btn-primary btn-sm rounded-sm',
        ]) }}
        onclick="document.getElementById('{{ $id }}').showModal()">
        @if (isset($triggerSlot))
            {{ $triggerSlot }}
        @else
            {{ $name }}
        @endif
    </button>
@endif

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
