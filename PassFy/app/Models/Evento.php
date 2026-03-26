<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evento extends Model
{
    protected $table = 'evento';

    protected $primaryKey = 'idEvento';

    protected $fillable = [
        'idOrg',
        'idCliente',
        'idCidade',
        'nomeEvento',
        'localEvento',
        'dataEvento',
        'descricaoEvento',
        'statusEvento',
    ];

    // Relacionamento com Organizadora
    public function organizadora(): BelongsTo
    {
        return $this->belongsTo(Organizadora::class, 'idOrg');
    }

    // Relacionamento com Cliente
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    // Relacionamento com Cidade
    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class, 'idCidade');
    }
}