<?php

namespace App\Inspections;

class Spam
{
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    public function detect($body)
    {
        // Detect invalid keywords.

        // $this->detectInvalidKeywords($body);
        // $this->detectKeyHeldDown($body);

        foreach ($this->inspections as $inspection) {
            // Laravel container makes this easy
            // Fetch one as a container
            app($inspection)->detect($body);
        }

        return false;
    }

    /* Refactored */
    // protected function detectInvalidKeywords($body)
    // {
    //     $invalidKeywords = [
    //         'yahoo customer support'
    //     ];

    //     foreach ($invalidKeywords as $keyword) {
    //         if (stripos($body, $keyword) !== false) {
    //             throw new \Exception('Your reply contains spam.');
    //         }
    //     }
    // }

    // protected function detectKeyHeldDown($body)
    // {
    //     /** Tinker */
    //     // Look for any char what so ever, try to find full matches of that exacted char act

    //     // wrapping so that it will be included as a match, and I can refer back to that match using \1 and 4 one more of those:
    //     // preg_match('/(.)\\1{4,}/', 'aaaaaaaaa', $matches);
    //     // $matches

    //     if (preg_match('/(.)\\1{4,}/', $body)) {
    //         throw new \Exception('Your reply contains spam.');
    //     }

    // }
}
