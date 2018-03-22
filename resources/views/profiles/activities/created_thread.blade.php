@component('profiles.activities.activity')
    @slot('heading')
        {{ $profileUser->name }} published <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent


{{--  include can also do the same things, but not so good as component.
One reason is the limit of html or variables in array  --}}

{{--  @include('profiles.activities.activity',[
    'heading' => 'my heading',
    'body' => 'my body'
])  --}}