<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Acciones extends Model {
    public $timestamps = false;
    protected $table = 'sia_CatAcciones';
    protected $fillable = [ 'nombre','usrAlta','fAlta','estatus'];

}