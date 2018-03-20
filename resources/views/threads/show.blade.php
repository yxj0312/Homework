@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">
                    <div class="level">
                        <span class="flex">
                            <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                            {{ $thread->title }}
                        </span>
                        
                        <form action="{{ $thread->path() }}" method="POST">
                            {{ csrf_field() }}
                            {{ method_field('DELETE')}}

                            <button type="submit" class="btn btn-link">Delete Thread</button>
                        </form>
                    </div>
                </div>
                
                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>
            <br>
            @foreach ($replies as $reply)
                @include('threads.reply')
            @endforeach

            {{ $replies->links() }}

            @if (auth()->check())
                <form action={{ route('threads.replies',['channel'=>$thread->channel->slug,'thread'=>$thread->id])}} method="POST">
                    {{ csrf_field() }}
                    <label for="body">Body:</label>
                    <textarea name="body" id="body" cols="30" rows="5" class="form-control" placeholder="Have something to say?"></textarea>
                    <br>
                    <button type="submit" class="btn btn-default">Post</button>
                </form>
            @else
                <br>
                <p class="text-center">Please <a href={{route('login')}}>sign in</a> to participate in this discussion.</p>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card card-default">
               
                {{--  Careful: when u call a relationship as a property, it gonna perform a SQL-query behind the scenes.
                So, if u just want the number of replies, do like below $thread->replies()->count() --}}
                <div class="card-body">
                    <p>
                        This thread was published {{ $thread->created_at->diffForHumans()}} by
                        <a href="">{{$thread->creator->name}}</a>, and currently 
                        {{--  has {{$thread->replies()->count()}} comments.  --}}
                        has {{$thread->replies_count}} {{ str_plural('comment',$thread->replies_count)}}.                        
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
