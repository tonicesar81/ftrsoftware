<div class="container bg-light mb-2">
    <div class="row">
        <div class="col-4">
            <h5>RELATÓRIO DE ANÁLISES</h5>
        </div>
        <div class="col-8">
            @php
            $role = Auth::user()->user_levels_id;
            @endphp
            @if(Auth::user()->user_levels_id <= 3)
            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                <div class="btn-group mr-2" role="group" aria-label="First group">

                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title='Novo Relatório'>
                        <i class="fas fa-clipboard-list"></i>
                    </button>
                    <a href="{{url('/analise/relatorios')}}" role="button" class="btn btn-sm btn-outline-secondary" title="Relatórios"><i class="fas fa-clipboard-list"></i></a>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                        @php
                        $tipo_relatorios = App\Tipo_relatorios::all()
                        @endphp
                        @foreach($tipo_relatorios as $tipo_relatorio)
                        <a class="dropdown-item" href="{{url('/analise/relatorios/create/'.$tipo_relatorio->id)}}">{{$tipo_relatorio->tipo_relatorio}}</a>
                        @endforeach
                    </div>
                </div>
                <div class="btn-group mr-2" role="group" aria-label="Second group">
                    <a href="{{url('/analise/sistema/create')}}" role="button" class="btn btn-sm btn-outline-secondary" title="Novo Sistema"><i class="fas fa-server"></i>+</a>
                    <a href="{{url('/analise/sistema')}}" role="button" class="btn btn-sm btn-outline-secondary" title="Sistemas"><i class="fas fa-server"></i></a>
                </div>
                <div class="btn-group mr-2" role="group" aria-label="Second group">
                    <a href="{{url('/analise/item/create')}}" role="button" class="btn btn-sm btn-outline-secondary" title="Novo Item"><i class="fas fa-list-ol"></i>+</a>
                    <a href="{{url('/analise/item')}}" role="button" class="btn btn-sm btn-outline-secondary" title="Itens"><i class="fas fa-list-ol"></i></a>
                </div>
                <div class="btn-group mr-2" role="group" aria-label="Second group">
                    <a href="{{url('/analise/obs/create')}}" role="button" class="btn btn-sm btn-outline-secondary" title="Nova Observação"><i class="fas fa-tasks"></i>+</a>
                    <a href="{{url('/analise/obs')}}" role="button" class="btn btn-sm btn-outline-secondary" title="Observações"><i class="fas fa-tasks"></i></a>
                </div>
                <div class="btn-group mr-2" role="group" aria-label="Second group">
                    <a href="{{url('/shoppings/create')}}" role="button" class="btn btn-sm btn-outline-secondary" title="Novo Shopping"><i class="fas fa-building"></i>+</a>
                    <a href="{{url('/shoppings')}}" role="button" class="btn btn-sm btn-outline-secondary" title="Shoppings"><i class="fas fa-building"></i></a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>