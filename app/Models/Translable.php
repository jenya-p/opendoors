<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


trait Translable {

    private $_translated = false;

    public static function bootTranslable() {

        self::saving(function (self $me) {
            if ($me->_translated) {
                throw new \Exception('Translated model can\'t be saved');
            }
        });


        self::retrieved(function (self $me) {
            $me->_translated = false;
        });

    }


    public function translate($locale = null) {
        if ($this->_translated) return $this;
        if ($locale === null) {
            $locale = app()->getLocale();
        }
        $this->_translated = true;

        $translateArray = function (&$values) use (&$translateArray, $locale) {
            if (is_array($values)) {
                foreach (array_keys($values) as $key) {
                    if (is_string($key) && Str::endsWith($key, '_en')
                        && array_key_exists($targetKey = substr($key, 0, -3), $values)) {
                        if ($locale == 'en' && !empty($values[$key])) {
                            $values[$targetKey] = $values[$key];
                        }
                        unset($values[$key]);
                    } else {
                        $translateArray($values[$key]);
                    }
                }
            }
        };

        foreach ($this->translable as $translable) {
            if (Str::endsWith($translable, '.*')) {
                $realTranslable = substr($translable, 0, -2);
                $values = $this->getAttribute($realTranslable);
                $translateArray($values);
                $this->setAttribute($realTranslable, $values);
            } else {
                if ($locale == 'en' && !empty($value = $this->getAttribute($translable . '_en'))) {
                    $this->setAttribute($translable, $value);
                }
                $this->makeHidden($translable . '_en');
            }
        }

        return $this;
    }

}
