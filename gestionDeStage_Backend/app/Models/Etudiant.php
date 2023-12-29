<?php

namespace App\Models;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;


class Etudiant extends Model implements Authenticatable
{
    use HasFactory, AuthenticableTrait;
    use HasApiTokens;


    protected $table = 'ETUDIANT';
    public const CREATED_AT = null;
    public const UPDATED_AT = null;
    public $timestamps = false;
    protected $primaryKey = 'id_Etud';
    protected $guarded = [];
    protected $hidden = [
        'password',
    ];
}
