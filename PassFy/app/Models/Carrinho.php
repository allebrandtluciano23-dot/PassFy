<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrinho extends Model
{
    protected $table = 'carrinho';

    protected $primaryKey = 'idCarrinho';

    protected $fillable = [
        'idCliente',
    ];

    // Relacionamento com Cliente
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    // (Futuro) Itens do carrinho
    public function itens(): HasMany
    {
        return $this->hasMany(ItemCarrinho::class, 'idCarrinho');
    }
}