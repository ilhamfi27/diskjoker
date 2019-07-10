<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Guest;
use App\Room;
use App\SongRequest;
use App\Events\NewSongRequest;

class SongRequestController extends Controller
{
    function store(Request $request)
    {
        $user = Auth::user();
        $room = Room::find($request->room_id);
        $identification = $user ? $user->id : $request->ip();
        $user_type = $user ? 'member' : 'guest';
        $maxRequest = $user ? $room->max_song_per_user : $room->max_song_per_guest;

        $validator = $this->validator([
            'video_id' => $request->video_id
        ]);

        $totalRequest = SongRequest::countUserRequests($room->id, $user_type, $identification);
        $lastQueue = SongRequest::lastSongQueue($room->id);

        $ytVideoDetail = !$validator->fails() ? $this->getYoutubeVideoDetails($request->video_id) : NULL;

        if($validator->fails()){
            return response()->json([
                'warning' => true,
                'message' => $validator->errors()->first('video_id')
            ]);
        }

        if ($totalRequest >= $maxRequest) {
            return response()->json([
                'error' => true,
                'message' => 'Maximum request song exceeded for today!'
            ]);
        } else {
            $songRequest = [
                'video_id' => $this->getYouTubeVideoID($request->video_id),
                'room_id' => $request->room_id,
                'queue' => $lastQueue == null ?
                                1 : $lastQueue + 1,
                'user_type' => $user_type,
                'user_id' => $user == null ?
                            null : $user->id,
                'ip_address' => $user == null ?
                            $request->ip() : null,
                'title' => $ytVideoDetail['title'],
                // 'thumbnail_df' => $ytVideoDetail['thumbnail_hg']->url, // actually it uses thumbnail->high
                'thumbnail_df' => '',
            ];
            $this->insertSongRequest($songRequest);
        }

        $songRequests = SongRequest::roomSongRequests($request->room_id)->last();
        return response()->json([
            'error' => false,
            'message' => 'Song added!',
            'song_request_data' => $songRequests
        ]);
    }

    function getSongRequests($room, $status, Request $request)
    {
        $songRequest = SongRequest::roomSongRequests($room,$status);
        if($request != NULL){
            $songRequest = $request->last && $request->last == 'true' ?
            $songRequest->last() : $songRequest ;
        } else {
            if ($songRequest->count() < 1) {
                return response()->json([
                    'error' => true,
                    'message' => 'Data not found'
                ]);
            }
        }
        return response()->json($songRequest, 200);
    }

    private function validator(array $data)
    {
        return Validator::make($data, [
            'video_id' => ['required'],
        ]);
    }

    private function insertSongRequest($data)
    {
        $songRequest = new SongRequest;
        $songRequest->video_id = $data['video_id'];
        $songRequest->room_id = $data['room_id'];
        $songRequest->queue =  $data['queue'];
        $songRequest->status = 'queue';
        $songRequest->user_type = $data['user_type'];
        $songRequest->user_id =  $data['user_id'];
        $songRequest->ip_address =  $data['ip_address'];
        $songRequest->title =  $data['title'];
        $songRequest->thumbnail_df =  $data['thumbnail_df'];
        return $songRequest->save();
    }
    
    private function getYouTubeVideoID($url)
    {
        $queryString = parse_url($url, PHP_URL_QUERY);
        parse_str($queryString, $params);
        if (isset($params['v']) && strlen($params['v']) > 0) {
            return $params['v'];
        } else {
            return "";
        }
    }

    private function getYoutubeVideoDetails($video_url)
    {
        $api_key = env('YOUTUBE_API_KEY');
        $video_id = $this->getYouTubeVideoID($video_url);
        $part = 'snippet%2CcontentDetails%2Cstatistics';

        $api_url = 'https://www.googleapis.com/youtube/v3/videos?part=' . $part . 
                                                                 '&id=' . $video_id . 
                                                                '&key=' . $api_key;
        
        $rawDetail = json_decode(file_get_contents($api_url));
        return [
            'title' => $rawDetail->items[0]->snippet->title,
            'thumbnail_df' => $rawDetail->items[0]->snippet->thumbnails->default,
            'thumbnail_md' => $rawDetail->items[0]->snippet->thumbnails->medium,
            'thumbnail_hg' => $rawDetail->items[0]->snippet->thumbnails->high,
            'description' => $rawDetail->items[0]->snippet->description,
        ];
    }
}
