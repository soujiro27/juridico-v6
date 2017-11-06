<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ObservacionesDoctosJuridico extends Model {
    public $timestamps = false;
    protected $table = 'sia_ObservacionesDoctosJuridico';
    protected $fillable = [ 'idVolante',
        'idSubTipoDocumento' ,
        'cveAuditoria' ,
        'pagina',
        'parrafo',
        'observacion',
        'usrAlta',
        'fAlta',
        'estatus'];

}