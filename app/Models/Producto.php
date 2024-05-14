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
    protected $fillable = ['nombre', 'precio', 'descripcion', 'imagen', 'categoria_id'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function comandas()
    {
        return $this->belongsToMany(Comanda::class, 'comanda_productos')->withPivot('cantidad');
    }
}
