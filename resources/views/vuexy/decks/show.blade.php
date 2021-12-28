@extends('vuexy.template')

@section('content')
    <!-- Basic Tables start -->
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Deck: {{$deck->icon}} {{$deck->name}}</h4>
                </div>
                <div class="card-body">
                    <p class="card-text">
                        Número total de seguidores: {{$deck->followers}}
                    </p>
                    @if(auth()->user()->isOwner())

                        <div class="d-flex justify-content-end ">
                            <button type="button" class="btn btn-primary waves-effect waves-float waves-light"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addUserModal">
                                Agregar usuario
                            </button>
                        </div>
                    @endif
                </div>


                <div class="table-responsive">

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Estado</th>
                            <th>Usuario</th>
                            <th>Seguidores</th>
                            <th>Acciones</th>
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
                                                src="{{$user->image_url ?? 'https://abs.twimg.com/sticky/default_profile_images/default_profile_400x400.png'}}"
                                                alt="avatar">
                                        </div>
                                        <div>
                                              <span class="font-weight-bold d-block text-nowrap">
                                                {{$user->userUsername}}
                                            </span>
                                            <small
                                                class="text-muted">{{$user->twitterUsername ?? '@JohnDoe'}} </small>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $user->twitterFollowers ?? 0}}</td>
                                <td>

                                    <form
                                        action="{{route('decks.users.delete',['deckId' => $deck->id,'userId' => $user->userId])}}"
                                        method="post">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-icon btn-outline-primary waves-effect">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-trash">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- Basic Tables end -->

    @if(auth()->user()->isOwner())
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
                                <label class="form-label" for="user_id">Seleccionar usuario</label>
                                <select class="form-select form-control"
                                        id="user_id" name="user_id">
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
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
        <!-- TERMINA LA SECCIÓN DE LOS MODALES-->
    @endif
@endsection
