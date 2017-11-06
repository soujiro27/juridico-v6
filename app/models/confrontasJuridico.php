<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ConfrontasJuridico extends Model {
    public $timestamps = false;
    protected $table = 'sia_ConfrontasJuridico';
    protected $fillable = [ 'idVolante',
        'notaInformativa',
        'nombreResponsable',
        'cargoResponsable',
        'siglas',
        'hConfronta',
        'fOficio',
        'fConfronta',
        'numFolio',
        'usrAlta',
        'fAlta',
        'estatus'];

}