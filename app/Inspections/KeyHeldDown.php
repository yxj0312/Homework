<?php

namespace App\Inspections;

use Exception;

class KeyHeldDown
{
    public function detect($body)
    {
        /* Tinker */
        // Look for any char what so ever, try to find full matches of that exacted char act

        // wrapping so that it will be included as a match, and I can refer back to that match using \1 and 4 one more of those:
        // preg_match('/(.)\\1{4,}/', 'aaaaaaaaa', $matches);
        // $matches

        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new Exception('Your reply contains spam.');
        }
    }
}
