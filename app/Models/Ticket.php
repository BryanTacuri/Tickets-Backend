<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HistorialIncidencia;
use App\Models\Traits\HasSorts;
use App\Models\Traits\HasEmbed;
use App\Models\Traits\HasFields;
use App\Models\Traits\HasParameters;
use App\Models\Traits\HasSearch;

class Ticket extends Model
{
    use HasFactory;
    use HasSorts;
    use HasEmbed;
    use HasParameters;
    use HasFields;
    use HasSearch;

    public $allowedSorts = ['id', 'persona_solicitante', 'fecha_ingreso', 'descripcion', 'asunto', 'status', 'created_at', 'updated_at'];
    public $allowedParameters = ['id', 'persona_solicitante', 'descripcion', 'asunto', 'fecha_ingreso', 'status', 'created_at', 'updated_at'];
    public $allowedFields = ['id', 'persona_solicitante', 'fecha_ingreso', 'descripcion', 'asunto', 'status', 'created_at', 'updated_at'];
    protected $guarded = [];


    public function historialIncidencia()
    {
        return $this->hasOne(HistorialIncidencia::class, 'tickets_id');
    }


    //Ordenar
    public function sort($query, $sort)
    {
        return $this->scopeSorts($query, $sort);
    }

    //Embed
    public function embed($query, $embed)
    {
        return $this->scopeEmbed($query, $embed);
    }

    //Parametros
    public function parameters($query, $parameters)
    {
        return $this->scopeParameters($query, $parameters);
    }

    //Campos
    public function fields($query, $fields)
    {
        return $this->scopeFields($query, $fields);
    }

    //Buscar por nombre 
    public function search($query, $search)
    {
        return $this->scopeSearch($query, $search);
    }

    //buscar por rango de fechas
    public function searchDate($query, $search)
    {
        return $this->scopeSearchDate($query, $search);
    }
}