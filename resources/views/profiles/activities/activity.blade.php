<div class="card card-default">
    <div class="card-header">
        <div class="level">
            <span class="flex">
                {{--  <a href="{{ route('profile', $thread->creator)}}">{{ $thread->creator->name }}</a> posted:
                <a href="{{ $thread->path() }}">{{ $thread->title }}</a>  --}}
                {{ $heading }}
            </span>
            {{--  <span>  --}}
                {{--  {{$thread->created_at->diffForHumans()}}  --}}
            {{--  </span>  --}}
        </div>
    </div>

    <div class="card-body">
        {{-- {{ $thread->body }} --}}
        {{ $body }}
    </div>
</div>
<br>