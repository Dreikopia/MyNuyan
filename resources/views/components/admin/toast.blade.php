@if (session('success') || session('error') || session('warning') || session('info'))
    <div id="toast-alert" class="toast toast-bottom toast-end z-50">

        @if (session('success'))
            <div class="alert alert-success text-foreground">
                <span>{{ session('success') }}</span>

                @if (session('undo_id'))
                    <form method="POST" action="{{ route('admin.complaints.unarchive', session('undo_id')) }}">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="btn btn-sm btn-ghost">
                            Undo
                        </button>
                    </form>
                @endif
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning">
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info">
                <span>{{ session('info') }}</span>
            </div>
        @endif

    </div>

    <script>
        setTimeout(() => {
            document.getElementById('toast-alert')?.remove();
        }, 5000);
    </script>
@endif
