<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Organizadora extends Model
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

    // Relacionamento com Cidade
    public function cidade(): BelongsTo
    {
        return $this->belongsTo(Cidade::class, 'idCidade');
    }

    // Hash automático da senha
    public function setSenhaOrgAttribute($value)
    {
        $this->attributes['senhaOrg'] = Hash::make($value);
    }
}