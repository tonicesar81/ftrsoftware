<table class="table" id="table_{{$inc}}">
    <thead>
        <tr class="bg-primary text-white">
            <th  colspan="2" >{{$inc}}. {!! $relatorio->tipo_relatorio !!} <button class="btn btn-danger btn-sm" type="button" onclick="apaga({{$inc}});">X</button></th>
            {!! Form::hidden('tipo_relatorios_id[]', $relatorio->id, ['class' => 'form-control']) !!}
        </tr>        
    </thead>
    <tbody>
        @php
        $i=1;
        @endphp
        @foreach($itens as $item)
        <tr>
            <td width='60%'>
                {{$inc}}.{{$i}} - {!! $item->item !!} {{ ($item->id == 60) ? '(SOMENTE PARA SHOPPING TIJUCA)' : '' }}
            </td>
            <td>
                {!! Form::radio('ok-'.$item->id, '0', true, ['onclick' => 'abreOk('.$item->id.')']) !!} OK
                {!! Form::radio('ok-'.$item->id, '1', false, ['onclick' => 'abreNaoOk('.$item->id.')']) !!} NÃO OK
                <div id='{{$item->id}}' class="bg-obs d-none">
                    @foreach($item->obs as $ob)
                    <div class="form-check">
                    {!! Form::checkbox('obs[]', $ob->id, false, ['class' => 'form-check-input'])!!}
                    {!! Form::label('obs', $ob->lista_analise, ['class' => 'form-check-label']) !!}
                    </div>
                    @endforeach
                    <div class="form-group">
                        @php $name = 'comm_'.$item->id; @endphp
                        {!! Form::label('comm_'.$item->id , 'Outra observação') !!}
                        {!! Form::textarea('comm_'.$item->id, null, ['class' => 'form-control text-uppercase', 'rows' => '3']) !!}
                    </div>
                </div>
            </td>
        </tr>
        @php
        $i++;
        @endphp
        @endforeach
    </tbody>
</table>