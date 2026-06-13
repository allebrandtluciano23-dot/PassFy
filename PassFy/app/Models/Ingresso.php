<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingresso extends Model
{
    protected $table = 'ingresso';

    protected $primaryKey = 'idIngresso';

    protected $fillable = [
        'idLote',
        'codigoUnico',
        'status',
    ];

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'idLote', 'idLote');
    }
}