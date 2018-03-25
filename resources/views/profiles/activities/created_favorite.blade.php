@component('profiles.activities.activity')
    @slot('icon')
        <i class="fa fa-heart" aria-hidden="true"></i>
    @endslot
    @slot('heading')
        <a href="{{ $activity->subject->favorited->path() }}">
            {{ $profileUser->name }} favorited a reply
        </a>
    @endslot

    @slot('body')
        {{ $activity->subject->favorited->body }}
    @endslot
@endcomponent