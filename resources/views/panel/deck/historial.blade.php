@extends('panel/index')
@section('titulocontenido')
    Deck: {{$id}}
@endsection

@section('subtitulo')
Historial del Deck en cuestión, si no está pendiente es porque ya se borró. Los infractores son las personas que borraron el Tweet antes de tiempo.
@endsection

@section('contenido')

                                 <div class="alert alert-primary alert-dismissible bg-primary text-white border-0 fade show"
                                    role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Info - </strong> Recuerda dar clic en el ID del Tweet para conocer mas detalles.
                                </div>
                                <div class="alert alert-secondary alert-dismissible bg-secondary text-white border-0 fade show"
                                    role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <strong>Info - </strong> Si alguien no esta dando RT reportalo con el administrador del DECK.
                                </div>
                                <div style="text-align:center;padding:1em 0;">  <iframe src="https://www.zeitverschiebung.net/clock-widget-iframe-v2?language=es&size=small&timezone=America%2FMexico_City" width="100%" height="90" frameborder="0" seamless></iframe> </div>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>

                <th scope="col">Username</th>
                <th scope="col">ID del tweet</th>
                <th scope="col">Hora</th>
                <th scope="col">Cantidad</th>
                <th scope="col">Pendiente</th>
                <th scope="col">Faltaron</th>
                <th scope="col"><strong>Infractores</stron></th>

                
            </tr>
        </thead>
        <tbody>
        @foreach($histo as $deck)
            <tr>

                <td>{{$deck->cuenta}}</td>
                <td><a href="{{route('inspector',['id'=>$id,'unico'=>$deck->id])}}">{{$deck->rtid}}</a></td>
                <td>{{$deck->created_at}}</td>
                <td>{{$deck->cantidad}}</td>
                <td>{{$deck->pendiente}}</td>
                <td>{{$deck->twitter}}</td>
                <td>{{$deck->infractores}}</td>





            </tr>
            @endforeach
            
        </tbody>
    </table>
   
</div>
<center>
    <a class="mt-5" href="{{route('decks.show',['deck'=>$id])}}">Volver al deck</a>
    </center>
@endsection
