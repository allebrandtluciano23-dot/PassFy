<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarteiraDigital extends Model
{
    protected $table = 'carteira_digital';

    protected $primaryKey = 'idCarteira';

    protected $fillable = [
        'idCliente',
        'saldo',
    ];

    protected $casts = [
        'saldo' => 'decimal:2',
    ];

    // Relacionamento com Cliente
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }
}