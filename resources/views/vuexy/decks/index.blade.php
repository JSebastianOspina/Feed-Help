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
                                    <form class="row gy-1 gx-2 mt-75" method="POST" action="{{route('userMasterRT')}}">
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
                                    perteneces <strong>con un solo clic</strong> siempre y cuando estés bien vinculado </p>
                                <!-- form -->
                                <form class="row gy-1 gx-2 mt-75" method="POST" action="{{route('userMasterRT')}}">
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
                <!--/ user rt master modal  -->

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

@endsection
