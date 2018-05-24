<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

trait RecordsActivity
{
    // Laravel toggle it in boot() of Thread.
    protected static function bootRecordsActivity()
    {
        // Prevent from 'Integrity constraint violation'.
        if (auth()->guest()) {
            return;
        }

        foreach (static::getActivityToRecord() as $event) {
            /* static::created(function ($thread) {
                $thread->recordActivity('created');
            }); */
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        /**
         * Add new event listner.
         * If threads are deleted, their associated activities will be also deleted.
         *
         * This will ONLY fire on the model like $favorite->delete().
         * If u have some sql query like unfavorite() method in favoritable,
         * it won't be toggled.
         *
         * Another bug: if we delete a reply, it will delete the activity of reply model,
         * but not favorite model.
         *
         * Solution: A reply can be favoritable(trait), we settle up a model event in the favoritable
         * If the associated model is ever deleted, as part of that relation I also want you to delete
         * the favorites for the model
         * @return void
         */
        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    protected static function getActivityToRecord()
    {
        // return ['created', 'deleted']; Or u can set a perporty and return them.

        return ['created'];
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event),
        ]);

        // Activity::create([
        //     'user_id' => auth()->id(),
            // 1st Refactor: 'type' =>'created_thread' ,
        /**getShortName example App\\Foo\\Thread -> Thread */
            // 2nd Refactor:  'type' => 'created_' . strtolower((new \ReflectionClass($thread))->getShortName()),
            // 3rd Refactor'type' => $event . '_' . strtolower((new \ReflectionClass($this))->getShortName()),
        //     'type' => $this->getActivityType($event),
        //     'subject_id' => $this->id,
        //     // 'subject_type' => 'App\Thread'
        //     'subject_type' => get_class($this)
        // ]);
    }

    // Polymorph
    public function activity()
    {
        // means we will not hard coding the related model.
        return $this->morphMany('App\Activity', 'subject');
    }

    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());

        // return $event . '_' . $type;
        return "{$event}_{$type}";
    }
}
