{{-- resources/views/admin/news/partials/_table.blade.php --}}
<div class="overflow-x-auto bg-base-100 border border-base-300 rounded-lg">
    <table class="table">
        <thead>
            <tr class="text-xs uppercase text-base-content/50">
                <th class="w-16"></th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Last updated</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($news as $post)
                <tr @click="openDrawer('{{ route('admin.news.show', $post) }}')"
                    class="hover:bg-base-200/60 cursor-pointer transition-colors">
                    <td>
                        @if ($post->image_path)
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}"
                                class="w-10 h-10 object-cover rounded-md">
                        @else
                            <div class="w-10 h-10 rounded-md bg-base-200"></div>
                        @endif
                    </td>
                    <td class="font-medium max-w-xs truncate">
                        {{ $post->title }}
                    </td>
                    <td>
                        <span class="badge badge-ghost badge-sm">
                            {{ $post->category->name }}
                        </span>
                    </td>
                    <td>
                        <span
                            class="badge badge-sm {{ $post->status?->value === 'published' ? 'badge-success' : 'badge-ghost' }}">
                            {{ ucfirst($post->status?->value ?? 'draft') }}
                        </span>
                    </td>
                    <td class="text-sm text-base-content/60">
                        {{ $post->updated_at->diffForHumans() }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-sm text-base-content/50 py-10">
                        No news found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
