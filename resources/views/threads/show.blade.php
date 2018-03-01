@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">
                    <a href="#">{{ $thread->creator->name }}</a> posted:
                    {{ $thread->title }}
                </div>
                
                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-md-8">            
            @foreach ($thread->replies as $reply)
                @include('threads.reply')
            @endforeach
        </div>
    </div>
    @if (auth()->check())
        <br>
        <div class="row justify-content-center">
            <div class="col-md-8">            
                <form action={{ route('threads.replies',['thread'=>$thread->id])}} method="POST">
                    {{ csrf_field() }}
                    <label for="body">Body:</label>
                    <textarea name="body" id="body" cols="30" rows="5" class="form-control" placeholder="Have something to say?"></textarea>
                    <br>
                    <button type="submit" class="btn btn-default">Post</button>
                </form>
            </div>
        </div>
    @else
        <br>
        <p class="text-center">Please <a href={{route('login')}}>sign in</a> to participate in this discussion.</p>
    @endif
</div>
@endsection
