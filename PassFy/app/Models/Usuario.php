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
        'senhaUsuario',
    ];

    protected $hidden = [
        'senhaUsuario',
    ];

    public function getAuthPassword()
    {
        return $this->senhaUsuario;
    }

    public function getAuthIdentifierName()
    {
        return 'idUsuario';
    }

    public function setSenhaUsuarioAttribute($value)
    {
        $this->attributes['senhaUsuario'] = Hash::make($value);
    }
}