<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Zttp\Zttp;

class Recaptcha implements Rule
{
    const URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Guzzle or zttp

        $response = Zttp::asFormParams()->post(static::URL, [
            'secret' => config('services.recaptcha.secret'),
            'response' => $value,
            'remoteip' => request()->ip()
        ]);

        return $response->json()['success'];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The recaptcha verification failed. Try again.';
    }

    /**
     * Is Recaptcha in test mode?
     * @method isInTestMode
     *
     * @return   bool
     */
    public static function isInTestMode()
    {
        return Zttp::asFormParams()->post(static::URL, [
            'secret' => config('services.recaptcha.secret'),
            'response' => 'test',
            'remoteip' => request()->ip()
        ])->json()['success'];
    }
}
