<?php
// App\Models\Mesa.php
namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $fillable = ['numero', 'capacidad', 'ubicacion'];

    public function comandas()
    {
        return $this->hasMany(Comanda::class);
    }
}
