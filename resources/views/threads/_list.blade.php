@forelse ($threads as $thread)
    <div class="card card-default">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4>
                        <a href={{route( 'threads.show',[ 'channel'=>$thread->channel->slug,'thread'=>$thread->id])}}>
                                        @if (auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                            <strong>
                                                {{ $thread->title }}
                                            </strong>
                                        @else
                                            {{ $thread->title }}            
                                        @endif
                                        </a> {{-- <a href={{$thread->path()}}>{{ $thread->title }}</a> --}}
                    </h4>
                    <h5>
                        Posted By: <a href="{{ route('profile', $thread->creator)}}">{{ $thread->creator->name }}</a>
                    </h5>
                </div>
                <a href="{{$thread->path() }}">{{ $thread->replies_count}} {{ str_plural('reply', $thread->replies_count)}}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="body">{{ $thread->body }}</div>
            <br>
        </div>
    </div>
    <br> 
@empty {{-- if there is no record at all --}}
    <p>There are no relevant results at this time.</p>
@endforelse