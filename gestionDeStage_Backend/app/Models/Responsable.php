<?php

namespace App\Models;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Laravel\Passport\HasApiTokens;
class Responsable extends Model implements Authenticatable
{
    use HasFactory, AuthenticableTrait, HasApiTokens;
    protected $table = 'RESPONSABLE';
    public const CREATED_AT = null;
    public const UPDATED_AT = null;
    public $timestamps = false;
    protected $primaryKey = 'id_Resp';
    protected $guarded = [];
}
