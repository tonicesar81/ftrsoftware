<div id="item_{{ $item->id }}" class="form-row">
    <script>
    $(document).ready(function ($) {
                $('.summernote').summernote({
                    lang: 'pt-BR' // default: 'en-US'
                });
            });
    </script>
    <input type="hidden" name="itens[]" value="{{ $item->id }}" />
    <div class="form-group col-12">
        <h4>{!! $item->item !!} {!! Form::button('Remover disciplina', ['class' => 'btn btn-primary', 'onClick' => 'removeItem('.$item->id.',"'.$item->item.'")']); !!}</h4>
    </div>
    <div class="form-group col-12">
        {!! Form::label('texto_referencia', 'Texto referente a disciplina') !!}
        {!! Form::textarea('texto_referencia['.$item->id.']', $item->texto, ['class' => 'form-control summernote', 'rows' => '20', 'id' => 'summernote']) !!}
    </div>
    <div class="form-check col-12">
        {!! Form::checkbox('ocultar[]', $item->id, false, ['class' => 'form-check-input'] ) !!}
        {!! Form::label('ocultar', 'Ocultar Checklist desta disciplina', ['class' => 'form-check-label']) !!}            
    </div>
    @for($i=0;$i<$qnt;$i++)
    <div class="form-group col-4">
        {!! Form::label('numero', 'Número de identificação') !!}
        {!! Form::text('numero['.$item->id.'-'.$i.']', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-4">
        {!! Form::label('pavimento', 'Pavimento') !!}
        {!! Form::text('pavimento['.$item->id.'-'.$i.']', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-4">
        {!! Form::label('setor', 'Setor') !!}
        {!! Form::text('setor['.$item->id.'-'.$i.']', null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-12">        
        <table class="table table-bordered table-sm">
            <thead>
                <tr class="bg-primary text-white text-center">
                    <th scope="col">RECOMENDAÇÃO</th>
                    <th scope="col">SITUAÇÃO</th>
                </tr>
            </thead>
            <tbody>
                @foreach($checklists as $ck)
                <tr>
                    <td>{{$ck->descricao}}</td>
                    <td>{!! Form::select('vistorias['.$item->id.'-'.$i.'][]',[$ck->id.':0' => 'OK',$ck->id.':1' => 'NÃO OK',$ck->id.':2' => 'NÃO SE APLICA']  ,null, ['class' => 'form-control form-control-sm']) !!}</td>                    
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="form-group col-6 offset-md-3">
        {!! Form::label('imagem', 'Imagem') !!}
        {!! Form::file('imagem', ['class' => 'form-control-file imagem', 'id' => 'imagem_'.$item->id.'-'.$i, 'onchange' => "pegaImagem('".$item->id."-".$i."')"]) !!}
    </div>
    <div id="imgs_{{ $item->id.'-'.$i }}" class="form-row justify-content-center">
        
    </div>
    @endfor
    <div class="form-group col-12">
        {!! Form::label('observacoes', 'Observações') !!}
        {!! Form::textarea('observacoes['.$item->id.']', $item->observacao, ['class' => 'form-control summernote', 'rows' => '20', 'id' => 'summernote']) !!}
    </div>
</div>
<script>

</script>