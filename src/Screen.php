<?php

namespace samjoyce777\LaravelPostScreen;

use GoogleMapStatic\StaticMap;
use GoogleMapStatic\Generators\UrlGenerator;
use GoogleMapStatic\Elements\Marker\Marker;
use GoogleMapStatic\UnitMeasures\Coordinate;
use GoogleMapStatic\Elements\Marker\MarkerStyle;
use GoogleMapStatic\UnitMeasures\MapSize;


class Screen
{

    /**
     * This rotates through the specified level of configuration and calls each method to check if it passes.
     * It will return false at first found instance of something flaggable.
     * @param $text
     * @param string $level
     * @return bool
     */
    public function isClean($text, $level = 'default')
    {
        $config = config('screen.level.' . $level);

        foreach ($config as $key => $value) {
            if ($value && is_callable(array($this, $key))) {
                if ($this->$key($text)) return false;
            }
        }

        return true;
    }

    /**
     * Searches to see if text has an email address in it
     * @param $text
     * @return bool
     */
    public function hasEmail($text)
    {
        return (preg_match('/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/si', $text)) ? true : false;
    }

    /**
     * Checks to see if text has banneds word, returns on first instance
     * @param $text
     * @return bool
     */
    public function hasBanned($text)
    {
        foreach (config('screen.banned') as $banned) {
            if (strpos($text, $banned) !== false) return true;
        }

        return false;
    }

    /**
     * Checks to see if text has flaggable words, returns on first instance
     * @param $text
     * @return bool
     */
    public function hasFlaggable($text)
    {
        foreach (config('screen.flaggable') as $flaggable) {
            if (strpos($text, $flaggable) !== false) return true;
        }

        return false;
    }

    /**
     * Checks to see if text has at least one external text
     * @param $text
     * @return bool
     */
    public function hasExternalLink($text)
    {
        $links = $this->getLinks($text);

        foreach ($links as $link) {
            if (parse_url($link, PHP_URL_HOST) != \Request::getHttpHost()) return true;
        }

        return false;
    }


    /**
     * Checks to see if text has at least one internal link
     * @param $text
     * @return bool
     */
    public function hasInternalLink($text)
    {
        $links = $this->getLinks($text);

        foreach ($links as $link) {
            if (parse_url($link, PHP_URL_HOST) == \Request::getHttpHost()) return true;
        }

        return false;
    }


    /**
     * Checks to see text any links
     * @param $text
     * @return bool
     */
    public function hasLink($text)
    {
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

        return (preg_match($reg_exUrl, $text)) ? true : false;
    }


    /**
     * Get array of links in the text
     * @param $text
     * @return mixed
     */
    public function getLinks($text)
    {
        $regex = '/https?\:\/\/[^\" ]+/i';
        preg_match_all($regex, $text, $matches);

        return $matches[0];
    }
}