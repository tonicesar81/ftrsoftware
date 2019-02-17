<div id="item_{{ $item }}">
    @foreach($instalacoes as $instalacao)
    <div class="form-group col-12">        
        <table class="table table-bordered table-sm">
            <thead>
                <tr class="bg-primary text-white text-center">
                    <th scope="col">{{ $instalacao->item }}{{ ($instalacao->numero != null)? '-'.$instalacao->numero : '' }} ( Local: {{$instalacao->pavimento}}/{{$instalacao->setor}} )</th>
                    <th scope="col">SITUAÇÃO</th>
                </tr>
            </thead>
            <tbody>
                @foreach($instalacao->descs as $desc)
                <tr>
                    <td>{{$desc->descricao}}</td>
                    <td>{!! Form::select('vistorias['.$instalacao->id.'][]',[$desc->id.':0' => 'OK',$desc->id.':1' => 'NÃO OK',$desc->id.':2' => 'NÃO SE APLICA']  ,null, ['class' => 'form-control form-control-sm']) !!}</td>                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="form-group col-6 offset-md-3">
        {!! Form::label('imagem', 'Imagem') !!}
        {!! Form::file('imagem', ['class' => 'form-control-file imagem', 'id' => 'imagem_'.$instalacao->id, 'onchange' => 'pegaImagem('.$instalacao->id.')']) !!}
    </div>
    <div id="imgs_{{ $instalacao->id }}" class="form-row justify-content-center">
        
    </div>
    @endforeach
</div>
<script>

</script>