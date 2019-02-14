<?php

namespace Cleanse\Pvpaissa\Classes;

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
        'Aether' => [
            'Adamantoise',
            'Balmung',
            'Cactuar',
            'Coeurl',
            'Faerie',
            'Gilgamesh',
            'Goblin',
            'Jenova',
            'Mateus',
            'Midgardsormr',
            'Sargatanas',
            'Siren',
            'Zalera'
        ],
        'Primal' => [
            'Behemoth',
            'Brynhildr',
            'Diabolos',
            'Excalibur',
            'Exodus',
            'Famfrit',
            'Hyperion',
            'Lamia',
            'Leviathan',
            'Malboro',
            'Ultros'
        ],
        'Chaos' => [
            'Cerberus',
            'Lich',
            'Moogle',
            'Odin',
            'Phoenix',
            'Ragnarok',
            'Shiva',
            'Zodiark'
        ]
    ];

    public function getDC($server)
    {
        $datacenters = $this->datacenters;

        return key($this->getParentStack($server, $datacenters));
    }

    private function getParentStack($child, $stack) {
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
