<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

trait Favoritable
{
    protected static function bootFavoritable()
    {   
        /**
         * When you are deleting the associated model(reply, thread),
         * as part of that, I also want you to delete the favorites.
         *
         * @return void
         */
        static::deleting(function ($model) {
            /**
             * Remeber: we can't do it, cause it is a sql query.
             * That means, there isn't any favorite instance to delete.
             */
            // $model->favorites()->delete();
            $model->favorites->each->delete();

        });
    }

    // ##############################################################
    // Relations
    // ##############################################################
    /**
     * A reply can be favorited.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    // ##############################################################
    // Methods
    // ##############################################################
    /**
     * Favorite the current reply.
     *
     * @return Model
     */
    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];
        if (!$this->favorites()->where($attributes)->exists()) {
            return $this->favorites()->create($attributes);
        }
    }

    /**
     * Unfavorite the current reply.
     */
    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        /* Get the reply's favorites, ONLY the one, where ther user_id is the
        current users, and delete it. */
        // $this->favorites()->where($attributes)->delete();
        /* Rather than call a sql query, 
        instead: get a collection of those models, and then u could do deleteing.
         so that the deleting event can be picked up and fired. */
        // $this->favorites()->where($attributes)->get()->each(function($favorite){
        //     $favorite->delete();
        // });
       /*  A little bit clean up here: 
       We can use a higher order collection, kind of fancy terms or some
       syntax sugar, that laravle provices */
        $this->favorites()->where($attributes)->get()->each->delete();
    }

    /**
     * Determine if the current reply has been favorited.
     *
     * @return boolean
     */
    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    // ##############################################################
    // Custom attribute/ Accessor
    // ##############################################################

    /**
     * Get the number of favorites for the reply.
     *
     * @return integer
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }
}