<?php

namespace Cleanse\PvPaissa\Classes;

class HelperRankSort
{
    public function sortRanks($players, $type)
    {
        $data = $this->sortPlayers($players, $type);

        $dataSorted = [];
        $rank = 0;
        $lastScore = PHP_INT_MAX;
        foreach ($data as $name => $score) {
            if ($lastScore !== $score[$type]) {
                $lastScore = $score[$type];
                $rank += 1;
            }

            $dataSorted[$score['id']] = $rank;
        }

        return $dataSorted;
    }

    private function sortPlayers($players, $orderBy)
    {
        $sortArray = [];

        foreach ($players as $player) {
            foreach ($player as $key => $value) {
                if (!isset($sortArray[$key])) {
                    $sortArray[$key] = array();
                }
                $sortArray[$key][] = $value;
            }
        }

        array_multisort($sortArray[$orderBy], SORT_DESC, $players);

        return $players;
    }
}
