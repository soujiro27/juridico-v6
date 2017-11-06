<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PlantillasJuridico extends Model {
    public $timestamps = false;
    protected $table = 'sia_plantillasJuridico';
    protected $fillable = [
        'idVolante',
        'numFolio',
        'asunto',
        'fOficio',
        'idRemitente',
        'texto',
        'siglas',
        'copias',
        'usrAlta',
        'fAlta',
        'estatus'];

}