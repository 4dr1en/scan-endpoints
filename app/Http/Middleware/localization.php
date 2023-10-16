<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class localization
{
/**
     * Supported languages for your application.
     */
    protected $supportedLanguages = ['en', 'fr'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $storedLocale = $request->session()->get('locale');

        if ($storedLocale && in_array($storedLocale, $this->supportedLanguages)) {
            App::setLocale($storedLocale);
        } else {
            $preferredLocale = $this->preferredLocale($request->header('Accept-Language'));

            if ($preferredLocale) {
                App::setLocale($preferredLocale);
                $request->session()->put('locale', $preferredLocale);
            }
        }

        return $next($request);
    }

    /**
     * Returns the preferred locale from the Accept-Language header.
     */
    protected function preferredLocale($header): ?string
    {
        $locales = [];
        $parts = explode(',', $header);

        foreach ($parts as $part) {
            $segments = explode(';', $part);
            $locale = trim($segments[0]);
            $q = isset($segments[1]) ? (float) str_replace('q=', '', $segments[1]) : 1.0;
            $locales[$locale] = $q;
        }

        arsort($locales); // Sorts the locales by the q value in descending order

        foreach ($locales as $locale => $q) {
            // Direct match (e.g. 'en', 'fr')
            if (in_array($locale, $this->supportedLanguages)) {
                return $locale;
            }
            
            // Check if two-letter language code is supported (e.g. 'en' from 'en-US')
            $twoLetterCode = substr($locale, 0, 2);
            if (in_array($twoLetterCode, $this->supportedLanguages)) {
                return $twoLetterCode;
            }
        }

        return null; // If no suitable locale is found
    }
}
