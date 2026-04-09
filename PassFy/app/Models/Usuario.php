<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    protected $table = 'usuario';

    protected $primaryKey = 'idUsuario';

    protected $fillable = [
        'nomeUsuario',
        'emailUsuario',
        'senhaUsuario',
    ];

    protected $hidden = [
        'senhaUsuario',
    ];

    // 👇 essencial pro login
    public function getAuthPassword()
    {
        return $this->senhaUsuario;
    }

    public function getAuthIdentifierName()
    {
        return 'emailUsuario';
    }

    // 👇 hash automático
    public function setSenhaUsuarioAttribute($value)
    {
        $this->attributes['senhaUsuario'] = Hash::make($value);
    }
}