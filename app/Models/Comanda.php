<?php
// App\Models\Comanda.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comanda extends Model
{
    use HasFactory;

    protected $fillable = ['mesa_id', 'fecha_hora', 'precio_total'];

    // Atributos por defecto
    protected $attributes = [
        'en_marcha' => true,
    ];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'comanda_productos')->withPivot('cantidad', 'estado_preparacion');
    }
    
}
