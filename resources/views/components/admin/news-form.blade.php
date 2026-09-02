@props(['categories', 'post' => null])

@php
    $isEditing = $post !== null;
    $modalId = $isEditing ? 'EditNews' . $post->id : 'CreateNews';
    $modalName = $isEditing ? 'Edit' : 'Create';
@endphp

<x-modal :id="$modalId" :name="$modalName" boxClass="max-w-2xl bg-surface" :trigger="!$isEditing" class="btn btn-primary">

    <form x-data="{
        errors: {},
        submitting: false,
        removeImage: false,
        imagePreview: null,
        dragging: false,
    
        handleImage(event) {
            const file = event.target.files[0];
    
            if (!file) {
                this.imagePreview = null;
                return;
            }
    
            this.imagePreview = URL.createObjectURL(file);
        },
    
        handleDrop(event) {
            this.dragging = false;
            const file = event.dataTransfer.files[0];
    
            if (!file) return;
    
            this.$refs.imageInput.files = event.dataTransfer.files;
            this.imagePreview = URL.createObjectURL(file);
        },
    
        undoRemove() {
            this.removeImage = false;
            this.imagePreview = null;
            this.$refs.imageInput.value = '';
        },
    
        removeSelectedImage() {
            this.imagePreview = null;
            this.$refs.imageInput.value = '';
        },
    
        async submitForm(e) {
            this.submitting = true;
            this.errors = {};
    
            try {
                const res = await fetch(e.target.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: new FormData(e.target),
                });
    
                if (res.status === 422) {
                    const data = await res.json();
                    this.errors = data.errors;
                    return;
                }
    
                if (res.ok) {
                    window.location.reload();
                }
            } catch (error) {
                console.error(error);
            } finally {
                this.submitting = false;
            }
        }
    }" @submit.prevent="submitForm" @submit="console.log('FORM SUBMITTED')" method="POST"
        enctype="multipart/form-data"
        action="{{ $isEditing ? route('admin.news.update', $post) : route('admin.news.store') }}">

        @csrf

        @if ($isEditing)
            @method('PATCH')
        @endif

        <div class="flex flex-col">
            <div class="space-y-5">

                <x-field name="title" label="Title" placeholder="News Title" :value="$post->title ?? old('title')" />

                <div class="space-y-3">
                    <label class="label">
                        <span class="label-text text-sm">
                            Featured Image
                        </span>
                    </label>

                    {{-- Existing image (edit mode, nothing removed/replaced yet) --}}
                    @if ($isEditing && $post->image_path)
                        <div x-show="!removeImage && !imagePreview" x-cloak class="relative group">
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}"
                                class="w-full h-48 object-cover rounded-lg border border-base-content/10">

                            <div
                                class="absolute inset-0 flex items-end justify-end gap-2 p-3 rounded-lg bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" @click="$refs.imageInput.click()" class="btn btn-sm btn-neutral">
                                    Replace
                                </button>
                                <button type="button" @click="removeImage = true" class="btn btn-sm btn-error">
                                    Remove
                                </button>
                            </div>
                        </div>

                        <div x-show="removeImage && !imagePreview" x-cloak class="alert alert-warning">
                            <div class="flex-1">
                                <p class="font-medium">
                                    Image will be removed
                                </p>

                                <p class="text-sm opacity-70">
                                    Choose a new image below or undo this action.
                                </p>
                            </div>

                            <button type="button" @click="undoRemove()" class="btn btn-sm">
                                <x-icons.undo />
                                Undo
                            </button>
                        </div>
                    @endif

                    {{-- New image preview --}}
                    <div x-show="imagePreview" x-cloak class="relative group">
                        <img :src="imagePreview" alt="New image preview"
                            class="w-full h-48 object-cover rounded-lg border border-base-content/10">

                        <div
                            class="absolute inset-0 flex items-end justify-between gap-2 p-3 rounded-lg bg-linear-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity">

                            <button type="button" @click="removeSelectedImage()" class="btn btn-sm btn-neutral">
                                Choose Another
                            </button>
                        </div>
                    </div>

                    {{-- Dashed dropzone: only shown when there is nothing to preview yet --}}
                    <div @if ($isEditing && $post->image_path) x-show="removeImage && !imagePreview"
                        @else x-show="!imagePreview" @endif
                        x-cloak @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                        @drop.prevent="handleDrop($event)" @click="$refs.imageInput.click()" :class="dragging
                            ? 'border-primary bg-primary/5'
                            : 'border-base-content/20 hover:border-primary/60 hover:bg-base-200/40'"
                        class="flex flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed p-8 text-center cursor-pointer transition-colors">

                        <x-icons.upload class="w-8 h-8 text-base-content/40" />

                        <p class="text-sm font-medium">
                            <span class="text-primary">Click to upload</span> or drag and drop
                        </p>

                        <p class="text-xs text-base-content/50">
                            JPG, PNG, or WebP
                        </p>

                        <input x-ref="imageInput" type="file" name="image" accept="image/jpeg,image/png,image/webp"
                            @change="handleImage" class="hidden">
                    </div>

                    @if ($isEditing && $post->image_path)
                        <input type="hidden" name="remove_image" :value="removeImage ? '1' : '0'">
                    @endif
                </div>

                <div class="flex justify-between gap-4">

                    <x-field name="category" label="Category" type="select">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(($post?->news_category_id ?? old('category')) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </x-field>

                    <x-field name="status" label="Status" type="select">
                        @foreach (App\Enums\NewsStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(($post->status?->value ?? old('status')) === $status->value)>
                                {{ ucfirst($status->value) }}
                            </option>
                        @endforeach
                    </x-field>

                </div>

                <x-field name="description" type="textarea" label="Content" placeholder="Write the article content here"
                    :value="$post->description ?? old('description')" />

                <div class="flex gap-2 mt-4">

                    <button type="button" class="btn btn-outline flex-1"
                        onclick="document.getElementById('{{ $modalId }}').close()">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary flex-1" :disabled="submitting">
                        <span x-show="!submitting">
                            {{ $isEditing ? 'Save Changes' : 'Publish' }}
                        </span>

                        <span x-show="submitting">
                            Saving...
                        </span>
                    </button>

                </div>

            </div>
        </div>

    </form>

</x-modal>
