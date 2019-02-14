<?php

namespace Cleanse\Pvpaissa;

use DateTime;
use Event;
use System\Classes\PluginBase;
use Cleanse\Pvpaissa\Models\Player;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name' => 'PvPaissa Profile',
            'description' => 'Adds FFXIV Feast and Frontlines profiles.',
            'author' => 'Paul Lovato',
            'icon' => 'icon-shield'
        ];
    }

    public function registerComponents()
    {
        return [
            'Cleanse\Pvpaissa\Components\Profile'   => 'cleansePvPaissaProfile',
            'Cleanse\Pvpaissa\Components\Verify'    => 'cleansePvPaissaVerify'
        ];
    }

    public function boot()
    {
        Event::listen('offline.sitesearch.query', function ($query) {

            $items = Player::where('name', 'like', "%${query}%")
                ->get();

            $results = $items->map(function ($item) use ($query) {

                $relevance = mb_stripos($item->name, $query) !== false ? 2 : 1;

                return [
                    'title' => $item->name,
                    'text' => $item->server . ' ' . $item->data_center,
                    'url' => '/character/' . $item->character,
                    'relevance' => $relevance,
                ];
            });

            return [
                'provider' => 'Character',
                'results' => $results,
            ];
        });
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'yearweek' => [$this, 'makeDateFromYearWeek']
            ]
        ];
    }

    public function makeDateFromYearWeek($yearWeek)
    {
        $year = substr($yearWeek, 0, -2);
        $week = substr($yearWeek, 4);

        $date = new DateTime();

        $date->setISODate($year, $week);
        return $date->format('Y-m-d');
    }
}
