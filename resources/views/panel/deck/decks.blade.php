@extends('panel/index')
@section('titulocontenido')
    Decks
@endsection

@section('subtitulo')
    Decks registrados hasta el momento
@endsection

@section('contenido')

    @if(auth()->user()->isOwner())

        @if(session('total'))
            <div class="alert alert-success alert-dismissible bg-success text-white border-0 fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                RT: {{ session('total') }}
            </div>
        @endif

        <div class="text-right">
            <button type="button" class="btn btn-success btn-rounded mb-3 " data-toggle="modal"
                    data-target="#primary-header-modal"><i class="fas fa-check"></i>Crear Deck
            </button>

            <button type="button" class="btn btn-danger btn-rounded mb-3 " data-toggle="modal"
                    data-target="#darrt"><i class="fas fa-check"></i>M A S T E R - R T
            </button>

        </div>

        <div id="primary-header-modal" class="modal fade" tabindex="-1" role="dialog"
             aria-labelledby="primary-header-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-colored-header bg-primary">
                        <h4 class="modal-title" id="primary-header-modalLabel">Crear nuevo Deck
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">

                        <form class="form-group" method="POST">
                            @csrf
                            <label class="form-control-label" for="nombre">Nombre del deck</label>
                            <input type="text" class="form-control" name="name" id="name">
                            <label class="form-control-label" for="owner_username">Administrador</label>
                            <input type="text" class="form-control" name="owner_username" id="owner_username">


                            <label class="form-control-label" for="rt_number">Cantidad RT/H</label>
                            <input type="number" class="form-control" name="rt_number" id="rt_number" value="1"
                                   step="1" min="1" max="4">
                            <label class="form-control-label" for="delete_minutes">Minutos para borrado</label>
                            <input type="number" class="form-control" name="delete_minutes" id="delete_minutes"
                                   value="10"
                                   step="1" min="10">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->


        <div id="darrt" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="primary-header-modalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-colored-header bg-primary">
                        <h4 class="modal-title" id="primary-header-modalLabel">Dar RT
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">

                        <form class="form-group" method="POST" action="{{ route('master') }}">
                            @csrf
                            <label class="form-control-label" for="rtid">ID Tweet</label>
                            <input type="text" class="form-control" name="rtid" id="rtid" required>
                            <label class="form-control-label" for="deck_list">Selecciona los decks</label>

                            <select class="form-select form-control" size="10" multiple
                                    aria-label="multiple select example" id="deck_list" name="deck_list[]">
                                @foreach($decks as $deck)
                                    <option selected value="{{$deck->id}}">{{$deck->nombre}}</option>

                                @endforeach

                            </select>
                            <label class="form-control-label" for="delete">¿Borrar RT?</label>

                            <select class="form-select form-control" aria-label="multiple select example" id="delete"
                                    name="delete">
                                <option value="1">Si</option>
                                <option value="0">No</option>
                            </select>
                            <label class="form-control-label" name="minutos"> Minutos para borrar</label>
                            <input class="form-control" required type="number" min="10" name="minutos" id="minutos">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Retweetear</button>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    @endif


    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Deck</th>
                <th scope="col">Admin</th>
                <th scope="col">Descripción</th>
                <th scope="col">Seguidores</th>
            </tr>
            </thead>
            <tbody>
            @foreach($decks as $deck)
                <tr>
                    <td><a href="{{route('decks.show',['deck'=>$deck->id])}}">{{$deck->name}}</a></td>
                    <td>{{$deck->owner_name}}</td>
                    <td>{{$deck->description}}</td>
                    <td>{{$deck->followers}}</td>

                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
@endsection
