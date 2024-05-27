<?php
// App\Models\Producto.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    const CATEGORIA_COCINA = 'cocina';
    const CATEGORIA_BARRA = 'barra';
    protected $fillable = ['nombre', 'precio', 'descripcion', 'imagen', 'categoria_id', 'estado_preparacion']; // Agrega 'estado_preparacion' a los campos fillable

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function comandas()
    {
        return $this->belongsToMany(Comanda::class, 'comanda_productos')->withPivot('cantidad', 'estado_preparacion'); // Asegúrate de incluir 'estado_preparacion' en la relación muchos a muchos con Comanda
    }
}
