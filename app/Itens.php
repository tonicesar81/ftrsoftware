<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Itens extends Model
{
    //
    public function tipo_relatorio()
    {
        return $this->hasOne('App\tipo_relatorios');
    }
}
