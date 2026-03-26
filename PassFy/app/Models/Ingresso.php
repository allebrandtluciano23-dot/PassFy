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

    // Relacionamento com Lote
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'idLote');
    }
}