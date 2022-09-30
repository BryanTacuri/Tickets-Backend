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

class HistorialDetalle extends Model
{
    use HasFactory;
    use HasSorts;
    use HasEmbed;
    use HasParameters;
    use HasFields;
    use HasSearch;

    public $allowedSorts = ['id', 'descripcion',  'created_at', 'updated_at'];
    public $allowedParameters = ['id', 'descripcion', 'created_at', 'updated_at'];
    public $allowedFields = ['id', 'descripcion',  'created_at', 'updated_at'];

    protected $guarded = [];

    public function historialIncidencia()
    {
        return $this->belongsTo(HistorialIncidencia::class);
    }
}