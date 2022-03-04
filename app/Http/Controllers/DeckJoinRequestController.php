<?php

namespace App\Http\Controllers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\CatalogApi;
use App\DeckJoinRequest;
use App\Http\Requests\DeleteDeckJoinRequest;
use Exception;
use Illuminate\Http\Request;

require 'twitter/twitteroauth/autoload.php';

class DeckJoinRequestController extends Controller
{

    /**
     * @throws \Abraham\TwitterOAuth\TwitterOAuthException
     */
    public function buildAuthorizeURL(Request $request)
    {
        //Save the user wanted deck Id
        session(['deckId' => $request->input('deckId')]);

        //Get api token
        $deckJoinApi = CatalogApi::latest()->first();
        //Check if api exist
        if ($deckJoinApi === null) {
            abort(404);
        }

        //Create Twitter App Instance
        $connection = new TwitterOAuth($deckJoinApi->key, $deckJoinApi->secret);

        //Generate oauth_token
        $OAUTH_CALLBACK = env('TWITTER_CALLBACK_URL', 'https://www.feed-help.de/callback') . '/catalog';

        dd($OAUTH_CALLBACK);
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $OAUTH_CALLBACK));

        //Save oauth_token and oauth_token_secret for authenticating the request later
        session(['oauth_token' => $request_token['oauth_token']]);
        session(['oauth_token_secret' => $request_token['oauth_token_secret']]);

        //Generate authentication url
        $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        return redirect($url);
    }

    /**
     * @throws Exception
     */
    public function destroy(DeleteDeckJoinRequest $request, DeckJoinRequest $deckJoinRequest)
    {
        $deckJoinRequest->delete();
        return redirect()->back()->withSuccess('Petición eliminada exitosamente');
    }


    public function callback(Request $request)
    {
        $deckJoinApi = CatalogApi::latest()->first();
        //Check if api exist
        if ($deckJoinApi === null) {
            abort(404);
        }

        //Create connection, identifying the api and retrieving the generated oauth info
        $connection = new TwitterOAuth($deckJoinApi->key, $deckJoinApi->secret, session('oauth_token'), session('oauth_token_secret'));

        //Finally, get user Access Token
        $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $request['oauth_verifier']]);

        $extraInfo = $this->getUserProfile($access_token['screen_name']);

        DeckJoinRequest::updateOrCreate(
            [
                'username' => auth()->user()->username,
                'deck_id' => session('deckId'),
            ],
            [
                'twitter_account' => $extraInfo['username'],
                'twitter_followers' => $extraInfo['followers_count']
            ]
        );

        //Redirect to view
        return redirect()->route('decks.catalog')->withSuccess('Hemos enviado tu petición');
    }

    public function getUserProfile($username)
    {
        $url = "https://twitter.com/i/api/graphql/ku_TJZNyXL2T4-D9Oypg7w/UserByScreenName?variables=%7B%22screen_name%22%3A%22" . $username . "%22%2C%22withHighlightedLabel%22%3Atrue%7D";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "authorization: Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA",
            "x-guest-token: " . $this->getGuestToken(),
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $resp = curl_exec($curl);
        curl_close($curl);
        $object = json_decode($resp);

        return [
            'username' => $username,
            'followers_count' => $object->data->user->legacy->followers_count,
        ];
    }

    public function getGuestToken()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.twitter.com/1.1/guest/activate.json');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

        $headers = array();
        $headers[] = 'Authority: api.twitter.com';
        $headers[] = 'Content-Length: 0';
        $headers[] = 'Sec-Ch-Ua: \"Google Chrome\";v=\"87\", \" Not;A Brand\";v=\"99\", \"Chromium\";v=\"87\"';
        $headers[] = 'X-Twitter-Client-Language: es';
        $headers[] = 'Sec-Ch-Ua-Mobile: ?0';
        $headers[] = 'Authorization: Bearer AAAAAAAAAAAAAAAAAAAAANRILgAAAAAAnNwIzUejRCOuH5E6I8xnZz4puTs%3D1Zv7ttfk8LF81IUq16cHjhLTvJu4FA33AGWWjCpTnA';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36';
        $headers[] = 'X-Twitter-Active-User: yes';
        $headers[] = 'Accept: */*';
        $headers[] = 'Origin: https://twitter.com';
        $headers[] = 'Sec-Fetch-Site: same-site';
        $headers[] = 'Sec-Fetch-Mode: cors';
        $headers[] = 'Sec-Fetch-Dest: empty';
        $headers[] = 'Referer: https://twitter.com/';
        $headers[] = 'Accept-Language: es-ES,es;q=0.9';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        return ($result->guest_token);
    }

}
