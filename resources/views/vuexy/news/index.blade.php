@extends('vuexy.template')
@section('content')
    <!-- Card layout -->
    <section class="card-layout">
        <div class="alert alert-info">
            <div class="alert-body d-flex align-items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="feather feather-info me-50">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <span>Bienvenido de nuevo {{auth()->user()->name}}</span>
            </div>
        </div>
        @if(auth()->user()->isOwner())
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-primary brtwaves-effect waves-float waves-light me-1"
                        data-bs-toggle="modal"
                        data-bs-target="#addNewModal">
                    Crear nueva noticia
                </button>
                <button type="button" class="btn btn-primary brtwaves-effect waves-float waves-light me-1"
                        data-bs-toggle="modal"
                        data-bs-target="#RTMasterModal">
                    RT Master
                </button>
                <button type="button" class="btn btn-primary brtwaves-effect waves-float waves-light "
                        data-bs-toggle="modal"
                        data-bs-target="#editDeckModal">
                    Configuración global del Deck
                </button>
            </div>


            <!-- COMIENZA LA SECCIÓN DE LOS MODALES-->

            <!-- add new card modal  -->
            <div class="modal fade" id="addNewModal" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-sm-5 mx-50 pb-5">
                            <h1 class="text-center mb-1" id="addNewCardTitle">Nueva noticia</h1>
                            <p class="text-center">Crea una nueva noticia para compartirla con los usuarios</p>

                            <!-- form -->
                            <form class="row gy-1 gx-2 mt-75" method="POST" action="{{route('news.store')}}">
                                @csrf

                                <div class="col-12">
                                    <label class="form-label" for="title">Titulo</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                           placeholder="Contenido de la noticia">
                                </div>

                                <div class="col-12">
                                    <label class="form-label" for="body">Contenido</label>
                                    <input type="text" class="form-control" name="body" id="body"
                                           placeholder="Contenido de la noticia">
                                </div>

                                <div class="col-12">
                                    <label class="form-label" for="image_url">URL imagen</label>
                                    <input type="text" class="form-control" name="image_url" id="image_url"
                                           placeholder="URL de la imagen">
                                </div>


                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-1 mt-1">Publicar noticia</button>
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

            <!-- edit deck modal  -->
            <div class="modal fade" id="editDeckModal" tabindex="-1" aria-labelledby="addNewCardTitle"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-sm-5 mx-50 pb-5">
                            <h1 class="text-center mb-1" id="addNewCardTitle">Editar Deck</h1>
                            <p class="text-center">Configuración global del deck </p>

                            <!-- form -->
                            <form class="row gy-1 gx-2 mt-75" method="POST" action="{{route('system.store')}}">
                                @csrf

                                <div class="col-12">
                                    <label class="form-label" for="status">Estado actual del Deck</label>

                                    <select class="form-select form-control" id="status" name="status">
                                        <option value="enabled" {{$system->status === 'active' ? 'selected' : ''}}>
                                            Activo
                                        </option>
                                        <option value="disabled" {{$system->status === 'disabled' ? 'selected' : ''}}>
                                            Inactivo
                                        </option>
                                        <option
                                            value="only_admins" {{$system->status === 'only_admins' ? 'selected' : ''}}>
                                            Solo Admins
                                        </option>

                                    </select>

                                </div>

                                <div class="col-12">
                                    <label class="form-label" for="same_tweet_id_minutes">Minutos para Tweet
                                        Repetido</label>
                                    <input type="number" class="form-control" name="same_tweet_id_minutes"
                                           id="same_tweet_id_minutes"
                                           placeholder="Valor numerico"
                                           min="15" required value="{{$system->same_tweet_id_minutes}}">
                                </div>


                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-1 mt-1">Guardar configuración
                                    </button>
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
            <!--/ edit deck modal  -->

            <!-- rt master modal  -->
            <div class="modal fade" id="RTMasterModal" tabindex="-1" aria-labelledby="addNewCardTitle"
                 aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-transparent">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body px-sm-5 mx-50 pb-5">
                            <h1 class="text-center mb-1" id="addNewCardTitle">RT MASTER</h1>
                            <p class="text-center">Privilegio para Owner </p>

                            <!-- form -->
                            <form class="row gy-1 gx-2 mt-75" method="POST" action="{{route('masterRT')}}">
                                @csrf

                                <div class="col-12">
                                    <label class="form-label" for="tweetURL">URL del Tweet</label>
                                    <input type="text" class="form-control" name="tweetURL" id="tweetURL" required>

                                </div>
                                <div class="col-12">
                                    <label class="form-label" for="deck_ids">Selecciona los decks</label>

                                    <select class="form-select " multiple="multiple"
                                            id="deck_ids" name="deck_ids[]">
                                        @foreach($decks as $deck)
                                            <option value="{{$deck->id}}">{{$deck->name}}</option>
                                        @endforeach

                                    </select>


                                </div>

                                <div class="col-12">
                                    <label class="form-label" for="same_tweet_id_minutes">Minutos para Tweet
                                        Repetido</label>
                                    <input type="number" class="form-control" name="same_tweet_id_minutes"
                                           id="same_tweet_id_minutes"
                                           placeholder="Valor numerico"
                                           min="15" required value="{{$system->same_tweet_id_minutes}}">
                                </div>


                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-1 mt-1">Guardar configuración
                                    </button>
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
            <!--/ rt master modal  -->
            <!-- TERMINA LA SECCIÓN DE LOS MODALES-->

        @endif

        <div class="row row-cols-1 row-cols-md-3 mb-2">
            @foreach($news as $new)

                <div class="col">
                    <div class="card h-100">
                        <img class="card-img-top" src="{{$new->image_url}}" alt=""/>
                        <div class="card-body">
                            <h4 class="card-title">{{$new->title}}</h4>
                            <p class="card-text">
                                {{$new->body}}
                            </p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Publicado en {{$new->updated_at}}</small>
                            <br>
                            <form action="{{route('news.delete',['id'=>$new->id])}}" method="POST"
                                  class="text-center">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-flat-danger mt-1">Eliminar
                                    publicación
                                </button>
                            </form>


                        </div>
                    </div>
                </div>
            @endforeach
        </div>


    </section>
    <!--/ Card layout -->
@endsection
