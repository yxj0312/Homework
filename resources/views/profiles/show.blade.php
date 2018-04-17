@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1>{{ $profileUser->name }}
                        {{--  <small>Since {{ $profileUser->created_at->diffForHumans()}}</small>  --}}
                    </h1>

                    @can('update', $profileUser)
                        <form method="POST" action="{{ route('avatar', $profileUser) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <input type="file" name="avatar">
                            <button type="submit" class="btn btn-primary">Add Avatar</button>
                        </form>
                    @endcan

                    <img src="{{ $$profileUser->avatar() }}" width="50"  height="50">
                </div>
                {{--  @foreach ($threads as $thread)  --}}
                {{--  @foreach ($activities as $activity)  --}}
                @forelse ($activities as $date => $activity)
                    <h3 class="page-header"> {{ $date }}</h3>
                    @foreach ($activity as $record)
                    @if (view()->exists("profiles.activities.{$record->type}"))
                        @include("profiles.activities.{$record->type}", ['activity' => $record])
                    @endif
                    @endforeach
                @empty
                    <p>There is no activity for this user yet.</p>          
                @endforelse
                {{--  {{ $threads->links()}}  --}}
            </div>
        </div>
    </div>
@endsection