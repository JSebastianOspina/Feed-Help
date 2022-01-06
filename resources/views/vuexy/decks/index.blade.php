@extends('vuexy.template')

@section('content')
    <!-- Basic Tables start -->
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Decks</h4>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        Estos son los Decks a los que perteneces. Si deseas unirte a uno, por favor comunicate con el
                        administrador para que te añada al Deck en cuestión.
                    </p>
                </div>
                @if(auth()->user()->isOwner())

                    <div class="d-flex justify-content-end px-2 mb-3">
                        <button type="button" class="btn btn-primary waves-effect waves-float waves-light me-1"
                                data-bs-toggle="modal"
                                data-bs-target="#createDeckModal">
                            Crear nuevo Deck
                        </button>
                        <button type="button" class="btn btn-primary brtwaves-effect waves-float waves-light "
                                data-bs-toggle="modal"
                                data-bs-target="#RTMasterModal">
                            RT Master
                        </button>
                    </div>

                    <!-- rt master modal  -->
                    <div class="modal fade" id="RTMasterModal" tabindex="-1" aria-labelledby="addNewCardTitle"
                         aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-transparent">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body px-sm-5 mx-50 pb-5">
                                    <h1 class="text-center mb-1" id="addNewCardTitle">RT MASTER</h1>
                                    <p class="text-center">Privilegio para Owner </p>

                                    <!-- form -->
                                    <form class="row gy-1 gx-2 mt-75" method="POST" action="{{route('masterRT')}}">
                                        @csrf

                                        <div class="col-12">
                                            <label class="form-label" for="tweetURL">URL del Tweet</label>
                                            <input type="text" class="form-control" name="tweetURL" id="tweetURL"
                                                   required>

                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="deck_ids">Selecciona los decks</label>

                                            <select class="form-select " multiple="multiple"
                                                    id="deck_ids" name="deck_ids[]">
                                                @foreach($decks as $deck)
                                                    <option value="{{$deck->id}}" selected>{{$deck->name}}</option>
                                                @endforeach

                                            </select>

                                        </div>

                                        <div class="col-12">
                                            <label class="form-label" for="delete_minutes">Minutos para borrar
                                                RT</label>
                                            <input type="number" class="form-control" name="delete_minutes"
                                                   id="delete_minutes"
                                                   min="10" required value="10">
                                        </div>

                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-primary me-1 mt-1">Hacer RT master
                                            </button>
                                            <button type="reset" class="btn btn-outline-secondary mt-1"
                                                    data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                Cancelar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ rt master modal  -->
                @endif

                <div class="d-flex justify-content-end px-2 mb-3">

                    <button class="btn btn-outline-warning" data-bs-toggle="modal"
                            data-bs-target="#UserRTMasterModal">
                            <span class="me-50">
                               👑
                            </span>
                        Master RT
                    </button>

                </div>

                <!-- user rt master modal  -->
                <div class="modal fade" id="UserRTMasterModal" tabindex="-1" aria-labelledby="addNewCardTitle"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-transparent">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body px-sm-5 mx-50 pb-5">
                                <h1 class="text-center mb-1" id="addNewCardTitle">RT MASTER</h1>
                                <p class="text-center">Esta herramienta te permite dar RT en todos los decks a los que
                                    perteneces <strong>con un solo clic</strong> siempre y cuando estés bien vinculado
                                </p>
                                <!-- form -->
                                @csrf

                                <div class="col-12">
                                    <label class="form-label" for="tweetURL">URL del Tweet</label>
                                    <input type="text" class="form-control" name="tweetURL" id="tweetURL"
                                           required>

                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="deck_ids">Selecciona los decks</label>

                                    <select class="form-select " multiple="multiple"
                                            id="deck_ids" name="deck_ids[]">
                                        @foreach($decks as $deck)
                                            <option value="{{$deck->id}}" selected>{{$deck->name}}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <h5 class="mt-2 pt-1">Estado actual</h5>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                       <span class="avatar avatar-tag bg-light-info me-1" id="test">
                                           <i data-feather="clock" class="font-medium-5" id="icon"></i>
                                       </span>
                                        <div class="d-flex flex-column">
                                         <span class="h5 d-block fw-bolder" id="status">
                                             En espera de tweet
                                         </span>
                                            <span id="message">
                                            Por favor, introduce la URL del tweet para empezar
                                        </span>
                                        </div>

                                    </div>
                                    <div>
                                        <div
                                            class="spinner-grow spinner-grow-sm  text-success me-50"
                                            style="animation-duration: 1s" id="animation"></div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-2">

                                    <button class="btn btn-primary btn-next" onclick="makeRT()" id="submitButton">
                                        <span class="align-middle d-sm-inline-block d-none">Hacer RT Master</span>
                                        <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ user rt master modal  -->


                <!-- show error modal  -->
                <div class="modal fade modal-danger" id="errorModal" tabindex="-1" aria-labelledby="addNewCardTitle"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
                        <div class="modal-content">
                            <div class="modal-header bg-transparent">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body px-sm-5 mx-50 pb-5">
                                <div class="d-flex flex-column justify-content-center h-100">
                                    <h1 class="text-center " id="addNewCardTitle">¡Upa, ha ocurrido un error!</h1>
                                    <h4 class="text-left mt-2">
                                        Nuestro equipo de monos se encontraba trabajando fuertemente en tu petición ...
                                    </h4>
                                    <h4 class="text-left my-1">
                                        Sin embargo, el mono negro de la derecha detectó un error.
                                    </h4>
                                    <div class="w-md-50 text-center">
                                        <img src="https://i.giphy.com/media/xT5LMrxYauvZhhzL6U/giphy.webp" alt=""
                                             class="img-fluid">
                                    </div>

                                    <h4 class="text-left mt-2">
                                        El mono dijo:
                                    </h4>
                                    <h4 id="errorMessage">....</h4>
                                    <div class="d-flex justify-content-center mt-1">
                                        <button type="button"
                                                class="btn btn-danger waves-effect waves-float waves-light"
                                                data-bs-dismiss="modal">Entendido, gracias
                                        </button>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>
                <!--/  show error modal  -->

                <div class="table-responsive">
                    @if(count($decks)>0)

                        <table class="table">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Estado</th>
                                <th>Seguidores</th>
                                <th>Minutos para RT</th>
                                <th>Minutos para borrado</th>
                                <th>Administrador</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($decks as $deck)
                                <tr onclick="window.location.href = '{{route('decks.show',['deck'=>$deck->id])}}'"
                                    class="cursor-pointer">
                                    <td>
                                        <span class="me-2">{{$deck->icon}}</span>
                                        <span class="fw-bold">{{$deck->name}}</span>
                                    </td>
                                    <td>
                                        @if($deck->enabled === 1)
                                            <span class="badge rounded-pill badge-light-primary me-1">Activo</span>
                                        @else
                                            <span class="badge rounded-pill badge-light-danger me-1">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>{{number_format($deck->followers,0,',','.')}}</td>
                                    <td>{{$deck->rt_minutes}}</td>
                                    <td>{{$deck->delete_minutes}}</td>
                                    <td>{{$deck->owner_name}}</td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Basic Tables end -->

    @if(auth()->user()->isOwner())
        <!-- COMIENZA LA SECCIÓN DE LOS MODALES-->
        <!-- add new card modal  -->
        <div class="modal fade" id="createDeckModal" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h1 class="text-center mb-1" id="addNewCardTitle">Nuevo Deck</h1>
                        <p class="text-center">Este es el comienzo de algo grande 🤩</p>

                        <!-- form -->
                        <form class="row gy-1 gx-2 mt-75" method="POST" action="{{route('decks.store')}}">
                            @csrf

                            <div class="col-12">
                                <label class="form-label" for="icon">Icono del Deck</label>
                                <input type="text" class="form-control" name="icon" id="icon">
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="name">Nombre del deck</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>

                            <div class="col-12">
                                <label class="form-control-label" for="owner_username">Administrador</label>
                                <input type="text" class="form-control" name="owner_username" id="owner_username">
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="rt_minutes">Minutos para RT</label>
                                <input type="number" class="form-control" name="rt_minutes" id="rt_minutes" value="60"
                                       min="60">
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="delete_minutes">Minutos para borrado</label>
                                <input type="number" class="form-control" name="delete_minutes" id="delete_minutes"
                                       value="10"
                                       step="1" min="10">
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-1 mt-1">Crear Deck</button>
                                <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal"
                                        aria-label="Close">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ add new card modal  -->

        <!-- TERMINA LA SECCIÓN DE LOS MODALES-->
    @endif

    <script>

        async function makeRTRequest(tweetUrl) {

            let htmlDecksIds = document.getElementById('deck_ids');
            let options = Array.from(htmlDecksIds.options);
            let selectedOptions = options.filter(function (currentOption) {
                return currentOption.selected;
            })
            let decksIds = selectedOptions.map(function (currentSelectedOption) {
                return currentSelectedOption.value;
            })

            let url = '{{route('userMasterRT')}}';
            let data = {
                'tweetURL': tweetUrl,
                'deck_ids': decksIds,
                '_token': '{{csrf_token()}}'
            }
            let response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            return await response.json();
        }

        function changeModalStatusToProcessing(tweetURLField, submitButton, status, message) {
            tweetURLField.setAttribute('readonly', 'true');
            submitButton.setAttribute('disabled', 'disabled');
            status.innerText = 'Procesando petición';
            message.innerText = 'Estamos trabajando en tu Tweet, pronto verás los resultados...';
        }

        function changeModalStatusToNewRT(tweetURLField, submitButton, status, message) {
            tweetURLField.removeAttribute('readonly');
            submitButton.removeAttribute('disabled');
            status.innerText = 'En espera de tweet';
            message.innerText = 'Por favor, introduce la URL del tweet para empezar';
        }

        function changeModalStatusToEnd(tweetURLField, submitButton, status, message, successRT) {
            status.innerText = 'Retweet finalizado';
            message.innerText = 'Se obtuvo un resultado de ' + successRT + ' RT';
            document.getElementById('animation').style.display = 'none';
            submitButton.style.display = 'none';
        }

        function resetModal() {
            let tweetURLField = document.getElementById('tweetId');
            let status = document.getElementById('status');
            let message = document.getElementById('message');
            let submitButton = document.getElementById('submitButton');
            let resetButton = document.getElementById('resetButton');

            tweetURLField.value = '';
            submitButton.style.display = 'block';
            resetButton.style.display = 'none';
            document.getElementById('animation').style.display = 'block';

            changeModalStatusToNewRT(tweetURLField, submitButton, status, message);

        }

        async function makeRT() {

            //Capture all necessary DOM documents.

            let tweetURLField = document.getElementById('tweetURL');
            let status = document.getElementById('status');
            let message = document.getElementById('message');
            let submitButton = document.getElementById('submitButton');
            let errorMessage = document.getElementById('errorMessage');
            let errorModal = new bootstrap.Modal(document.getElementById('errorModal'));

            let tweetUrl = tweetURLField.value;
            if (!isValidTwitterURL(tweetUrl)) {
                console.log(errorModal)
                errorMessage.innerText = '"Debes introducir una URL de un tweet válido"';
                errorModal.show();
                return;
            }

            //Change the status to processing
            changeModalStatusToProcessing(tweetURLField, submitButton, status, message);

            //Realizar la petición
            try {
                let responseData = await makeRTRequest(tweetUrl);
                //Check for errors
                if (responseData.error === true) {
                    let errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorMessage.innerText = '"' + responseData.message + '"';
                    errorModal.show();
                    changeModalStatusToNewRT(tweetURLField, submitButton, status, message);
                    return;
                } else {

                    changeModalStatusToEnd(tweetURLField, submitButton, status, message, responseData.successRT);
                }
            } catch (e) {
                errorMessage.innerText = '"' + 'Ocurrió un error interno en el servidor, la culpa es nuestra. Por favor, comunicate con el owner del deck' + '"';
                errorModal.show();
                changeModalStatusToNewRT(tweetURLField, submitButton, status, message);
            }


        }

        function isValidTwitterURL(url) {
            console.log(url)
            try {
                let tweetUrl = new URL(url);
                if (!tweetUrl.host.includes('twitter')) {
                    return false;
                }
            } catch (_) {
                return false;
            }

            return true;
        }
    </script>


@endsection
