<?php
// App\Models\Categoria.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = ['nombre'];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'categoria_role');
    }
}
