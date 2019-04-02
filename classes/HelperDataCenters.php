<?php

namespace Cleanse\PvPaissa\Classes;

class HelperDataCenters
{
    public $datacenters = [
        'Elemental' => [
            'Aegis',
            'Atomos',
            'Carbuncle',
            'Garuda',
            'Gungnir',
            'Kujata',
            'Ramuh',
            'Tonberry',
            'Typhon',
            'Unicorn'
        ],
        'Gaia' => [
            'Alexander',
            'Bahamut',
            'Durandal',
            'Fenrir',
            'Ifrit',
            'Ridill',
            'Tiamat',
            'Ultima',
            'Valefor',
            'Yojimbo',
            'Zeromus'
        ],
        'Mana' => [
            'Anima',
            'Asura',
            'Belias',
            'Chocobo',
            'Hades',
            'Ixion',
            'Mandragora',
            'Masamune',
            'Pandaemonium',
            'Shinryu',
            'Titan'
        ],
        'Crystal' => [
            'Balmung',
            'Brynhildr',
            'Coeurl',
            'Diabolos',
            'Goblin',
            'Malboro',
            'Mateus',
            'Zalera'
        ],
        'Aether' => [
            'Adamantoise',
            'Cactuar',
            'Faerie',
            'Gilgamesh',
            'Jenova',
            'Midgardsormr',
            'Sargatanas',
            'Siren'
        ],
        'Primal' => [
            'Behemoth',
            'Excalibur',
            'Exodus',
            'Famfrit',
            'Hyperion',
            'Lamia',
            'Leviathan',
            'Ultros'
        ],
        'Chaos' => [
            'Cerberus',
            'Louisoix',
            'Moogle',
            'Omega',
            'Ragnarok',
        ],
        'Light' => [
            'Lich',
            'Odin',
            'Phoenix',
            'Shiva',
            'Zodiark'
        ]
    ];

    public function getDC($server)
    {
        $datacenters = $this->datacenters;

        return key($this->getParentStack($server, $datacenters));
    }

    private function getParentStack($child, $stack)
    {
        foreach ($stack as $k => $v) {
            if (is_array($v)) {
                // If the current element of the array is an array, recurse it and capture the return
                $return = $this->getParentStack($child, $v);

                // If the return is an array, stack it and return it
                if (is_array($return)) {
                    return array($k => $return);
                }
            } else {
                // Since we are not on an array, compare directly
                if ($v == $child) {
                    // And if we match, stack it and return it
                    return array($k => $child);
                }
            }
        }

        // Return false since there was nothing found
        return false;
    }
}
