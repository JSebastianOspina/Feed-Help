@extends('vuexy.template')

@section('content')
    <!-- Basic Tables start -->
    <div class="row" id="basic-table">
        <div class="col-12">
            <div class="d-flex justify-content-end " style="margin-bottom: 15px">

                <a class="btn btn-primary btn-sm me-1" href="{{route('decks.show',['deck'=>$deckId])}}">
                    <i data-feather="settings"></i>
                    <span>Volver al deck</span>
                </a>

            </div>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between w-100">
                        <h4 class="card-title">Ver historial</h4>
                    </div>

                </div>
                <div class="card-body">
                    <p class="card-text">
                        Historial del Deck en cuestión. El estado pendiente significa que el tweet no se ha borrado del
                        perfil de los usuarios
                    </p>
                    <p class="card-text">
                        Para ver mas información, da clic a el ID del tweet
                    </p>
                </div>


                <div class="table-responsive">

                    <table class="table">
                        <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>ID del Tweet</th>
                            <th>Cantidad RT exitosos</th>
                            <th>Estado</th>
                            <th>Cuentas que no dieron RT</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($records as $record)
                            <tr>
                                <td>{{$record->username}}</td>
                                <td>
                                    <a href="{{route('decks.records.show',['deckId'=>$deckId, 'recordId' => $record->id])}}">
                                        {{$record->tweet_id}}
                                    </a>
                                </td>
                                <td>{{$record->success_rt}}</td>
                                <td>
                                    @if($record->pending === 1)
                                        <span class="badge rounded-pill badge-light-warning me-1">Pendiente</span>
                                    @else
                                        <span class="badge rounded-pill badge-light-success me-1">Borrado</span>
                                    @endif
                                </td>
                                <td>{{$record->not_rt_by}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- Basic Tables end -->


@endsection
