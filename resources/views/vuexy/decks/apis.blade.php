@extends('vuexy.template')

@section('content')
    <!-- connection -->
    <!-- drives area starts-->
    <div class="drives">
        <div class="row">
            <div class="col-12">
                <h4 class="mb-2 ">APIS de escritura</h4>
            </div>
            <div class="d-flex justify-content-end " style="margin-bottom: 15px">

                <a class="btn btn-primary btn-sm me-1" href="{{route('decks.show',['deck'=>$deckId])}}">
                    <i data-feather="settings"></i>
                    <span>Volver al deck</span>
                </a>

            </div>
            @foreach($rtApis as $rtApi)
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card shadow-none border">
                        <div class="card-body">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            >
                                <circle cx="12" cy="12" r="3"></circle>
                                <path
                                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            <div class="mt-2">
                                <h5>{{$rtApi->name}}</h5>
                            </div>
                            <div class="d-flex justify-content-between mb-50">
                                <span class="text-truncate">Estado</span>

                                <div class="d-flex align-items-center">
                                    @if($rtApi->isActive === 1)
                                        <div class="spinner-grow spinner-grow-sm text-success me-50"
                                             style="animation-duration: 1s">
                                        </div>

                                        <span class="text-truncate">Activa</span>
                                    @else
                                        <div class="spinner-grow spinner-grow-sm text-danger me-25"
                                             style="animation-duration: 1s">
                                        </div>
                                        <span class="text-truncate">Inactiva</span>
                                    @endif

                                </div>

                            </div>

                            <div class="d-flex justify-content-center  mt-1">
                                <form
                                    action="{{route('decks.apis.authorize')}}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="deckId" value="{{$deckId}}">
                                    <input type="hidden" name="apiId" value="{{$rtApi->id}}">

                                    <button type="submit"
                                            class="btn btn-flat-{{$rtApi->isActive === 1? 'primary':'success'}} waves-effect waves-float waves-light d-inline-block ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round"
                                             stroke-linejoin="round" class="feather feather-twitter me-25">
                                            <path
                                                d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                                        </svg>
                                        {{$rtApi->isActive === 1? 'REAUTORIZAR API':'AUTORIZAR API'}}
                                    </button>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="col-12">
                <h4 class="mb-2 ">APIS de borrado</h4>
            </div>
            @foreach($deleteApis as $deleteApi)
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="card shadow-none border">
                        <div class="card-body">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            >
                                <circle cx="12" cy="12" r="3"></circle>
                                <path
                                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                            <div class="mt-2">
                                <h5>{{$deleteApi->name}}</h5>
                            </div>
                            <div class="d-flex justify-content-between mb-50">
                                <span class="text-truncate">Estado</span>

                                <div class="d-flex align-items-center">
                                    @if($deleteApi->isActive === 1)
                                        <div class="spinner-grow spinner-grow-sm  text-success me-50"
                                             style="animation-duration: 1s">
                                        </div>

                                        <span class="text-truncate">Activa</span>
                                    @else
                                        <div class="spinner-grow spinner-grow-sm text-danger me-25"
                                             style="animation-duration: 1s">
                                        </div>
                                        <span class="text-truncate">Inactiva</span>
                                    @endif

                                </div>

                            </div>

                            <div class="d-flex justify-content-center  mt-1">
                                <form
                                    action="{{route('decks.apis.authorize')}}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="deckId" value="{{$deckId}}">
                                    <input type="hidden" name="apiId" value="{{$deleteApi->id}}">

                                    <button type="submit"
                                            class="btn btn-flat-{{$deleteApi->isActive === 1? 'primary':'success'}} waves-effect waves-float waves-light d-inline-block ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round"
                                             stroke-linejoin="round" class="feather feather-twitter me-25">
                                            <path
                                                d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path>
                                        </svg>
                                        {{$deleteApi->isActive === 1? 'REAUTORIZAR API':'AUTORIZAR API'}}
                                    </button>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- drives area ends-->



    <!--/ connection -->

@endsection
