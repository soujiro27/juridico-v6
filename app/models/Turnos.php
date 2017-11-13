<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Turnos extends Model {
    public $timestamps = false;
    protected $table = 'sia_turnosJuridico';
    protected $fillable = [ 'estadoProceso','usrAlta','fAlta'];

}