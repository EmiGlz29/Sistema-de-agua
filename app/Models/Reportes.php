<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reportes extends Model
{
    use HasFactory;

    protected $table = 'reportes';

    protected $fillable = [
        'usuario_id',
        'ubicacion',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'folio',
    ];

    protected function casts(): array
    {
        return [
            'usuario_id' => 'integer',
        ];
    }

    public function getAttribute($key)
    {
        if (is_string($key) && ! array_key_exists($key, $this->attributes)) {
            foreach (array_keys($this->attributes) as $attribute) {
                if (strcasecmp($attribute, $key) === 0) {
                    return parent::getAttribute($attribute);
                }
            }
        }

        return parent::getAttribute($key);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
