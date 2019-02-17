<?php

namespace Cleanse\PvPaissa\Classes;

use Cleanse\Feast\Classes\UpdateSolo;
use Cleanse\Feast\Classes\UpdateParty;
use Cleanse\Frontlines\Classes\UpdateFrontlines;
use Cleanse\PvPaissa\Models\Player;

class UpdateOrCreatePlayer
{
    private $mode;
    private $player = [];

    public function __construct($mode, $player)
    {
        $this->mode = $mode;
        $this->player = $player;
    }

    public function update($season = 1)
    {
        switch ($this->mode) {
            case "solo":
                $model = new UpdateSolo($season, $this->player);
                break;
            case "party":
                $model = new UpdateParty($season, $this->player);
                break;
            case "frontlines":
                $model = new UpdateFrontlines($this->player);
                break;
        }

        $data = Player::updateOrCreate(
            ['character' => $this->player['character']],
            $this->player
        )->toArray();

        $model->update($data['id']);
    }
}
