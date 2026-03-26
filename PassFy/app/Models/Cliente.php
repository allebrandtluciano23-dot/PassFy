<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    protected $table = 'cliente';

    protected $primaryKey = 'idCliente';

    protected $fillable = [
        'idCidade',
        'nomeCliente',
        'enderecoCliente',
        'cepCliente',
        'bairroCliente',
        'telefoneCliente',
        'cpfCliente',
        'emailCliente',
        'senhaCliente',
    ];

    protected $hidden = [
        'senhaCliente',
    ];

    // Relacionamento com Cidade
    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class, 'idCidade');
    }

    // Hash automático da senha
    public function setSenhaClienteAttribute($value)
    {
        $this->attributes['senhaCliente'] = Hash::make($value);
    }
}