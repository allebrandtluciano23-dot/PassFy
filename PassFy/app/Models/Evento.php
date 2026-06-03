<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Lote;

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
        'horaEvento',
        'tipoEvento',
        'descricaoEvento',
        'statusEvento',
        'imagemEvento',
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

    // Relacionamento com Lotes
    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class, 'idEvento');
    }

    // Acessor para pegar o menor preço dos lotes
    public function getPrecoMinimoAttribute()
    {
        return $this->lotes->min('valorIngresso');
        return $min ?? 0;
    }
}
