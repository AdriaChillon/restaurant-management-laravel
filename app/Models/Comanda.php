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
    
     // Add the accessor
     protected $appends = ['desactivada'];

     public function getDesactivadaAttribute()
     {
         // Filtrar los productos que no sean de la categoría "Refrescos" o "Cafés"
         $productosFiltrados = $this->productos->reject(function ($producto) {
             return $producto->categoria->nombre === 'Refrescos' || $producto->categoria->nombre === 'Cafes';
         });
 
         // Verificar si todos los productos filtrados están listos
         return $productosFiltrados->every(function ($producto) {
             return $producto->pivot->estado_preparacion === 'listo';
         });
     }
}
