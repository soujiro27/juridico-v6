<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SubTiposDocumentos extends Model {
    public $timestamps = false;
    protected $table = 'sia_catSubTiposDocumentos';
    protected $fillable = ['idTipoDocto','nombre','auditoria', 'usrAlta','fAlta','estatus'];

}