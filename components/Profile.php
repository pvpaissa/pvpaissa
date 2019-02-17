<?php

namespace Cleanse\PvPaissa\Components;

use Config;
use Cms\Classes\ComponentBase;
use Cleanse\PvPaissa\Models\Player;

class Profile extends ComponentBase
{
    public $character;
    public $slug;

    public function componentDetails()
    {
        return [
            'name'            => 'PvPaissa Profile',
            'description'     => 'Grabs the Feast and Frontline stats.'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'Character Slug',
                'description' => 'Look up the character by their id.',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ]
        ];
    }

    public function onRun()
    {
        $this->slug = $this->property('slug');
        $this->page['season'] = $this->loadCurrentSeason();
        $this->page['gcs'] = $this->getGCS();
        $this->character = $this->page['character'] = $this->loadRankings();
    }

    public function loadRankings()
    {
        return Player::with([
                'solo' => function($q){
                    $q->orderBy('season', 'desc');
                },
                'party' => function($q){
                    $q->orderBy('season', 'desc');
                },
                'frontlines'
            ])
            ->where('character', $this->slug)
            ->first();
    }

    private function loadCurrentSeason()
    {
        return Config::get('cleanse.feast::season', 1);
    }

    private function getGCS()
    {
        return ['Immortal Flames', 'Maelstrom', 'Order of the Twin Adder'];
    }
}
