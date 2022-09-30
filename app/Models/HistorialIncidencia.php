<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;
use App\Models\HistorialDetalle;
use App\Models\Traits\HasSorts;
use App\Models\Traits\HasEmbed;
use App\Models\Traits\HasFields;
use App\Models\Traits\HasParameters;
use App\Models\Traits\HasSearch;

class HistorialIncidencia extends Model
{
    use HasFactory;
    use HasSorts;
    use HasEmbed;
    use HasParameters;
    use HasFields;
    use HasSearch;

    public $allowedSorts = ['id', 'usuario_soporte', 'comentario', 'fecha_atencion', 'created_at', 'updated_at'];
    public $allowedParameters = ['id', 'usuario_soporte', 'comentario', 'fecha_atencion', 'created_at', 'updated_at'];
    public $allowedFields = ['id', 'usuario_soporte', 'comentario', 'fecha_atencion', 'created_at', 'updated_at'];

    protected $guarded = [];

    public function ticket()
    {
        //relacion uno a uno 
        return $this->belongsTo(Ticket::class);
    }

    public function historialDetalles()
    {
        return $this->hasMany(HistorialDetalle::class, 'historial_incidencias_id');
    }
}