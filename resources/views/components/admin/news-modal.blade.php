@props(['categories', 'post' => null])

@php
    $isEditing = $post !== null;
    $modalId = $isEditing ? 'EditNews' . $post->id : 'CreateNews';
    $modalName = $isEditing ? 'Edit' : 'Create';
@endphp

<x-modal :id="$modalId" :name="$modalName" boxClass="max-w-2xl" :trigger="!$isEditing" class="btn btn-primary">

    <form x-data="{
        errors: {},
        submitting: false,
        removeImage: false,
        imagePreview: null,
    
        handleImage(event) {
            const file = event.target.files[0];
    
            if (!file) {
                this.imagePreview = null;
                return;
            }
    
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
    }" @submit.prevent="submitForm" method="POST" enctype="multipart/form-data"
        action="{{ $isEditing ? route('admin.news.update', $post) : route('admin.news.store') }}">

        @csrf

        @if ($isEditing)
            @method('PATCH')
        @endif

        <div class="flex flex-col">
            <div class="space-y-4">

                <x-field name="title" label="Title" placeholder="News Title" :value="$post->title ?? old('title')" />

                <div class="space-y-3">
                    <label class="label">
                        <span class="label-text font-medium">
                            Featured Image
                        </span>
                    </label>

                    @if ($isEditing && $post->image_path)
                        <div x-show="!removeImage && !imagePreview" x-cloak class="space-y-3">
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}"
                                class="w-full h-48 object-cover rounded-lg">

                            <button type="button" @click="removeImage = true" class="btn btn-error btn-outline w-full">
                                Remove Image
                            </button>

                            <p class="text-xs text-base-content/60 text-center">
                                Remove the current image to upload a replacement.
                            </p>
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

                    <div x-show="imagePreview" x-cloak class="space-y-3">
                        <img :src="imagePreview" alt="New image preview" class="w-full h-48 object-cover rounded-lg">

                        <div class="flex items-center justify-between">
                            <span class="text-sm text-success font-medium">
                                New image selected
                            </span>

                            <button type="button" @click="removeSelectedImage()" class="btn btn-sm btn-outline">
                                Choose Another
                            </button>
                        </div>
                    </div>

                    <div @if ($isEditing && $post->image_path) x-show="removeImage" @endif x-cloak class="space-y-2">

                        <input x-ref="imageInput" type="file" name="image" accept="image/jpeg,image/png,image/webp"
                            @change="handleImage" class="file-input file-input-bordered w-full">

                        <p class="text-xs text-base-content/60">
                            JPG, PNG, or WebP.
                        </p>
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
