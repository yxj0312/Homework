{{--  v-cloak means add the attributes until every thing is loaded  --}}
{{--  when everything is loaded, vue will automatically remove that  --}}
{{--  Then we set a displaynone for this tag, so that we don't see some vue component before attributes are loaded  --}}
<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="card card-default">        
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
            <div v-if="editing">
                <div class="form-group">
                    {{--  We should not input {{ $reply->body }} cause v-model  --}}
                    {{--  we gonna pass it on the top  --}}
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-xs btn-primary" @click="update">Update</button>                
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body"></div>
        </div>

        {{--  @can('update', Reply::class)  --}}
        @can('update', $reply)    
            <div class="card-footer level">
                <button class="btn btn-xs mr-1" @click="editing = true">Edit</button>

                <form action="/replies/{{ $reply->id }}" method="POST">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                </form>
            </div>
        @endcan
    </div>
    <br>
</reply>