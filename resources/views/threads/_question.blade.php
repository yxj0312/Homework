<div class="card card-default">
    <div class="card-header">
        <div class="level">
            <img src="{{ $thread->creator->avatar_path }}" alt="{{ $thread->creator->name }}"
            width="25" height="25" class="mr-1">
            <span class="flex">
                <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a> posted:
                {{ $thread->title }}
            </span>
            @can ('update', $thread)
            <form action="{{ $thread->path() }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE')}}

                <button type="submit" class="btn btn-link">Delete Thread</button>
            </form>
            @endcan
        </div>
    </div>
    
    <div class="card-body">
        {{ $thread->body }}
    </div>

    <div class="card-footer">
        <button class="btn btn-xs" @click="editing = true">Edit</button>
    </div>
</div>