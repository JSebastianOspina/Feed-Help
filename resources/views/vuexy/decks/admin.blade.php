@extends('vuexy.template')

@section('content')


    <div class="content-wrapper container-xxl p-0">

        <div class="content-body">
            <section class="app-user-view-billing">
                <div class="row">
                    <!-- User Sidebar -->
                    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                        <!-- User Card -->
                        <div class="card">
                            <div class="card-body">
                                <div class="user-avatar-section">
                                    <div class="d-flex align-items-center flex-column">
                                        <img class="img-fluid rounded mt-3 mb-2"
                                             src="https://i.pravatar.cc/110"
                                             height="110"
                                             width="110" alt="User avatar"/>

                                        <div class="user-info text-center">
                                            <h4>{{$deck->name}}</h4>
                                            <span class="badge bg-light-secondary">{{$deck->owner_name}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-around my-2 pt-75">
                                    <div class="d-flex align-items-start me-2">
                                            <span class="badge bg-light-primary p-75 rounded">
                                                <i data-feather="check" class="font-medium-2"></i>
                                            </span>
                                        <div class="ms-75">
                                            <h4 class="mb-0">{{$deck->min_followers}}k</h4>
                                            <small>Requisito</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start">
                                            <span class="badge bg-light-primary p-75 rounded">
                                                <i data-feather="twitter" class="font-medium-2"></i>
                                            </span>
                                        <div class="ms-75">
                                            <h4 class="mb-0">{{$deck->followers}}k</h4>
                                            <small>Total seguidores</small>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="fw-bolder border-bottom pb-50 mb-1">Detalles</h4>
                                <div class="info-container">
                                    <ul class="list-unstyled">
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Admin:</span>
                                            <span>{{$deck->owner_name}}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Requisito:</span>
                                            <span>{{$deck->min_followers}}k seguidores</span>
                                        </li>

                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Minutos para RT:</span>
                                            <span>{{$deck->rt_minutes}}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Minutos para borrado:</span>
                                            <span>{{$deck->delete_minutes}} minutos</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Total Apis:</span>
                                            <span>{{$deck->apis->count()}}</span>
                                        </li>
                                        <li class="mb-75">
                                            <span class="fw-bolder me-25">Usuarios:</span>
                                            <span>{{$deck->twitterAccounts->count()}}</span>
                                        </li>

                                    </ul>
                                    @if($canEditDeck)
                                        <div class="d-flex justify-content-center pt-2">
                                            <button class="btn btn-primary me-1" data-bs-target="#editDeckModal"
                                                    data-bs-toggle="modal">
                                                Editar deck
                                            </button>

                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- /User Card -->

                    </div>
                    <!--/ User Sidebar -->

                    <!-- User Content -->
                    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                        <!-- User Pills -->

                        <div class="d-flex justify-content-end" style="margin-bottom: 15px">


                            <a class="btn btn-primary btn-sm me-1" href="{{route('decks.show',['deck'=>$deck->id])}}">
                                <i data-feather="user"></i>
                                <span>Gestionar usuarios</span>
                            </a>

                            <a class="btn btn-primary btn-sm me-1"
                               href="{{route('decks.apis.verify',['deckId'=>$deck->id])}}">
                                <i data-feather="settings"></i>
                                <span>Gestionar apis</span>
                            </a>

                        </div>
                        <!--/ User Pills -->


                        <!-- gestion de apis -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-50">Gestión de apis</h4>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#addNewCard">
                                    <i data-feather="plus"></i>
                                    <span>Añadir API</span>
                                </button>
                            </div>

                            @foreach($deck->apis as $api)
                                <div class="card-body">
                                    <div class="added-cards">
                                        <div class="cardMaster rounded border p-2 mb-1">
                                            <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                <div class="card-information">
                                                    <div class="d-flex align-items-center mb-50">
                                                        <h6 class="mb-0">{{$api->name}}</h6>
                                                        @if($api->type === 'rt')
                                                            <span class="badge badge-light-success ms-50">
                                                                Retweet
                                                            </span>
                                                        @else
                                                            <span class="badge badge-light-danger ms-50">
                                                                Borrado
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="card-number">Api Key: {{$api->key}}</span>
                                                </div>
                                                <div class="d-flex flex-column text-start text-lg-end">
                                                    <div class="d-flex order-sm-0 order-1 mt-1 mt-sm-0">
                                                        <button class="btn btn-outline-primary me-75"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editApi{{$api->id}}Modal">
                                                            Editar
                                                        </button>
                                                        <form
                                                            action="{{route('decks.apis.delete',['deckId'=>$deck->id, 'apiId'=> $api->id])}}"
                                                            method="POST">
                                                            @csrf
                                                            @method('delete')
                                                            <button
                                                                type="submit"
                                                                class="btn btn-outline-secondary">Borrar
                                                            </button>

                                                        </form>
                                                    </div>
                                                    <span class="mt-2">Editada el: {{$api->updated_at}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- edit current api modal -->
                                <div class="modal fade" id="editApi{{$api->id}}Modal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header bg-transparent">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body px-sm-5 mx-50 pb-5">
                                                <h1 class="text-center mb-1" id="addNewCardTitle">Editar API</h1>
                                                <p class="text-center">Cambia la configuración actual de la API</p>

                                                <!-- form -->
                                                <form class="row gy-1 gx-2 mt-75" method="POST"
                                                      action="{{route('decks.apis.patch',['deckId'=>$deck->id, 'apiId'=>$api->id])}}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="col-12">
                                                        <label class="form-label" for="name">Nombre</label>
                                                        <div class="input-group input-group-merge">
                                                            <input id="name" name="name"
                                                                   class="form-control" type="text"
                                                                   placeholder="Nombre para identificar la API"
                                                                   value="{{ $api->name}}"
                                                            />
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" for="key">API Key</label>
                                                        <div class="input-group input-group-merge">
                                                            <input id="key" name="key"
                                                                   class="form-control" type="text"
                                                                   placeholder="API key de app Twitter"
                                                                   value="{{ $api->key}}"

                                                            />
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" for="secret">API Secret</label>
                                                        <div class="input-group input-group-merge">
                                                            <input id="secret" name="secret"
                                                                   class="form-control" type="text"
                                                                   placeholder="API secret de app Twitter"
                                                                   value="{{ $api->secret}}"
                                                            />
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" for="type">Tipo de api</label>
                                                        <select id="type" name="type" class="form-select">
                                                            <option value="rt"
                                                                {{$api->type === 'rt'? 'selected': ''}}>
                                                                Retweet
                                                            </option>
                                                            <option
                                                                value="delete" {{$api->type === 'delete'? 'selected': ''}}>
                                                                Borrar
                                                            </option>

                                                        </select>
                                                    </div>


                                                    <div class="col-12 text-center">
                                                        <button type="submit" class="btn btn-primary me-1 mt-1">
                                                            Actualizar
                                                            API
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
                                <!--/ add new card modal  -->
                            @endforeach

                        </div>
                        <!-- / gestion de apis -->

                    </div>
                    <!--/ User Content -->
                </div>
            </section>
        @if($canEditDeck)
            <!-- Edit Deck Modal -->
                <div class="modal fade" id="editDeckModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
                        <div class="modal-content">
                            <div class="modal-header bg-transparent">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                            </div>
                            <div class="modal-body pb-5 px-sm-5 pt-50">
                                <div class="text-center mb-2">
                                    <h1 class="mb-1">Editar Deck</h1>
                                    <p>Cambia la configuración actual del Deck</p>
                                </div>
                                <form class="row gy-1 pt-75" action="{{route('decks.update',['deck'=>$deck->id])}}"
                                      method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="rt_minutes">Minutos para RT:</label>
                                        <input type="number" class="form-control" name="rt_minutes" id="rt_minutes"
                                               value="{{$deck->rt_minutes}}"
                                               min="60" required
                                        >
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="delete_minutes">Minutos para borrado</label>
                                        <input type="number" class="form-control" name="delete_minutes"
                                               id="delete_minutes"
                                               value="{{$deck->delete_minutes}}" required
                                               step="1" min="10">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="whatsapp_group_url">URL grupo Whatsapp</label>
                                        <input type="text" id="whatsapp_group_url" name="whatsapp_group_url"
                                               class="form-control modal-edit-tax-id"
                                               placeholder="Formato: https://chat.whatsapp.com/XXXX"
                                               value="{{$deck->whatsapp_group_url}}"
                                        />
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="telegram_username">Usuario de telegram (recepción
                                            de
                                            notificaciones)</label>
                                        <input type="text" id="telegram_username" name="telegram_username"
                                               class="form-control modal-edit-tax-id"
                                               placeholder="IxraelGomez"
                                               value="{{$deck->telegram_username}}"
                                        />
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="min_followers">Cantidad mínima de seguidores (en
                                            K)</label>
                                        <input type="number" class="form-control" name="min_followers"
                                               id="min_followers"
                                               placeholder="30 para 30k seguidores"
                                               value="{{$deck->min_followers}}"
                                               min="0">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label" for="enabled">Estado del Deck</label>
                                        <select id="enabled" name="enabled" class="form-select">
                                            <option value="1" {{$deck->enabled === 1? 'selected': ''}}>Activo</option>
                                            <option value="0" {{$deck->enabled === 0? 'selected': ''}}>Mantenimiento
                                            </option>

                                        </select>
                                    </div>


                                    <div class="col-12">
                                        <div class="d-flex align-items-center mt-1">
                                            <div class="form-check form-switch form-check-primary">
                                                <input type="checkbox" class="form-check-input" id="customSwitch10"
                                                       {{$deck->isPublic ? 'checked': ''}} name="isPublic"/>
                                                <label class="form-check-label" for="customSwitch10">
                                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                                </label>
                                            </div>
                                            <label class="form-check-label fw-bolder" for="customSwitch10">Permitir
                                                encontrar
                                                en la busqueda de Decks</label>
                                        </div>
                                    </div>
                                    <div class="col-12 text-center mt-2 pt-50">
                                        <button type="submit" class="btn btn-primary me-1">Guardar cambios</button>
                                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                                aria-label="Close">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Edit Deck Modal -->
        @endif


        <!-- add new api modal -->
            <div class="modal fade" id="addNewCard" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-sm-5 mx-50 pb-5">
                            <h1 class="text-center mb-1" id="addNewCardTitle">Añadir nueva API</h1>
                            <p class="text-center">Añade una nueva api al deck para hacerlo mas estable</p>

                            <!-- form -->
                            <form class="row gy-1 gx-2 mt-75" method="POST"
                                  action="{{route('decks.apis.store',['deckId'=>$deck->id])}}">
                                @csrf
                                <div class="col-12">
                                    <label class="form-label" for="name">Nombre</label>
                                    <div class="input-group input-group-merge">
                                        <input id="name" name="name"
                                               class="form-control" type="text"
                                               placeholder="Nombre para identificar la API"
                                               required
                                        />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="key">API Key</label>
                                    <div class="input-group input-group-merge">
                                        <input id="key" name="key"
                                               class="form-control" type="text"
                                               placeholder="API key de app Twitter"
                                               required
                                        />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="secret">API Secret</label>
                                    <div class="input-group input-group-merge">
                                        <input id="secret" name="secret"
                                               class="form-control" type="text"
                                               placeholder="API secret de app Twitter"
                                               required
                                        />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="type">Tipo de api</label>
                                    <select id="type" name="type" class="form-select">
                                        <option value="rt" selected>Retweet</option>
                                        <option value="delete">Borrar</option>

                                    </select>
                                </div>
                                <input name="deck_id"
                                       class="form-control" type="hidden"
                                       value="{{$deck->id}}"
                                />

                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-1 mt-1">Guardar API</button>
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


        </div>
    </div>
@endsection
