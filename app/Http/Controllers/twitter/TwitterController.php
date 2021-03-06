<?php

namespace App\Http\Controllers\twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Api;
use App\Deck;
use App\DeckUser;
use App\Http\Controllers\Controller;
use App\Record;
use App\TargetAccount;
use App\TwitterAccount;
use App\TwitterAccountApi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

require 'twitteroauth/autoload.php';

class TwitterController extends Controller
{

    public function buildAuthorizeURL(Request $request)
    {
        $targetAccount = TargetAccount::where('user_id', auth()->user()->id)
            ->where('hasVisited', false)->first();
        if ($targetAccount) {
            $targetAccount->hasVisited = true;
            $targetAccount->save();
            return redirect()->route('captureAccount');
        }

        $api = Api::find($request->input('apiId'));
        //Check if api exist
        if ($api === null) {
            abort(404);
        }
        //Save the API ID for benign able to remember the tokens.
        session(['apiId' => $api->id]);

        //check if user is cheating
        $deck = $api->deck;
        if ($deck->id != $request->input('deckId')) {
            abort(403);
        }

        $OAUTH_CALLBACK = env('TWITTER_CALLBACK_URL', 'https://www.feed-help.de/callback');
        //Create Twitter App Instance
        $connection = new TwitterOAuth($api->key, $api->secret);

        //Generate oauth_token
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $OAUTH_CALLBACK));

        //Save oauth_token and oauth_token_secret for authenticating the request later
        session(['oauth_token' => $request_token['oauth_token']]);
        session(['oauth_token_secret' => $request_token['oauth_token_secret']]);

        //Generate authentication url
        $url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
        return redirect($url);
    }

    public function callback(Request $request)
    {
        $api = Api::find(session('apiId'));
        //First, verify that the user belogs to the deck
        $deckUser = DeckUser::where([['user_id', auth()->user()->id], ['deck_id', $api->deck->id]])->first();
        if ($deckUser === null) {
            return redirect()->route('decks.apis.verify', ['deckId' => $api->deck->id])
                ->withError('No perteneces al Deck a el que est??s intentando vincular');
        }
        $callback = env('TWITTER_CALLBACK_URL', 'https://www.feed-help.de/callback');

        //Create connection, identifying the api and retrieving the generated oauth info
        $connection = new TwitterOAuth($api->key, $api->secret, session('oauth_token'), session('oauth_token_secret'));

        //Finally get user Access Tokends
        $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $request['oauth_verifier']]);

        //Create twitter account record
        $extraInfo = $this->getAccountExtraInfo($access_token['screen_name']);


        //Check if user is in more than two decks
        $decksCount = TwitterAccount::where('username', '=', $access_token['screen_name'])->count();

        if ($decksCount > 2) {
            return "
            <script>alert('Lo sentimos, ??nicamente puedes estar en dos decks por cuenta de twitter')
            window.location.href='/decks';
            </script>";
        }

        //Continue normal flow
        $twitterAccount = TwitterAccount::where('deck_id', '=', $api->deck->id)
            ->where('user_id', '=', auth()->user()->id)
            ->first();
        $deck = $api->deck;
        if ($twitterAccount) {
            //Subtract current followers, and update for the new ones.
            $deck->followers -= $twitterAccount->followers;
            $deck->followers += $extraInfo['followers_count'];
            $deck->save();
            //Update twitter account info
            $twitterAccount->username = $access_token['screen_name'];
            $twitterAccount->followers = $extraInfo['followers_count'];
            $twitterAccount->image_url = $extraInfo['profile_image_url_https'];
            $twitterAccount->status = 'pending';
            $twitterAccount->save();
        } else {
            //Create twitter account
            $twitterAccount = TwitterAccount::create(
                [
                    'deck_id' => $api->deck->id,
                    'user_id' => auth()->user()->id,
                    'username' => $access_token['screen_name'],
                    'followers' => $extraInfo['followers_count'],
                    'image_url' => $extraInfo['profile_image_url_https'],
                    'status' => 'pending',
                ]);
            //Update deck followers
            $deck->followers += $extraInfo['followers_count'];
            $deck->save();
        }

        //Store api credentials
        TwitterAccountApi::updateOrCreate(
            [
                'twitter_account_id' => $twitterAccount->id,
                'api_id' => $api->id,
                'user_id' => auth()->user()->id,
            ],
            [
                'isActive' => true,
                'key' => $access_token['oauth_token'],
                'secret' => $access_token['oauth_token_secret'],
            ]);

        //Sync the pivot table
        $deckUser->twitter_account_id = $twitterAccount->id;
        $deckUser->save();

        // Check if the user has already authorize all apis in order to active it account.
        $this->checkIfTwitterAccountHasAllApis($twitterAccount, $api->deck->id);

        //Redirect to view
        return redirect()->route('decks.apis.verify', ['deckId' => $api->deck->id]);
    }

    public function getAccountExtraInfo($username): array
    {
        $profile = $this->getUserProfile($username);

        return [
            'followers_count' => $profile->data->user->legacy->followers_count,
            'profile_image_url_https' => str_replace('normal', '400x400', $profile->data->user->legacy->profile_image_url_https)
        ];

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
        return json_decode($resp);
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

    private function checkIfTwitterAccountHasAllApis(TwitterAccount $twitterAccount, int $deckId): void
    {
        $apisCount = DB::table('apis')->select('id')->where('deck_id', $deckId)->count();
        $twitterAccountsCount = DB::table('twitter_account_apis')
            ->where('twitter_account_id', $twitterAccount->id)
            ->where('isActive', 1)
            ->count();

        if ($apisCount === $twitterAccountsCount) {
            $twitterAccount->status = 'active';
        } else {
            $twitterAccount->status = 'pending';
        }
        $twitterAccount->save();
    }


    public function makeRT(Request $request)
    {
        if (session('isTweeting') === true) {
            return response()->json([
                'error' => true,
                'message' => 'Ya tienes un tweet en proceso de RT'
            ]);
        }
        session(['isTweeting' => true]);

        $tweetId = $this->getTweetId($request);

        $this->verifyIfTweetHasAlreadyBeenTweeted($tweetId);

        //Define useful variables
        $deckId = $request->input('deckId');
        $user = auth()->user();

        if (!$user) {
            session(['isTweeting' => false]);
            abort(403);
        }

        //Get all RT deck's apis
        $apis = Api::where('deck_id', $deckId)
            ->where('type', 'rt')
            ->get();

        /* Deck verification starts */
        //Check if the deck has apis attached
        $this->verifyIfDeckHasApis($apis);

        //Pick a random api
        $selectedApi = $apis->random();

        /* ---------- THE REQUEST VERIFICATION STARTS --------*/

        /* User permissions verification starts */

        //Verify if the user belongs to the deck
        $this->verifyIfUserBelongsToTheDeck($user, $deckId);

        /*TwitterAccount verification starts, first retrieve it */
        $userTwitterAccount = TwitterAccount::where('deck_id', $deckId)
            ->where('user_id', $user->id)
            ->first();

        $deck = $selectedApi->deck;

        // Verify if the user has a twitter account attach to the deck
        $this->verifyIfUserHasTwitterAccountInTheDeck($userTwitterAccount, $deck->name);

        //Verify if the user's twitter account has all apis
        $this->verifyIfUserTwitterAccountStatusIsActive($userTwitterAccount);

        /* Business logic verification starts */


        //Check if user is time restricted in the current deck
        $this->verifyIfUserIsTimeRestricted($user, $deck);

        /* ---------- THE REQUEST VERIFICATION ENDS --------*/

        //Get all twitter accounts attached to that api
        $twitterAccountsApis = $selectedApi->twitterAccountApis;

        //Start a counter in order to track the successfully RT
        $totalTwitterAccountsApis = $twitterAccountsApis->count();
        $successRt = 0;
        $notRtBy = '';
        $extraInfo = [];
        //iterate over all accounts and make the rt from there
        foreach ($twitterAccountsApis as $twitterAccountApi) {
            //Create api connection and make RT post request
            $apiConnection = new TwitterOAuth($selectedApi->key, $selectedApi->secret, $twitterAccountApi->key, $twitterAccountApi->secret);
            $response = $apiConnection->post("statuses/retweet", ["id" => $tweetId]);

            if ($this->isError($response) === false) {

                //The request doesn't have errors, let's count it
                $successRt++;
            } else {
                //Store the information from the accounts that didn't RT
                $this->handleErrorInformation($twitterAccountApi, $notRtBy, $response, $extraInfo);

            }
        }

        //Save the record
        $this->createNewTweetRecord($deckId, $tweetId, $deck->delete_minutes, $successRt, $totalTwitterAccountsApis, $notRtBy, $extraInfo);
        session(['isTweeting' => false]);
        return response()->json(['successRT' => $successRt . '/' . $totalTwitterAccountsApis]);
    }


    /**
     * @param Request $request
     * @return array
     */
    private function getTweetId(Request $request): string
    {
        $tweetUrl = explode('/', $request->input('tweetURL'));
        return explode('?', end($tweetUrl))[0];
    }

    private function verifyIfTweetHasAlreadyBeenTweeted($tweetId): void
    {
        $system = DB::table('systems')
            ->select(['same_tweet_id_minutes'])
            ->first();
        if ($system !== null) {
            $same_tweet_id_minutes = $system->same_tweet_id_minutes;
        } else {
            $same_tweet_id_minutes = 15;
        }

        $record = DB::table('records')->where('tweet_id', $tweetId)
            ->where('created_at', '>=', Carbon::now()->subMinutes($same_tweet_id_minutes))
            ->orderBy('created_at', 'asc')
            ->get();

        if ($record->count() > 0) {
            session(['isTweeting' => false]);

            $nextHour = Carbon::parse($record[0]->created_at)->addMinutes($same_tweet_id_minutes);
            $remainingMinutes = $nextHour->diffInMinutes(Carbon::now());

            response()->json([
                'error' => true,
                'message' => 'Ya se ha hecho RT a este tweet. Estar?? disponible nuevamente en  ' . $remainingMinutes . ' minutos'])->send();
            die();

        }

    }

    private function verifyIfDeckHasApis($apis): void
    {

        if (count($apis) === 0) {
            session(['isTweeting' => false]);

            response()->json(['error' => true, 'message' => 'El deck no tiene apis registradas para dar RT'])->send();
            die();
        }
    }

    private function verifyIfUserBelongsToTheDeck(?\Illuminate\Contracts\Auth\Authenticatable $user, $deckId): void
    {

        if (!($user->belongsToDeck($deckId))) {
            session(['isTweeting' => false]);

            response()->json([
                'error' => true,
                'message' => 'Estas tratando de dar RT a un deck a el que no perteneces'
            ])->send();
            die();
        }
    }

    private function verifyIfUserHasTwitterAccountInTheDeck($userTwitterAccount, $deckName): void
    {

        if ($userTwitterAccount === null) {
            session(['isTweeting' => false]);

            response()->json(
                [
                    'error' => true,
                    'message' => 'No tienes ninguna cuenta vinculada al deck ' . $deckName
                ]
            )->send();
            die();
        }
    }

    private function verifyIfUserTwitterAccountStatusIsActive($userTwitterAccount): void
    {

        if ($userTwitterAccount->status !== 'active') {
            session(['isTweeting' => false]);

            response()->json(
                [
                    'error' => true,
                    'message' => 'Parece que estas intentando dar RT desde una cuenta que no cumple los requisitos. Por favor, revisa tus Apis en el deck ' . $userTwitterAccount->deck->name
                ])->send();
            die();
        }
    }

    private function verifyIfUserIsTimeRestricted($user, $deck)
    {
        $lastRecord = Record::where('username', $user->username)
            ->where('deck_id', $deck->id)
            ->where('created_at', '>=', Carbon::now()->subMinutes($deck->rt_minutes))
            ->orderBy('created_at', 'asc')
            ->get();
        if ($lastRecord->count() > 0) {
            session(['isTweeting' => false]);

            $nextHour = Carbon::parse($lastRecord[0]->created_at)->addMinutes($deck->rt_minutes);
            $remainingMinutes = $nextHour->diffInMinutes(Carbon::now());

            response()->json([
                'error' => true,
                'message' => 'Has excedido el numero m??ximo de RT para el deck ' . $deck->name . ' El pr??ximo RT estar?? disponible en ' . $remainingMinutes . ' minutos'])->send();
            die();

        }
    }

    public function isError($responseObject): bool
    {
        if (isset($responseObject->errors[0]->message)) {
            return true;
        }

        return false;
    }

    /**
     * @param $twitterAccountApi
     * @param string $notRtBy
     * @param $response
     * @param array $extraInfo
     */
    private function handleErrorInformation($twitterAccountApi, string &$notRtBy, $response, array &$extraInfo): void
    {
        $twitterAccount = $twitterAccountApi->twitterAccount;
        $notRtBy .= isset($twitterAccount->username) ? $twitterAccount->username . ',' : 'Usuario eliminado' . ',';
        $extraInfo[] = (object)[
            'username' => $twitterAccount->username ?? 'Usuario eliminado',
            'status_code' => $response->errors[0]->code,
            'message' => $response->errors[0]->message,
        ];
        //Now, check if the user has to re-authorize the api.
        self::checkAndSaveIfInvalidOrExpiredToken($response->errors[0]->code, $twitterAccountApi);
    }

    /**
     * Check and save twitterAccountApi Status if invalid or expired token.
     *
     * @param  $username
     * @param  $deckname
     * @param  $code status coe from api.
     * @return void
     */
    public static function checkAndSaveIfInvalidOrExpiredToken($statusCode, TwitterAccountApi $twitterAccountApi): void
    {
        if ($statusCode == 89 || $statusCode == 32) {
            $twitterAccountApi->isActive = false;
            $twitterAccountApi->save();
            $twitterAccount = $twitterAccountApi->twitterAccount;
            if ($twitterAccount) {
                $twitterAccount->status = 'pending';
                $twitterAccount->save();
            }

        }
    }

    /**
     * @param Request $request
     * @param $tweetId
     * @param int $successRt
     * @param $totalTwitterAccountsApis
     * @param string $notRtBy
     * @param array $extraInfo
     */
    private function createNewTweetRecord($deckId, $tweetId, $deleteMinutes, int $successRt, $totalTwitterAccountsApis, string $notRtBy, array $extraInfo): void
    {

        Record::create([
            'must_delete_at' => Carbon::now()->addMinutes($deleteMinutes),
            'username' => Auth::user()->username,
            'deck_id' => $deckId,
            'tweet_id' => $tweetId,
            'success_rt' => $successRt . '/' . $totalTwitterAccountsApis,
            'not_rt_by' => $notRtBy,
            'extra_info' => serialize($extraInfo),
            'pending' => true
        ]);
    }


    public function masterRT(Request $request)
    {
        if (!(auth()->user()->isOwner())) {
            abort(403, 'Eso tiliiin ');
        }
        session(['isTweeting' => false]);

        $request->validate([
            'deck_ids' => 'array'
        ]);

        if (session('isTweeting') === true) {
            return response()->json([
                'error' => true,
                'message' => 'Ya tienes un tweet en proceso de RT'
            ]);
        }
        $user = auth()->user();
        if (!$user) {
            session(['isTweeting' => false]);
            abort(403);
        }

        session(['isTweeting' => true]);

        $tweetId = $this->getTweetId($request);

        $this->verifyIfTweetHasAlreadyBeenTweeted($tweetId);
        $decks = Deck::find($request->input('deck_ids'));

        $globalCounter = 0;
        $successGlobalRt = 0;
        $excludedAccounts = [];
        foreach ($decks as $currentDeck) {
            //Define useful variables
            $deckId = $currentDeck->id;

            //Get all RT deck's apis
            $apis = Api::where('deck_id', $deckId)
                ->where('type', 'rt')
                ->get();

            /* Deck verification starts */
            //Check if the deck has apis attached if not, continue to next deck

            if (count($apis) === 0) {
                continue;
            }

            //Pick a random api
            $selectedApi = $apis->random();

            //Get all twitter accounts attached to that api
            $twitterAccountsApis = $selectedApi->twitterAccountApis;

            //Start a counter in order to track the successfully RT
            $totalTwitterAccountsApis = $twitterAccountsApis->count();
            $successRt = 0;
            $notRtBy = '';
            $extraInfo = [];
            //iterate over all accounts and make the rt from there
            foreach ($twitterAccountsApis as $twitterAccountApi) {
                $twitterAccount = $twitterAccountApi->twitterAccount;
                //Verify if there is a twitter account attach to that twitter_account_api.
                if (isset($twitterAccount)) {
                    //Check if the twitter account has already tweet this.
                    if (in_array($twitterAccount->username, $excludedAccounts, true)) {
                        continue;
                    }
                    $excludedAccounts[] = $twitterAccountApi->twitterAccount->username;
                } else {
                    continue;
                }

                $globalCounter++;
                //Create api connection and make RT post request
                $apiConnection = new TwitterOAuth($selectedApi->key, $selectedApi->secret, $twitterAccountApi->key, $twitterAccountApi->secret);
                $response = $apiConnection->post("statuses/retweet", ["id" => $tweetId]);

                if ($this->isError($response) === false) {
                    //The request doesn't have errors, let's count it
                    $successRt++;
                    $successGlobalRt++;
                } else {
                    //Store the information from the accounts that didn't RT
                    $this->handleErrorInformation($twitterAccountApi, $notRtBy, $response, $extraInfo);
                }
            }

            //Save the record
            $this->createNewTweetRecord($deckId, $tweetId, $request->input('delete_minutes'), $successRt, $totalTwitterAccountsApis, $notRtBy, $extraInfo);
        }
        session(['isTweeting' => false]);
        return redirect()->back()->withSuccess('Se han realizado ' . $successGlobalRt . '/' . $globalCounter . ' Tweets');
    }

    public function userMasterRT(Request $request)
    {

        //Verify that the user is logged.
        $user = auth()->user();
        if (!$user) {
            session(['isTweeting' => false]);
            abort(403);
        }

        if (!($user->isDonor())) {
            session(['isTweeting' => false]);

            return response()->json([
                'error' => true,
                'message' => 'Esta funci??n est?? disponible unicamente para usuarios que hayan hecho donaciones'
            ]);

        }

        if (session('isTweeting') === true) {
            return response()->json([
                'error' => true,
                'message' => 'Ya tienes un tweet en proceso de RT'
            ]);
        }

        //Verify that the user provide an array of index
        $request->validate([
            'deck_ids' => 'array'
        ]);
        //Start process
        session(['isTweeting' => true]);

        $tweetId = $this->getTweetId($request);
        //Verify if the tweet has not been tweeted before.
        $this->verifyIfTweetHasAlreadyBeenTweeted($tweetId);

        $decks = Deck::find($request->input('deck_ids'));

        //We will verify this for all the decks
        foreach ($decks as $possibleDeck) {

            //First, verify that the user belongs to the current deck
            $this->verifyIfUserBelongsToTheDeck($user, $possibleDeck->id);

            //Now check if the user it's time restricted
            $this->verifyIfUserIsTimeRestricted($user, $possibleDeck);

            /*TwitterAccount verification starts, first retrieve it */
            $userTwitterAccount = TwitterAccount::where('deck_id', $possibleDeck->id)
                ->where('user_id', $user->id)
                ->first();

            // Verify if the user has a twitter account attach to the deck
            $this->verifyIfUserHasTwitterAccountInTheDeck($userTwitterAccount, $possibleDeck->name);

            //Verify if the user's twitter account has all apis
            $this->verifyIfUserTwitterAccountStatusIsActive($userTwitterAccount);

        }

        $globalCounter = 0;
        $successGlobalRt = 0;
        $excludedAccounts = [];
        foreach ($decks as $currentDeck) {
            //Define useful variables
            $deckId = $currentDeck->id;

            //Get all RT deck's apis
            $apis = Api::where('deck_id', $deckId)
                ->where('type', 'rt')
                ->get();

            /* Deck verification starts */
            //Check if the deck has apis attached if not, continue to next deck

            if (count($apis) === 0) {
                continue;
            }

            //Pick a random api
            $selectedApi = $apis->random();

            //Get all twitter accounts attached to that api
            $twitterAccountsApis = $selectedApi->twitterAccountApis;

            //Start a counter in order to track the successfully RT
            $totalTwitterAccountsApis = $twitterAccountsApis->count();
            $successRt = 0;
            $notRtBy = '';
            $extraInfo = [];
            //iterate over all accounts and make the rt from there
            foreach ($twitterAccountsApis as $twitterAccountApi) {
                $twitterAccount = $twitterAccountApi->twitterAccount;
                //Verify if there is a twitter account attach to that twitter_account_api.
                if (isset($twitterAccount)) {
                    //Check if the twitter account has already tweet this.
                    if (in_array($twitterAccount->username, $excludedAccounts, true)) {
                        continue;
                    }
                    $excludedAccounts[] = $twitterAccountApi->twitterAccount->username;
                } else {
                    continue;
                }

                $globalCounter++;
                //Create api connection and make RT post request
                $apiConnection = new TwitterOAuth($selectedApi->key, $selectedApi->secret, $twitterAccountApi->key, $twitterAccountApi->secret);
                $response = $apiConnection->post("statuses/retweet", ["id" => $tweetId]);

                if ($this->isError($response) === false) {
                    //The request doesn't have errors, let's count it
                    $successRt++;
                    $successGlobalRt++;
                } else {
                    //Store the information from the accounts that didn't RT
                    $this->handleErrorInformation($twitterAccountApi, $notRtBy, $response, $extraInfo);
                }
            }

            //Save the record
            $this->createNewTweetRecord($deckId, $tweetId, 13, $successRt, $totalTwitterAccountsApis, $notRtBy, $extraInfo);
        }

        session(['isTweeting' => false]);
        return response()->json([
            'successRT' => $successGlobalRt . '/' . $globalCounter
        ]);
    }

    public function unrt()
    {

        $records = Record::with('deck')->where('pending', 1)->get();
        if ($records->count() === 0) {
            return 'No hay tweets que borrar';
        }
        foreach ($records as $record) {

            if (!($record->must_delete_at->isPast())) {
                echo nl2br("Aun no han pasado los minutos de borrado \n");
            } else { //The wait delete minutes has pass
                $deck = $record->deck;
                $deleteApi = Api::where('deck_id', $deck->id)
                    ->where('type', 'delete')
                    ->first();
                if ($deleteApi === null) {
                    echo nl2br("El deck " . $deck->name . "No tiene api de borrado \n");
                    continue;
                }

                //Tomamos los users del deck
                foreach ($deleteApi->twitterAccountApis as $twitterAccountApi) {
                    $connection = new TwitterOAuth($deleteApi->key, $deleteApi->secret, $twitterAccountApi->key, $twitterAccountApi->secret);
                    $request = $connection->post("statuses/unretweet", ["id" => $record->tweet_id]);

                    if (isset($request->errors[0])) {
                        self::checkAndSaveIfInvalidOrExpiredToken($request->errors[0]->code, $twitterAccountApi);
                        echo nl2br("Se present?? el siguiente error con el usuario :" . $twitterAccountApi->twitter_account_id . "\n");
                        var_dump($request);
                    } else {
                        echo nl2br("borrado, cuenta de twitter con id: " . $twitterAccountApi->twitter_account_id . "\n");
                    }
                }

                $record->pending = 0;
                $record->save();
            }
        }

        echo "listo";
    }

}
