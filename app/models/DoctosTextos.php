<?php
namespace App\Models;
 use Illuminate\Database\Eloquent\Model;
 use App\Models\SubTiposDocumentos;
 class DoctosTextos extends Model {
     public $timestamps = false;
     protected $table = 'sia_CatDoctosTextos';
     protected $fillable = ['idTipoDocto', 'tipo', 'idSubTipoDocumento','nombre','texto', 'usrAlta','fAlta'];


 }