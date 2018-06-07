@extends('admin.layout.app')

@section('administration-content')

<p>
    <a class="btn btn-sm btn-default" href="{{ route('admin.channels.create') }}">
        New Channel<span class="fa fa-cog"></span>
    </a>
</p>

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Slug</th>
            <th>Description</th>
            <th>Threads</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse($channels as $channel)
        <tr class="{{ $channel->archived ? 'table-danger' : '' }}">
            <td>{{$channel->name}}</td>
            <td>{{$channel->slug}}</td>
            <td>{{$channel->description}}</td>
            <td>{{$channel->threads_count}}</td>
            <td>
                <a href="/admin/channels/{{ $channel->slug }}/edit" class="btn btn-default btn-xs">Edit</a>
            </td>
        </tr>
        @empty
        <tr>
            <td>Nothing here.</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection