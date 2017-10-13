<?php

namespace App\Http\Controllers;

use App\Playlists\Contracts\PlaylistsInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistsController extends Controller
{

    /**
     * @var PlaylistsInterface
     */
    private $playlists;

    public function __construct(PlaylistsInterface $playlists)
    {
        $this->playlists = $playlists;
    }

    /**
     * Gets all playlists
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {

        //dd(Youtube::getPlaylistById('PL3485902CC4FB6C67'));
        if($request->ajax())
            return response($this->playlists->getAll(), 200);

        return view('pages.interface.playlists');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.interface.playlists');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name');

        if ($this->playlists->existsByName($name)) {
            return response('Playlist already exists', 422);
        }

        $playlist = $this->playlists->create($name, Auth::user()->id);

        return response($playlist, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if($request->ajax())
            return response($this->playlists->getById($id)->first(), 200);

        return view('pages.interface.playlists');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('pages.interface.playlists');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $playlist = $this->playlists->update($id, $request->all());

        return response($playlist, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->playlists->remove($id);

        return response('success', 200);
    }

    /**
     * Searches Youtube
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function searchYoutube(Request $request)
    {
        $term = $request->input('term', '');
        $type = $request->input('type', 'video');
        $limit = $request->input('limit', 5);

        $results = $this->playlists->searchYoutube($term, $type, $limit);

        return response($results, 200);
    }

    /**
     * Gets the Youtube's playlists content
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function playlistContent(Request $request)
    {
        $id = $request->input('id', null);

        $results = $this->playlists->playlistContent($id);

        return response()->json($results, 200);
    }
}