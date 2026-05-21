<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lote extends Model
{
    protected $table = 'lote';

    protected $primaryKey = 'idLote';

    protected $fillable = [
        'idEvento',
        'nomeLote',
        'quantidadeTotal',
        'valorIngresso',
    ];

    protected $casts = [
        'valorIngresso' => 'decimal:2',
    ];

    // Relacionamento com Evento
    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class, 'idEvento');
    }
}