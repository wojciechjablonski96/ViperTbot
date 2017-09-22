<?php namespace App\Http\Controllers;

use App\Repositories\TwitchApiRepository;

class InterfaceController extends Controller
{
    /**
     * @var TwitchApiRepository
     */
    private $twitch;


    public function __construct(TwitchApiRepository $twitch)
    {
        $this->twitch = $twitch;
    }

    public function index()
    {
        //dd($this->twitch->myChannel());
        //dd(config('services.igdb.key'));
        return view('pages.interface.index', [
            'followers' => $this->twitch->followers(),
            'subscribers' => $this->twitch->subscribers(),
            'live_channels' => $this->twitch->liveChannels()
        ]);
    }
}