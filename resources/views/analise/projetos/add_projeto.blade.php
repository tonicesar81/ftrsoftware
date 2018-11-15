<?php
foreach ($shoppings as $s) {
    $shopping[$s->id] = $s->shopping;
}
foreach ($tipo_relatorios as $t) {
    $tipo_relatorio[$t->id] = $t->tipo_relatorio;
}
?>
<div class='form-group col-12 border border-primary'>
    <div class="form-group col-12">

        {!! Form::label('tipo_relatorios', 'Escolha uma disciplina a serem analisada') !!}
        {!! Form::select('projetos[\'tipo_relatorios\'][]',$tipo_relatorio ,null, ['class' => 'form-control', 'id' => 'select-shopping']) !!}
    </div>
    <div class="form-group col-12">
        {!! Form::label('memorial', 'memorial') !!}
        {!! Form::file('projetos[\'memorial\'][]', ['class' => 'form-control memorial', 'onChange' => 'validate(this.value, \'pdf\')']); !!}
        <small id="inputHelpBlock" class="form-text text-muted">
            (somente PDF)
        </small>
        <small id="inputHelpBlock" class="form-text text-muted">
            Tamanho máximo por arquivo: 50MB
        </small>
    </div>
    <div class="form-group col-12">
        Arquivos para análise
    </div>
    <div class="form-group col-12 row">
        <div class="col-6">
            {!! Form::label('pdf', 'Versão em PDF (Obrigatório)') !!}
            {!! Form::file('projetos[\'pdf\'][]', ['class' => 'form-control pdf', 'onChange' => 'validate(this.value, \'pdf\')']); !!}
        </div>
        <div class="col-6">
            {!! Form::label('pdf', 'Versão em DWG (Obrigatório)') !!}
            {!! Form::file('projetos[\'dwg\'][]', ['class' => 'form-control dwg', 'onChange' => 'validate(this.value, \'dwg\')']); !!}
        </div>
        <div class="form-group col-12">
            <hr>
        </div>
        <div id="file-{{$fid}}" class="row">

        </div>

        <div class="col-12">
            <hr>
            {!! Form::button('Mais arquivos', ['class' => 'btn btn-primary', 'onClick' => 'addFile('.$fid.');']); !!}
        </div>
        
        <small id="inputHelpBlock" class="form-text text-muted">
            Tamanho máximo por arquivo: 50MB
        </small>
    </div>
</div>    