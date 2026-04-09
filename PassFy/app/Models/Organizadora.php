<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organizadora extends Authenticatable
{
    protected $table = 'organizadora';

    protected $primaryKey = 'idOrg';

    protected $fillable = [
        'idCidade',
        'nomeOrg',
        'cnpjOrg',
        'enderecoOrg',
        'cepOrg',
        'telefoneOrg',
        'emailOrg',
        'senhaOrg',
    ];

    protected $hidden = [
        'senhaOrg',
    ];

    // 👇 ESSENCIAL pro login funcionar
    public function getAuthPassword()
    {
        return $this->senhaOrg;
    }

    public function getAuthIdentifierName()
    {
        return 'emailOrg';
    }

    // Relacionamento
    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class, 'idCidade');
    }

    // Hash automático
    public function setSenhaOrgAttribute($value)
    {
        $this->attributes['senhaOrg'] = Hash::make($value);
    }
}