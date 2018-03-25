@component('profiles.activities.activity')
    @slot('icon')
        <i class="fa fa-reply" aria-hidden="true"></i>
    @endslot
    @slot('heading')
        {{ $profileUser->name }} replied to
        <a href="{{ $activity->subject->thread->path() }}">"{{ $activity->subject->thread->title }}"</a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent