<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Etudiant;
use App\Models\Stage;

class Certificate extends Model
{
    use HasFactory;
    protected $table = 'certificates';
    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($certificate) {
            $certificate->token = Str::random(32);
        });
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
}