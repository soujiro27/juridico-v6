<?php
require './fpdf.php';
//require './../models/get.php';

$idVolante = $_GET['param1'];

function conecta(){
    try{
      require './../../src/conexion.php';
      $db = new PDO("sqlsrv:Server={$hostname}; Database={$database}", $username, $password );
      return $db;
    }catch (PDOException $e) {
      print "ERROR: " . $e->getMessage();
      die();
    }
  }

function consultaRetorno($sql,$db){
		$query=$db->prepare($sql);
		$query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}


$sql="select v.idTipoDocto, v.numDocumento,v.fDocumento,
 v.fRecepcion, v.hRecepcion, v.destinatario,v.folio, v.anexos,v.idTurnado,v.idRemitente, v.asunto,
cr.nombre as caracter,
a.nombre as accion,
ar.nombre as area,
CONVERT (date, SYSDATETIMEOFFSET()) as fActual,
CONCAT(e.nombre,' ',e.paterno,' ',e.materno) as titular
from sia_Volantes v
inner join sia_CatCaracteres cr on v.idCaracter=cr.idCaracter
inner join sia_CatAcciones a on v.idAccion=a.idAccion
inner join sia_areas ar on v.idRemitente=ar.idArea
inner join sia_empleados e on ar.idEmpleadoTitular=e.idEmpleado
where v.idVolante='$idVolante'";




$db=conecta();
$datos=consultaRetorno($sql, $db);
//echo json_encode($datos);

function convierte($cadena){
  $str = utf8_decode($cadena);
  return $str;
}

$audit=convierte('AUDITORÍA SUPERIOR DE LA CIUDAD DE MÉXICO');
$dir=convierte('DIRECCIÓN GENERAL DE ASUNTOS JURÍDICOS');
$num=convierte('NÚM DE DOCUMENTO');
$titular=convierte($datos[0]['titular']);
$area=convierte($datos[0]['area']);
$dest=convierte($datos[0]['destinatario']);
$asunto=convierte($datos[0]['asunto']);

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);
$pdf->Image('./img/logo-top.png',10,8,33);
$pdf->Cell(80,1,1);
$pdf->Cell(50,5,$audit,0,0,'C');
$pdf->Ln(5);
$pdf->Cell(80,1,1);
$pdf->Cell(50,5,$dir,0,0,'C');
$pdf->Ln(20);

$pdf->Cell(150,1,'',0);
$pdf->Cell(15,5,'Folio',1,0,'C');
$pdf->Cell(18,5,$datos[0]['folio'],1,0,'C');

$pdf->Ln(5);
$pdf->Cell(45,5,'DATOS DEL DOCUMENTO',1,0,'C');
$pdf->Cell(105,1,'',0);
$pdf->Cell(15,5,'Fecha',1,0,'C');
$pdf->Cell(18,5,$datos[0]['fActual'],1,0,'C');


$pdf->Ln(5);
$pdf->Cell(30,5,'TIPO',1,0,'C');
$pdf->Cell(48,5,$num,1,0,'C');
$pdf->Cell(40,5,'FECHA DOCUMENTO',1,0,'C');
$pdf->Cell(25,5,'ANEXOS',1,0,'C');
$pdf->Cell(40,5,'FECHA RECEPCION',1,0,'C');

$pdf->Ln(5);
$pdf->Cell(30,5,$datos[0]['idTipoDocto'],1,0,'C');
$pdf->Cell(48,5,$datos[0]['numDocumento'],1,0,'C');
$pdf->Cell(40,5,$datos[0]['fDocumento'],1,0,'C');
$pdf->Cell(25,5,$datos[0]['anexos'],1,0,'C');
$pdf->Cell(40,5,$datos[0]['fRecepcion'],1,0,'C');

$pdf->Ln(15);
$pdf->Cell(45,5,'REMITENTE',1,0,'C');

$pdf->Ln(5);
$pdf->Cell(77,5,'NOMBRE',1,0,'L');
$pdf->Cell(105,5,'CARGO',1,0,'L');

$pdf->Ln(5);
$pdf->Cell(15,5,$datos[0]['idRemitente'],1,0,'C');
$pdf->Cell(62,5,$titular,1,0,'L');
$pdf->Cell(105,5,$area,1,0,'L');
$pdf->Ln(10);
$pdf->Cell(91,5,'DEPENDENCIA',1,0,'C');
$pdf->Cell(91,5,'DESTINATARIO',1,0,'C');
$pdf->Ln(5);
$pdf->Cell(91,5,$audit,1,0,'C');
$pdf->Cell(91,5,$dest,1,0,'C');

$pdf->Ln(10);
$pdf->Cell(45,5,'ASUNTO',1,0,'C');
$pdf->Ln(5);
$pdf->MultiCell(183,30,$asunto,1);


$pdf->Ln(5);
$pdf->Cell(61,5,'CARACTER',1,0,'C');
$pdf->Cell(61,5,'TURNADO A ',1,0,'C');
$pdf->Cell(61,5,'INSTRUCCION',1,0,'C');

$pdf->Ln(5);
$pdf->Cell(61,5,$datos[0]['caracter'],1,0,'C');
$pdf->Cell(61,5,$datos[0]['idTurnado'],1,0,'C');
$pdf->Cell(61,5,$datos[0]['accion'],1,0,'C');

$pdf->Ln(10);
$pdf->Cell(45,5,'CONTROL DE GESTION',1,0,'C');
$pdf->Ln(5);
$pdf->Cell(183,10,'ACUSE DE RECIBO: ',1,0,'L');
$pdf->Ln(10);
$pdf->Cell(183,10,'NOMBRE Y FIRMA: ',1,0,'L');

$pdf->Ln(15);
$pdf->Cell(45,5,'ACCIONES IMPLEMENTADAS',1,0,'C');
$pdf->Ln(5);
$pdf->Cell(183,5,'',1,0,'C');

$pdf->Ln(10);
$pdf->Cell(45,5,'EXPEDIENTE',1,0,'C');
$pdf->Ln(5);
$pdf->Cell(183,5,'',1,0,'C');


$pdf->Ln(40);
$pdf->Cell(90,5,'',0,0,'C');
$pdf->Cell(45,5,'PENDIENTE',0,0,'C');
$pdf->Cell(45,5,'DESAHOGO','T',0,'C');
$pdf->Output();
?>
