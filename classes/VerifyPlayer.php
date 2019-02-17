<?php

namespace Cleanse\PvPaissa\Classes;

use Auth;
use GuzzleHttp;
use Log;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;
use Cleanse\PvPaissa\Classes\HelperDataCenters;
use Cleanse\PvPaissa\Models\Player;

class VerifyPlayer
{
    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function getOrCreateId()
    {
        //get the player by their character id.
        $check = $this->firstOrCreate();

        //if no character id was given, return and say no character with that id
        if (!isset($check->character)) {
            $error = 'Character does not exist';

            return $error;
        }

        //else if character already claimed
        if (isset($check->user_id)) {
            $error = 'Character already claimed.';

            return $error;
        }

        return $check->verification_code;
    }

    public function checkIfVerified()
    {
        $user = Auth::getUser();

        if (!$user) {
            return null;
        }

        $needle = Player::where('character', $this->code)->first();
        $haystack = $this->guzzleCharacterPage();

        if (strpos($haystack, $needle->verification_code) !== false) {
            $needle->user = $user;
            $needle->verified_at = Carbon::now()->toDateTimeString();
            $needle->save();

            return true;
        }

        return false;
    }

    private function uniqueIdReal($length = 13)
    {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($length / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $length);
    }

    private function guzzleCharacterPage()
    {
        $guzzle = $this->guzzle();

        $crawler = new Crawler($guzzle);

        //If no character.
        if (!$crawler->filterXPath('//*[@id="character"]/div[2]/div[3]')->count()) {
            Log::info('No character or maintenance.');
            return;
        }

        return $crawler->filterXPath('//*[@id="character"]/div[2]/div[3]')->text();
    }

    private function guzzle()
    {
        $link = 'http://na.finalfantasyxiv.com/lodestone/character/'.$this->code.'/';

        $client = new GuzzleHttp\Client();

        $res = $client->get($link);

        return $res->getBody()->getContents();
    }

    private function firstOrCreate()
    {
        $guzzle = $this->guzzle();

        $crawler = new Crawler($guzzle);

        //If no character.
        if (!$crawler->filterXPath('//*[@id="character"]/div[2]/div[3]')->count()) {
            Log::info('No character or maintenance.');

            return false;
        }

        //DataCenter
        $dcHelper = new HelperDataCenters;
        $dc = $dcHelper->getDC($crawler->filterXPath('//*[@id="character"]/div[1]/a[1]/div[2]/p[2]')->text());

        $avatar = $crawler->filterXPath('//*[@id="character"]/div[1]/a[1]/div[1]/img')->attr('src');

        $player = Player::updateOrCreate(
            [
                'character' => $this->code
            ],
            [
                'name' => $crawler->filterXPath('//*[@id="character"]/div[1]/a[1]/div[2]/p[1]')->text(),
                'data_center' => $dc,
                'server' => $crawler->filterXPath('//*[@id="character"]/div[1]/a[1]/div[2]/p[2]')->text(),
                'avatar' => $avatar,
                'verification_code' => $this->uniqueIdReal(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]
        );

        return $player;
    }
}
