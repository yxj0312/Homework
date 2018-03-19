<div class="card card-default">        
    <div class="card-header">
        <div class="level">
            <div class="flex">
                <a href="{{ route('profile', $reply->owner) }}">
                    {{$reply->owner->name}}
                </a> said {{ $reply->created_at->diffForHumans() }}...
            </div>
            <div>
                
                
                <form action="/replies/{{ $reply->id }}/favorites" method="POST">
                    {{ csrf_field() }}
                    {{-- remeber favorites() is better than favorites --}}
                    <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : ''}}>
                        {{--  {{ $reply->favorites()->count() }} 
                        {{ str_plural('Favorite', $reply->favorites()->count())}}  --}}

                        {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count)}}
                    </button>
                </form>
            </div>         
        </div>
    </div>

    <div class="card-body">
        {{ $reply->body }}
    </div>
</div>
<br>