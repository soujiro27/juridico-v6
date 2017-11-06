<?php
namespace App\Models;
 use Illuminate\Database\Eloquent\Model;
use App\Models\Volantes;
 class Caracteres extends Model {
     public $timestamps = false;
     protected $primaryKey = 'idCaracter';
     protected $table = 'sia_catCaracteres';
     protected $fillable = ['siglas', 'nombre','usrAlta','fAlta','estatus'];



 }