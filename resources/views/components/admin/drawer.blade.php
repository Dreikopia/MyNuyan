@props(['id', 'title' => null])

<div class="drawer drawer-end">
    <input id="{{ $id }}" type="checkbox" class="drawer-toggle" />

    <div class="drawer-side z-50">
        <label for="{{ $id }}" class="drawer-overlay"></label>

        <div class="bg-surface h-full w-full max-w-xl lg:max-w-2xl flex flex-col">

            <div class="flex items-center justify-between p-6 pb-3 border-b border-background">
                <div class="flex items-center gap-2">
                    <label for="{{ $id }}" class="cursor-pointer">
                        <x-icons.panel-right />
                    </label>

                    <h3 class="text-lg font-bold">
                        {{ $title }}
                    </h3>
                </div>

                {{ $header ?? '' }}
            </div>

            <div class="flex-1 overflow-y-auto px-6 pt-4 space-y-5">
                {{ $slot }}
            </div>

        </div>
    </div>
</div>
