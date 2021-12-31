<?php

namespace App\Http\Controllers\twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Api;
use App\Blockeduser;
use App\Deck;
use App\DeckUser;
use App\Http\Controllers\Controller;
use App\Record;
use App\Rt;
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

        $OAUTH_CALLBACK = "http://127.0.0.1:8000/callback";
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
        $callback = "https://www.feed-help.de/callback";

        //Create connection, identifying the api and retrieving the generated oauth info
        $connection = new TwitterOAuth($api->key, $api->secret, session('oauth_token'), session('oauth_token_secret'));

        //Finally get user Access Tokends
        $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $request['oauth_verifier']]);

        //Create twitter account record
        $extraInfo = $this->getAccountExtraInfo($access_token['screen_name']);

        $twitterAccount = TwitterAccount::updateOrCreate(
            [
                'deck_id' => $api->deck->id,
                'user_id' => auth()->user()->id,
            ],
            [
                'username' => $access_token['screen_name'],
                'followers' => $extraInfo['followers_count'],
                'image_url' => $extraInfo['profile_image_url_https'],
                'status' => 'pending',
            ]);

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
        $deckUser = DeckUser::where([['user_id', auth()->user()->id], ['deck_id', $api->deck->id]])->first();
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

    private function checkIfTwitterAccountHasAllApis(TwitterAccount $twitterAccount, int $deckId)
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

    public function reautorizar()
    {
        $user = Auth::user();
        $guardar = Decks_user::where([['username', $user->username], ['nombredeck', session('nombredeck')]])->first();
        $guardar->twitter = "";
        $guardar->save();
        echo "<script>window.close();</script>";
    }

    public function makeRT(Request $request)
    {
        $user = auth()->user();

        //Get all RT deck's apis
        $apis = Api::where('deck_id', $request->input('deckId'))
            ->where('type', 'rt')
            ->get();
        //Pick a random api
        $selectedApi = $apis->random();


        //Check if is owner, for not limiting any characteristic
        if (!$user->isOwner()) {

            return $this->RTFromOwner($request); // Makes the Rt if the user is an Owner
        }

        /* THE REQUEST VERIFICATION STARTS */

        //Verify if the user belongs to the deck
        if ($user->getDeckInfo($request->input('deckId'))['hasPermission'] === false) {
            return response()->json(['error' => true, 'message' => 'Ha ocurrido un error, eso es lo único que sabemos']);
        }

        $userTwitterAccount = TwitterAccount::where('deck_id', $request->input('deckId'))
            ->where('user_id', $user->id)
            ->first();

        // Verify if the user has a twitter account attach to the deck
        if ($userTwitterAccount === null) {
            return response()->json(['error' => true, 'message' => 'No tienes ninguna cuenta vinculada al deck']);
        }

        //Check if the user is time restricted
        $deck = $selectedApi->deck;

        $lastRecord = Record::where('username', $user->username)
            ->where('deck_id', $deck->id)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->orderBy('created_at', 'asc')
            ->get();


        if ($lastRecord->count() >= $deck->rt_number) {
            $nextHour = Carbon::parse($lastRecord[0]->created_at)->addHour();
            $remainingMinutes = $nextHour->diffInMinutes(Carbon::now());

            return response()->json([
                'error' => true,
                'message' => 'Has excedido tus RT/H. El próximo RT estará disponible en ' . $remainingMinutes . ' minutos']);

        }


        //Verify if the user's twitter account has all apis
        if ($userTwitterAccount->status !== 'active') {
            return response()->json(
                [
                    'error' => true,
                    'message' => 'Parece que estas intentando dar RT desde una cuenta que no cumple los requisitos. Por favor, revisa tus Apis'
                ]);
        }

        //Check if the deck has apis attached
        if (count($apis) === 0) {
            return response()->json(['error' => true, 'message' => 'El deck no tiene apis registradas para dar RT']);
        }
        //Ends verification

        //Get all twitter accounts attached to that api
        $twitterAccountsApis = $selectedApi->twitterAccountApis;

        //Start a counter in order to track the successfully RT
        $totalTwitterAccountsApis = $twitterAccountsApis->count();
        $successRt = 0;
        $notRtBy = '';
        $extraInfo = [];
        //iterate over all accounts and make the rt from there
        $tweetId = $this->getTweetId($request);
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

                // Check if the user has already authorize all apis in order to active it account.
                $this->checkIfTwitterAccountHasAllApis($userTwitterAccount, $deck->id);
            }
        }

        //Save the record
        $this->createNewTweetRecord($request, $tweetId, $successRt, $totalTwitterAccountsApis, $notRtBy, $extraInfo);

        return response()->json(['successRT' => $successRt . '/' . $totalTwitterAccountsApis]);
    }

    /**
     * RTFrom Owner. Same darRT method but has not time restriction or api dependence.
     *
     * @param Request $request
     * @return void
     */
    public function RTFromOwner(Request $request)
    {
        $aux = Deck::where('nombre', str_replace('_', ' ', $request->input('deckname')))->first();

        $consumer_key = $aux->crearkey;
        $consumer_secret = $aux->crearsecret;

        $auxx = explode('/', $request->input('rtid'));
        $tweet = end($auxx);

        $deck_name = $request->input('deckname');
        $guardarr = Decks_user::where(['nombredeck' => $deck_name])->get();
        $contador = 0;
        $total = 0;
        $quienes = [];
        $no = "";
        foreach ($guardarr as $guardar) {
            $access_token = [];
            $access_token['oauth_token'] = $guardar->crearkey;
            $access_token['oauth_token_secret'] = $guardar->crearsecret;
            $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

            $statues = $connection->post("statuses/retweet", ["id" => $tweet]);

            if ($this->isError($statues) == "no") {
                $contador++;
            } else {
                $no = $no . $guardar->twitter . ",";
                $c = new \stdClass();
                $c->twitter = $guardar->twitter;
                $c->codigo = $statues->errors[0]->code;
                $c->mensaje = $statues->errors[0]->message;
                array_push($quienes, $c);

                //Verificar si no tiene api aprobada, lo guarda en la base de datos.
                $this->checkAndSaveIfInvalidOrExpiredToken($guardar->username, $deck_name, $statues->errors[0]->code);
            }
            $total++;
        }

        $registro = new Rt;
        $registro->rtid = $tweet;
        $registro->deck = $deck_name;
        $registro->cuenta = Auth::user()->username;
        $registro->twitter = $no; //TERMINAR
        $registro->pendiente = "Si";
        $registro->cantidad = $contador . '/' . $total;
        $registro->quienes = serialize($quienes);
        $registro->save();

        return back()->with('total', $contador . '/' . $total);
    }

    public function isError($responseObject): bool
    {
        if (isset($responseObject->errors[0]->message)) {
            return true;
        }

        return false;
    }

    /**
     * Check and safe if invalid or expiren token. Guarda el usuario infractor.
     * Si ya existe, ignora el procedimiento.
     *
     * @param  $username
     * @param  $deckname
     * @param  $code status coe from api.
     * @return void
     */
    public static function checkAndSaveIfInvalidOrExpiredToken($statusCode, TwitterAccountApi $twitterAccountApi): void
    {
        if ($statusCode === 89 || $statusCode === 32) {
            $twitterAccountApi->isActive = false;
            $twitterAccountApi->save();
        }
    }

    /**
     * @param Request $request
     * @return array
     */
    private function getTweetId(Request $request): string
    {
        $tweetUrl = explode('/', $request->input('tweetURL'));
        return end($tweetUrl);
    }

    /**
     * @param $twitterAccountApi
     * @param string $notRtBy
     * @param $response
     * @param array $extraInfo
     */
    private function handleErrorInformation($twitterAccountApi, string &$notRtBy, $response, array &$extraInfo): void
    {
        $notRtBy .= $twitterAccountApi->twitterAccount->username . ',';
        $extraInfo[] = (object)[
            'username' => $twitterAccountApi->twitterAccount->username,
            'status_code' => $response->errors[0]->code,
            'message' => $response->errors[0]->message,
        ];
        //Now, check if the user has to re-authorize the api.
        self::checkAndSaveIfInvalidOrExpiredToken($response->errors[0]->code, $twitterAccountApi);
    }

    /**
     * @param Request $request
     * @param $tweetId
     * @param int $successRt
     * @param $totalTwitterAccountsApis
     * @param string $notRtBy
     * @param array $extraInfo
     */
    private function createNewTweetRecord(Request $request, $tweetId, int $successRt, $totalTwitterAccountsApis, string $notRtBy, array $extraInfo): void
    {
        Record::create([
            'username' => Auth::user()->username,
            'deck_id' => $request->input('deckId'),
            'tweet_id' => $tweetId,
            'success_rt' => $successRt . '/' . $totalTwitterAccountsApis,
            'not_rt_by' => $notRtBy,
            'extra_info' => serialize($extraInfo),
            'pending' => true
        ]);
    }

    public function darRT(Request $request)
    {

        if (Auth::user()->hasRole('Owner')) {

            return $this->RTFromOwner($request); // Makes the Rt if the user is an Owner
        }

        //Check if user is blocked.

        if ($this->isBlocked(Auth::user()->username, $request->input('deckname')) && !(Auth::user()->hasRole('consentido'))) {
            return back()->withErrors('Ups! parece que se cayó una de tus apis, por favor, re-vincula.');
        }

        //Get Twitter Object.
        $causante = Decks_user::where([['username', Auth::user()->username], ['nombredeck', $request->input('deckname')]])->first();

        //Check if user is time restricted
        if ($this->isTimeRestricted($causante->username, $request->input('deckname'))) {
            return back()->withErrors('Haz alcanzado el máximo de RT/H');
        }

        //Check if user has not aprobe it's apis.
        if ($causante->crearsecret == null || $causante->twitter == "") {
            return back()->withErrors('¿Estas tratando de dar RT sin tener apis aprobadas?');
        } else {
            //There is no error, user is ready for having it's RT.
            $aux = Deck::where('nombre', str_replace('_', ' ', $request->input('deckname')))->first();

            if ($aux->api3key == null) {
                $consumer_key = $aux->crearkey;
                $consumer_secret = $aux->crearsecret;
            } else {
                if ($aux->numero == null | $aux->numero == 1) {
                    $consumer_key = $aux->crearkey;
                    $consumer_secret = $aux->crearsecret;
                    $aux->numero = 2;
                    $controlador = $aux->numero;
                    $aux->save();
                } else {
                    $consumer_key = $aux->api3key;
                    $consumer_secret = $aux->api3secret;
                    $aux->numero = 1;
                    $aux->save();
                }
            }

            $controlador = $aux->numero;

            $tweet = $request->input('rtid');
            $deck_name = $request->input('deckname');
            $guardarr = Decks_user::where(['nombredeck' => $deck_name])->get();
            $contador = 0;
            $total = 0;
            $quienes = [];
            $no = "";
            foreach ($guardarr as $guardar) {

                $access_token = [];
                if ($controlador == 2) {
                    $access_token['oauth_token'] = $guardar->crearkey;
                    $access_token['oauth_token_secret'] = $guardar->crearsecret;
                } elseif ($controlador == null) {

                    $access_token['oauth_token'] = $guardar->crearkey;
                    $access_token['oauth_token_secret'] = $guardar->crearsecret;
                } else {

                    $access_token['oauth_token'] = $guardar->api3key;
                    $access_token['oauth_token_secret'] = $guardar->api3secret;
                }

                $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

                $statues = $connection->post("statuses/retweet", ["id" => $tweet]);

                if ($this->isError($statues) == "no") {
                    $contador++;
                } else {
                    $no = $no . $guardar->twitter . ",";
                    $c = new \stdClass();
                    $c->twitter = $guardar->twitter;
                    $c->codigo = $statues->errors[0]->code;
                    $c->mensaje = $statues->errors[0]->message;
                    array_push($quienes, $c);

                    //Verificar si no tiene api aprobada, lo guarda en la base de datos.
                    $this::checkAndSaveIfInvalidOrExpiredToken($guardar->username, $deck_name, $statues->errors[0]->code);
                }
                $total++;
            }

            //Save to the historial.
            $registro = new Rt;
            $registro->rtid = $tweet;
            $registro->deck = $deck_name;
            $registro->cuenta = Auth::user()->username;
            $registro->twitter = $no; //TERMINAR
            $registro->pendiente = "Si";
            $registro->cantidad = $contador . '/' . $total;
            $registro->quienes = serialize($quienes);
            $registro->save();

            return back()->with('total', $contador . '/' . $total);
        }
    }

    /**
     * IsBlocked. Determina si un usuario para determinado deckname
     * ha sido bloqueado debido a que no tiene autorizada la api
     *
     * @param string $username username
     * @param string $deck Nombre del deck en cuestion
     * @return boolean Verdadero si ha sido bloqueado, falso si esta libre de pecados
     */
    public function isBlocked($username, $deck)
    {
        $search = Blockeduser::where(['username' => $username, 'deck' => $deck])->count();
        return $search >= 1 ? true : false;
    }

    /**
     * IsTimeRestricted. Determina si un usuario tiene bloqueo de tiempo
     * debido a que ha realizado un RT en el deck hace menos de una hora.
     *
     * @param string $username Nombre de usuario
     * @param string $deck Nombre del deck
     * @return boolean Verdadero si esta bloqueado. False si esta libre de pecados
     */
    public function isTimeRestricted($username, $deck)
    {

        $posible = Rt::where([['cuenta', $username], ['deck', $deck]])->latest()->first();

        if ($posible != null) {

            if (($posible->created_at->diff(Carbon::now())->h) < 1) {
                return true; // User is blocked.
            }
        }
        return false; // User is not blocked.
    }

    public function hora()
    {
        $callback = "https://www.feed-help.de/callback";
        define('OAUTH_CALLBACK', $callback);

        $tweet = Rt::where('deck', 'SocialBoost')->latest()->get();

        foreach ($tweet as $posible) {
            $diferencia = $posible->updated_at->diff(Carbon::now());

            if (($diferencia->h > 1) || ($diferencia->i > 10) || 1 == 1) { //HA pasado mas de diez minutos

                $deck_name = $posible->deck;
                $aux = Deck::where('nombre', str_replace('_', ' ', $deck_name))->first();
                $consumer_key = $aux->borrarkey;
                $consumer_secret = $aux->borrarsecret;

                $borrame = Decks_user::where('nombredeck', $deck_name)->get(); //Tomamos los users del deck
                $infractores = [];
                $no = "";
                foreach ($borrame as $guardar) {
                    $access_token = [];
                    $access_token['oauth_token'] = $guardar->borrarkey;
                    $access_token['oauth_token_secret'] = $guardar->borrarsecret;
                    $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

                    $statues = $connection->post("statuses/unretweet", ["id" => $posible->rtid]);
                    echo "borrado" . $guardar->username . "\n";


                    if (isset($statues->errors[0])) {
                        $echo = "Se ha detectado un error de api con: " . $guardar->twitter;
                    } else {

                        if ($statues->retweeted == false) {
                            $no = $no . $guardar->twitter . ",";

                            /*
                            $c->codigo = $statues->errors[0]->code;
                            $c->mensaje = $statues->errors[0]->message;
                            array_push($infractores,$c);
    */
                            echo "vamos bien";
                        }
                    }
                }


                $posible->infractores = $no;
                $posible->save();
            }
        }

        echo "listo";
    }

    public function unrt()
    {
        $callback = "https://www.feed-help.de/callback";
        define('OAUTH_CALLBACK', $callback);

        $tweet = Rt::where('pendiente', 'Si')->get();

        foreach ($tweet as $posible) {

            $diferencia = $posible->updated_at->diffInMinutes(Carbon::now());
            if ($diferencia > $posible->minutos) { //HA pasado mas de diez minutos

                $deck_name = $posible->deck;

                $aux = Deck::where('nombre', str_replace('_', ' ', $deck_name))->first();

                if ($aux == null) {
                    continue;
                }

                $consumer_key = $aux->borrarkey;
                $consumer_secret = $aux->borrarsecret;

                $borrame = Decks_user::where('nombredeck', $deck_name)->get();

                //Tomamos los users del deck
                $no = "";
                foreach ($borrame as $guardar) {
                    //if(!($guardar->nombredeck == "MM_Deck")){


                    $access_token = [];
                    $access_token['oauth_token'] = $guardar->borrarkey;
                    $access_token['oauth_token_secret'] = $guardar->borrarsecret;
                    $connection = new TwitterOAuth($consumer_key, $consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

                    $statues = $connection->post("statuses/unretweet", ["id" => $posible->rtid]);
                    var_dump($statues);

                    echo "borrado" . $guardar->username . "\n";


                    //DETECCION DE INFRACTORES
                    if (isset($statues->errors[0])) {
                    } else {

                        if ($statues->retweeted == false) {
                            $no = $no . $guardar->twitter . ",";
                        }
                    }


                    //FINALIZA LA DETECCION DE INFRACTORES
                }

                $posible->pendiente = "No";
                $posible->infractores = $no;

                $posible->save();
            }
        }

        // }

        echo "listo";
    }

    public function limite($id)
    {
        $aux = Deck::where('nombre', str_replace('_', ' ', $id))->first();

        $consumer_key = $aux->crearkey;
        $consumer_secret = $aux->crearsecret;

        $guardar = Decks_user::where([['nombredeck', $id], ['username', Auth::user()->username]])->first();

        $connection = new TwitterOAuth($consumer_key, $consumer_secret, $guardar->crearkey, $guardar->crearsecret);
        // obtener datos usuario user = $connection->get('account/verify_credentials', ['tweet_mode' => 'extended', 'include_entities' => 'true']);
        $statues = $connection->get("application/rate_limit_status", ["resources" => "statuses"]);
        var_dump($statues);
        echo "hi";
    }
}
