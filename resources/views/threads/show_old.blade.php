@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/vendor/jquery.atwho.css') }}"> 

    {{-- Ep 81 Add a json_encode here to fetch thread--}}     
@endsection

@section('content')
{{-- <thread-view :data-replies-count="{{ $thread->replies_count  }}" :data-locked="{{ $thread->locked }}" inline-template> --}}
<thread-view :thread = "{{ $thread }}" inline-template>    
    <div class="container">
        <div class="row">
            <div class="col-md-8" v-cloak>
                @include('threads._question')
                <br>
                {{--  In replies component, the count is changed.
                We wanna notify the parent, which be thread: we use custom events, same as reply to replies  --}}
                {{--  <replies :data="{{ $thread->replies }}"   --}}
                <replies
                    @added="repliesCount++"
                    @removed="repliesCount--"></replies>

                {{--  Still have some comments in thread.reply, which need to review  --}}
                {{--  @foreach ($replies as $reply)
                    @include('threads.reply')
                @endforeach

                {{ $replies->links() }}  --}}

                {{--  @if (auth()->check())
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
                @endif  --}}
            </div>

            <div class="col-md-4" v-cloak>
                <div class="card card-default">
                
                    {{--  Careful: when u call a relationship as a property, it gonna perform a SQL-query behind the scenes.
                    So, if u just want the number of replies, do like below $thread->replies()->count() --}}
                    <div class="card-body">
                        <p>
                            This thread was published {{ $thread->created_at->diffForHumans()}} by
                            <a href="">{{$thread->creator->name}}</a>, and currently 
                            {{--  has {{$thread->replies()->count()}} comments.  --}}
                            has <span v-text="repliesCount"></span> {{ str_plural('comment',$thread->replies_count)}}.                        
                        </p>
                        {{-- @if (auth::check()) --}}
                            <p>
                                <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}" v-if="signedIn"></subscribe-button>
                                <button :class="classes(locked)"
                                        v-if="authorize('isAdmin')"
                                        @click="toggleLock"
                                        v-text="locked ? 'Unlock' : 'Lock'"></button>

                                <button :class="classes(pinned)"
                                        v-if="authorize('isAdmin')"
                                        @click="togglePin"
                                        v-text="pinned ? 'Unpin' : 'Pin'"></button>
                            </p>
                        {{-- @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</thread-view>
@endsection
