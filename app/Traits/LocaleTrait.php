<?php

namespace App\Traits;

use Exception;

trait LocaleTrait
{
    use JwtTrait;

    public function setLocale()
    {
        if (!empty(app('request')->input('locale'))) {
            $locale = app('request')->input('locale');
        }
        elseif (!empty(app('request')->headers->get('locale'))) {
            $locale = app('request')->headers->get('locale');
        }
        elseif (!empty($this->jwt('profile')->get('locale'))) {
            $locale = $this->jwt('profile')->get('locale');
        }
        else {
            $locale = env('DEFAULT_LOCALE');
        }

        self::checkRequestedLocale($locale);

        app()->setLocale($locale);
    }

    public function checkRequestedLocale($locale)
    {
        $localesAllowed = explode(',', env('LOCALES_ALLOWED'));

        if (!in_array($locale, $localesAllowed)) {
            throw new Exception('Locale not allowed.');
        }
    }
}
