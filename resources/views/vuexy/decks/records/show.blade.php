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
                <a class="btn btn-primary btn-sm me-1" href="{{route('decks.records',['deckId'=>$deckId])}}">
                    <i data-feather="book-open"></i>
                    <span>Volver al historial</span>
                </a>

            </div>
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between w-100">
                        <h4 class="card-title">Inspector de Tweets</h4>
                    </div>

                </div>
                <div class="card-body">
                    <p class="card-text">
                        ¿Para que sirve? Esta herramienta sirve para saber a detalle por que no salieron todos tus RTS
                        completos. Podras visualizar un Codigo de error y a lado su descripcion.
                    </p>
                    <p class="card-text">
                        En caso de que alguien tenga INVALID TOKEN o EXPIRED TOKEN es necesario que lo reportes con el
                        admin. AYUDANOS A CUIDAR LAS APIS.
                    </p>

                </div>


                <div class="table-responsive">
                    <table class="table" id="testTable">
                        <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Código</th>
                            <th>Significado</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($details as $detail)
                            <tr>
                                <td>{{$detail->username}}</td>
                                <td>{{$detail->status_code}}</td>
                                <td>{{$detail->message}}</td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <!-- Basic Tables end -->
    <script src="/tablefilter/tablefilter.js"></script>


    <script data-config>

        var tf = new TableFilter('testTable', {
            base_path: '/tablefilter/',
            // aligns filter at cell bottom when Bootstrap is enabled
            filters_cell_tag: 'th',

            // allows Bootstrap table styling
            themes: [{
                name: 'transparent'
            }]
        });
        tf.init();
        let cells = document.getElementsByClassName('flt');

        for (let cell of cells) {
            cell.classList.add('form-control');
        }

    </script>

@endsection
