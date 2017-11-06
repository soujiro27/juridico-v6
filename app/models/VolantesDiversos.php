<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Volantes extends Model {
    public $timestamps = false;
    protected $table = 'sia_Volantes';
    protected $primaryKey = 'idVolante';
    protected $fillable = ['idTipoDocto',
        'subFolio',
        'extemporaneo',
        'folio',
        'numDocumento',
        'anexos',
        'fDocumento',
        'fRecepcion',
        'hRecepcion',
        'hRecepcion',
        'idRemitente',
        'destinatario',
        'asunto',
        'idCaracter',
        'idTurnado',
        'idAccion',
        'usrAlta',
        'fAlta',
        'estatus'];


}