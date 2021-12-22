@extends('panel/index')
@section('titulocontenido')
    Deck: {{$id}}
@endsection

@section('subtitulo')
Analizando el tweet con ID: {{$h}}
@endsection

@section('contenido')
  <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading">Para que sirve el inspector?</h4>
                                    <p>Esta herramienta sirve para saber a detalle por que no salieron todos tus RTS completos. Podras visualizar un Codigo de error y a lado su descripcion.</p>
                                    <hr>
                                    <p class="mb-0">En caso de que alguien tenga INVALID TOKEN o EXPIRED TOKEN es necesario que lo reportes con el admin. AYUDANOS A CUIDAR LAS APIS.</p>
                                </div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>

                <th scope="col">Twitter</th>
                <th scope="col">Código</th>
                <th scope="col">Significado</th>
                
                
            </tr>
        </thead>
        <tbody>
        @foreach($o as $err)
            <tr>

                <td>{{$err->twitter}}</td>
                <td>{{$err->codigo}}</td>
                <td>{{$err->mensaje}}</td>
                




            </tr>
            @endforeach
            
        </tbody>
    </table>
    <center>
    <a href="{{route('historial',['id'=>$id])}}">Volver al historial</a>
    </center>
</div>
@endsection
