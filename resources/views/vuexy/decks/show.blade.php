@extends('vuexy.template')

@section('content')
    <!-- Basic Tables start -->
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="d-flex justify-content-end " style="margin-bottom: 15px">

                <button class="btn btn-primary btn-sm me-1"
                        data-bs-toggle="modal"
                        data-bs-target="#RTModal">
                    <i data-feather="twitter"></i>
                    <span>Nuevo RT</span>
                </button>

                <a class="btn btn-primary btn-sm me-1" href="{{route('decks.apis.verify',['deckId'=>$deck->id])}}">
                    <i data-feather="settings"></i>
                    <span>Gestionar apis</span>
                </a>

                <a class="btn btn-primary btn-sm me-1" href="{{$deck->whatsapp_group_url}}"
                   target="_blank">
                    <i data-feather="send"></i>
                    <span>Grupo de Whatsapp</span>
                </a>

                <a class="btn btn-primary btn-sm me-1" href="{{route('decks.records',['deckId'=>$deck->id])}}">
                    <i data-feather="book-open"></i>
                    <span>Historial del Deck</span>
                </a>
                @if($hasPermission === true)

                    <a class="btn btn-primary btn-sm" href="{{route('decks.edit',['deck'=>$deck->id])}}">
                        <i data-feather="tool"></i>
                        <span>Gestionar deck</span>
                    </a>
                @endif

            </div>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between w-100">

                        <h4 class="card-title">Deck: {{$deck->icon}} {{$deck->name}}</h4>
                        @if($hasPermission === true)

                            <div>
                                <button type="button" class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addUserModal">
                                    <i data-feather="user-plus"></i>
                                    Agregar usuario
                                </button>
                            </div>
                        @endif
                    </div>

                </div>
                <div class="card-body">
                    <p class="card-text">
                        Número total de seguidores: {{$deck->followers}}
                    </p>

                </div>


                <div class="table-responsive">

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Usuario</th>
                            <th>Seguidores</th>
                            @if($hasPermission === true)
                                <th>Acciones</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($deckUsers as $user)
                            <tr>
                                <td>
                                    @if($user->twitterStatus === null)
                                        <span class="badge rounded-pill badge-light-danger me-1">Inactivo</span>
                                    @elseif($user->twitterStatus === 'active')
                                        <span class="badge rounded-pill badge-light-primary me-1">Activo</span>
                                    @else
                                        <span class="badge rounded-pill badge-light-warning me-1">Pendiente</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-1">
                                            <img
                                                style="width: 32px; height:32px"
                                                class="rounded-circle"
                                                src="{{$user->image_url ?? asset('2.0/app-assets/images/avatars/default.png')}}"
                                                alt="avatar">
                                        </div>
                                        <div>
                                              <span class="font-weight-bold d-block text-nowrap">
                                                {{$user->userUsername}}
                                            </span>
                                            <small
                                                class="text-muted">{{$user->twitterUsername ?? 'No vinculado'}} </small>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $user->twitterFollowers ?? 0}}</td>
                                @if($hasPermission === true)

                                    <td>
                                        <form
                                            action="{{route('decks.users.delete',['deckId' => $deck->id,'userId' => $user->userId])}}"
                                            method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-icon btn-outline-primary waves-effect">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-trash">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- Basic Tables end -->

    @if($hasPermission === true)

        <!-- COMIENZA LA SECCIÓN DE LOS MODALES-->
        <!-- add new card modal  -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h1 class="text-center mb-1" id="addNewCardTitle">Añadir usuario</h1>

                        <!-- form -->
                        <form class="row gy-1 gx-2 mt-75" method="POST"
                              action="{{route('decks.users.store',['deckId' => $deck->id])}}">
                            @csrf

                            <div class="col-12">
                                <label class="form-control-label" for="user_username">Nombre de usuario (FeedDeck)</label>
                                <input type="text" class="form-control" name="user_username" id="user_username">
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="role">Rol del usuario</label>
                                <select class="form-select form-control"
                                        id="role" name="role">
                                    <option value="user" selected>Usuario</option>
                                    <option value="admin">Administrador ⚠️</option>
                                </select>
                                <small class="mt-1 d-block">Ten cuidado al añadir un administrador al deck, este tiene
                                    privilegios y podría
                                    causar daños irremediables en el deck</small>
                            </div>


                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-1 mt-1">Añadir usuario</button>
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
    @endif
    <!-- create app modal -->
    <div class="modal fade" id="RTModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-3 px-sm-3">
                    <h1 class="text-center mb-1" id="createAppTitle">Dar un nuevo Retweet</h1>
                    <p class="text-center mb-2">Este Tweet será publicado en todas las cuentas activas del Deck</p>

                    <!-- content -->

                    <div class="d-flex flex-column justify-content-center" role="tabpanel">
                        <div>
                            <label for="tweetId" class="mb-1">URL del Tweet </label>
                            <input class="form-control" type="text"
                                   placeholder="https://twitter.com/iMensajex/status/1394837941264990211"
                                   id="tweetId" name="tweetId"/>
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

                            <button class="btn btn-primary btn-next" onclick="resetModal()" id="resetButton"
                                    style="display: none">
                                <span class="align-middle d-sm-inline-block d-none">Hacer otro RT</span>
                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                            </button>

                            <button class="btn btn-primary btn-next" onclick="makeRT()" id="submitButton">
                                <span class="align-middle d-sm-inline-block d-none">Hacer RT</span>
                                <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
                            </button>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- / create app modal -->


    <!-- show error modal  -->
    <div class="modal fade modal-danger" id="errorModal" tabindex="-1" aria-labelledby="addNewCardTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <button type="button" class="btn btn-danger waves-effect waves-float waves-light"
                                    data-bs-dismiss="modal">Entendido, gracias
                            </button>
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>
    <!--/  show error modal  -->

    <!-- TERMINA LA SECCIÓN DE LOS MODALES-->

    <script>
        async function makeRTRequest(tweetUrl) {
            let url = '{{route('makeRT')}}';
            let data = {
                'tweetURL': tweetUrl,
                'deckId': {{$deck->id}},
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
            resetButton.style.display = 'block';
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

            let tweetURLField = document.getElementById('tweetId');
            let status = document.getElementById('status');
            let message = document.getElementById('message');
            let submitButton = document.getElementById('submitButton');
            let resetButton = document.getElementById('resetButton');
            let errorMessage = document.getElementById('errorMessage');
            let retweetModal = bootstrap.Modal.getInstance((document.getElementById('RTModal')));

            let tweetUrl = tweetURLField.value;
            if (!isValidTwitterURL(tweetUrl)) {
                let errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                console.log(errorModal)
                errorMessage.innerText = '"Debes introducir una URL de un tweet válido"';
                errorModal.show();
                return;
            }

            //Change the status to processing
            changeModalStatusToProcessing(tweetURLField, submitButton, status, message);

            //Realizar la petición
            let responseData = await makeRTRequest(tweetUrl);

            //Check for errors
            if (responseData.error === true) {
                let errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorMessage.innerText = '"' + responseData.message + '"';
                errorModal.show();
                retweetModal.hide();
                changeModalStatusToNewRT(tweetURLField, submitButton, status, message);
                return;
            }

            changeModalStatusToEnd(tweetURLField, submitButton, status, message, responseData.successRT);

        }

        function isValidTwitterURL(url) {

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
