@forelse ($threads as $thread)
    <div class="card card-default">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <h4>
                        {{-- <a href={{route( 'threads.show',[ 'channel'=>$thread->channel->slug,'thread'=>$thread->id])}}> --}}
                        <a href={{$thread->path()}}>
                            @if($thread->pinned)
                               <span class="fa fa-map-pin" aria-hidden="true"></span>
                            @endif                     
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
        {{-- <div class="body">{!! $thread->body !!}</div> --}}
        <div class="card-body">
            <thread-view :thread="{{ $thread }}" inline-template>
                <highlight :content="body"></highlight>
            </thread-view>
            <br>
        </div>
        <div class="card-footer">
            {{-- {{ $thread->visits }} Visits --}}
            <div class="level">
                <div class="flex">
                    {{ $thread->visits }} Visits            
                </div>
                <a href="/threads/{{ $thread->channel->slug }}"><span class="label label-primary">{{ $thread->channel->name}}</span></a>            
            </div>
        </div>
    </div>
    <br> 
@empty {{-- if there is no record at all --}}
    <p>There are no relevant results at this time.</p>
@endforelse