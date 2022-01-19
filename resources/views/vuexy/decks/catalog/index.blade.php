@extends('vuexy.template')

@section('content')
    <div class="card">
        <div class="card-body">
            <h2>
                Catalogo de decks
            </h2>
            <p>
                ¿Quieres unirte a un nuevo Deck? te presentamos el catálogo de decks 😎.
            </p>
            <ol>
                <li>
                    Navega através de la lista de Decks públicos. Si cumples los requisitos para unirte, da clic en
                    "Solicitar unirme".
                </li>
                <li>
                    Autorizar el API con <strong>LA CUENTA DE TWITTER QUE PLANEAS USAR EN EL DECK.</strong>
                </li>
                <li>
                    El administrador recibirá tu solicitud. En caso de aceptarla, serás añadido automáticamente al deck
                </li>

            </ol>
            <p>
                Nota: Es muy importante que realices en proceso con la cuenta de twitter que planeas usar en el deck, pues
                <strong>esta será la que el administrador valorará a la hora de aceptar tu solicitud</strong>. Unicamente estás autorizado a
                usar <strong>esa cuenta de Twitter.</strong> En dado caso de que vincules una cuenta diferente, <strong>serás expulsado automáticamente
                del deck y baneado de FeedDeck permanentemente 🚫 </strong>. Utiliza esta función bajo tu propio riesgo.
            </p>

        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <div class="user-avatar-section">
                        <div class="d-flex align-items-center flex-column">
                                <span class="text-xl-center h1">
                                    🤩
                                </span>

                            <div class="user-info text-center">
                                <h2>𝐁𝐚𝐝 𝐛𝐮𝐧𝐧𝐲</h2>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-around my-2 pt-75">
                        <div class="d-flex align-items-start me-2">
                                            <span class="badge bg-light-primary p-75 rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-check font-medium-2"><polyline
                                                        points="20 6 9 17 4 12"></polyline></svg>
                                            </span>
                            <div class="ms-75">
                                <h4 class="mb-0">150k</h4>
                                <small>Requisito</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                                            <span class="badge bg-light-primary p-75 rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-twitter font-medium-2"><path
                                                        d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                                            </span>
                            <div class="ms-75">
                                <h4 class="mb-0">6.001.670</h4>
                                <small>Total seguidores</small>
                            </div>
                        </div>
                    </div>
                    <h4 class="fw-bolder border-bottom pb-50 mb-1">Detalles</h4>
                    <div class="info-container">
                        <ul class="list-unstyled">
                            <li class="mb-75">
                                <span class="fw-bolder me-25">Admin:</span>
                                <span>fernando</span>
                            </li>
                            <li class="mb-75">
                                <span class="fw-bolder me-25">Requisito:</span>
                                <span>150k seguidores</span>
                            </li>

                            <li class="mb-75">
                                <span class="fw-bolder me-25">Minutos para RT:</span>
                                <span>60</span>
                            </li>
                            <li class="mb-75">
                                <span class="fw-bolder me-25">Minutos para borrado:</span>
                                <span>15 minutos</span>
                            </li>

                            <li class="mb-75">
                                <span class="fw-bolder me-25">Usuarios:</span>
                                <span>20</span>
                            </li>

                        </ul>

                        <div class="d-flex justify-content-center pt-1">
                            <button class="btn btn-primary me-1 waves-effect waves-float waves-light"
                                    data-bs-target="#editDeckModal" data-bs-toggle="modal">
                                <i data-feather="star"></i>
                                Solicitar unirme
                            </button>
                            <button class="btn btn-success me-1 waves-effect waves-float waves-light"
                                    data-bs-target="#editDeckModal" data-bs-toggle="modal">
                                <i data-feather="send"></i>
                                Escribir al Owner
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
