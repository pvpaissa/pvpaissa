<?php

namespace Cleanse\Pvpaissa\Models;

use Model;

/**
 * Class Player
 * @package Cleanse\Pvpaissa\Models
 *
 * @property integer id
 * @property integer user_id
 * @property string character
 * @property string name
 * @property string data_center
 * @property string server
 * @property string avatar
 * @property integer pvp_rank
 * @property string grand_company
 * @property string verification_code
 * @property string verified_at
 */
class Player extends Model
{
    public $table = 'cleanse_pvpaissa_players';

    public $fillable = [
        'character',
        'name',
        'data_center',
        'server',
        'avatar',
        'pvp_rank',
        'grand_company'
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'user' => ['Cleanse\User\Models\User']
    ];

    public $hasOne = [
        'frontlines' => [
            'Cleanse\Frontlines\Models\Overall',
            'key' => 'player_id',
            'otherKey' => 'id'
        ]
    ];

    public $hasMany = [
        'solo' => [
            'Cleanse\Feast\Models\FeastSolo',
            'key' => 'player_id'
        ],
        'solo_daily' => [
            'Cleanse\Feast\Models\FeastSoloDaily',
            'key' => 'player_id'
        ],
        'party' => [
            'Cleanse\Feast\Models\FeastParty',
            'key' => 'player_id'
        ],
        'party_daily' => [
            'Cleanse\Feast\Models\FeastPartyDaily',
            'key' => 'player_id'
        ],
        'frontlines_weekly' => [
            'Cleanse\Frontlines\Models\Weekly',
            'key' => 'player_id'
        ]
    ];

    /**
     * Automatically links a player to a user if not one already.
     * @param  Cleanse\User\Models\User $user
     * @return Cleanse\Pvpaissa\Models\Player
     */
    public static function getFromUser($user = null)
    {
        if ($user === null) {
            $user = Auth::getUser();
        }

        if (!$user) {
            return null;
        }

        if ($player = $user->pvpaissa_player) { /* ahh */
            $player->setRelation('user', $user);
        }
        else {
            $getLodestoneInformation = [];

            $player = new static;
            $player->user = $user;
            $player->character = $getLodestoneInformation['character'];
            $player->character = $getLodestoneInformation['name'];
            $player->character = $getLodestoneInformation['data_center'];
            $player->character = $getLodestoneInformation['server'];
            $player->character = $getLodestoneInformation['avatar'] ?? '';
            $player->character = $getLodestoneInformation['pvp_rank'] ?? '';
            $player->character = $getLodestoneInformation['grand_company'] ?? '';
            $player->save();

            $user->pvpaissa_player = $player; /* ahh */
        }

        return $player;
    }

}
