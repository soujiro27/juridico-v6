<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DocumentosSiglas extends Model {
    public $timestamps = false;
    protected $table = 'sia_DocumentosSiglas';
    protected $fillable = [ 'idDocumentoSiglas',
        'idVolante',
        'idSubTipoDocumento',
        'siglas',
        'usrAlta',
        'fAlta',
        'estatus',
        'idPuestosJuridico',
        'idDocumentoTexto',
        'fOficio',
        'numFolio'
    ];

}