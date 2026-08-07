@extends('layouts.admin')

@section('content')
    <div class="overflow-x-auto">
        <table class="table">
            <!-- head -->
            <thead>
                <tr>
                    <th>News</th>
                    <th>Article</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <th>{{ $category->name }}</th>
                        <td>Cy Ganderton</td>
                        <td>Quality Control Specialist</td>
                        <td>Blue</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500">
                            No categries found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
