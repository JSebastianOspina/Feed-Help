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
                <button type="button" class="btn btn-primary waves-effect waves-float waves-light"
                        data-bs-toggle="modal"
                        data-bs-target="#addNewModal">
                    Crear nueva noticia
                </button>
        </div>
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


    </section>
    <!--/ Card layout -->
@endsection
