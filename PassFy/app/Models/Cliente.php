<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cliente extends Authenticatable
{
    protected $table = 'cliente';

    protected $primaryKey = 'idCliente';

    protected $fillable = [
        'idCidade',
        'nomeCliente',
        'enderecoCliente',
        'cepCliente',
        'telefoneCliente',
        'cpfCliente',
        'emailCliente',
        'senhaCliente',
    ];

    protected $hidden = [
        'senhaCliente',
    ];

    public function getAuthPassword()
    {
        return $this->senhaCliente;
    }

    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class, 'idCidade');
    }

    public function carrinho(): HasOne
    {
        return $this->hasOne(Carrinho::class, 'idCliente');
    }

    public function carteiraDigital(): HasOne
    {
        return $this->hasOne(CarteiraDigital::class, 'idCliente');
    }

    public function setSenhaClienteAttribute($value)
    {
        $this->attributes['senhaCliente'] = Hash::make($value);
    }
}