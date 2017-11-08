<?php
session_start();
// Include the main TCPDF library (search for installation path).
require_once('./tcpdf/tcpdf.php');

$idVolante = $_GET['param'];


function conecta(){
  try{
    require './../../src/conexion.php';
    $db = new \PDO("sqlsrv:Server={$hostname}; Database={$database}", $username, $password );
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

$sql = "select * from sia_plantillasJuridico where idVolante ='$idVolante'";
$db=conecta();
$datos=consultaRetorno($sql, $db);

$id = $datos[0]['idRemitente'];

$sql = "select * from sia_RemitentesJuridico where idRemitenteJuridico='$id'";
$db=conecta();
$datosNombre=consultaRetorno($sql, $db);
//var_dump($datosNombre);
$saludo=$datosNombre[0]['saludo'];
$nombrel = $datosNombre[0]['nombre'];
$name = $saludo.' '.$nombrel;
$puesto = $datosNombre[0]['puesto'];
$texto = $datos[0]['texto'];
$siglas = $datos[0]['siglas'];
//var_dump($datos);

function convierte($cadena){
  $str = utf8_decode($cadena);
  return $str;
}

function mes($num){
  $meses= ['Enero','Febrero','Marzo','Abril','Mayo','Junio', 'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
  return $meses[$num-1];
}


$feoficio=explode('-',$datos[0]['fOficio']);
$mes2=mes(intval($feoficio[1]));



/*
$numdocu=convierte(str_replace('/',"\n", $datos[0]['numDocumento']));
$clave=$datos[0]['claveAuditoria'];
$fdocume=$datos[0]['fDocumento'];
$nomarers=$datos[0]['nombreres'];
$direc=$datos[0]['direccion'];
$sig=$datos[0]['siglas'];
$puesjud=$datos[0]['idPuestosJuridico'];
$tipo=$datos[0]['tipoau'];
$numof=$datos[0]["numFolio"];*/

class MYPDF extends TCPDF {
      // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica' , 'I', 11);
        // Page number
        //$this->Cell(20);
        //$this->Cell(186, 3,' | '.$this->getAliasNumPage().' | '. ' de '.' | '.$this->getAliasNbPages().' | ',1,1,'R');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Auditoria Superior de la Ciudad de México');
$pdf->SetTitle('Oficio ' /*.$clave*/);
 
 $pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// -------------------------------------------------------------------

// set font
//$pdf->SetFont('helvetica', '', 20);

// add a page
$pdf->AddPage();

$text1 = '
<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td colspan="1"><img img src="img/asamblea.png"/></td>
        <td colspan="2"></td>
        <td colspan="4"><p><font size="10"><b> AUDITORÍA SUPERIOR DE LA CIUDAD DE MÉXICO<br><br> DIRECCIÓN GENERAL DE ASUNTOS JURÍDICOS<br><br>OFICIO NÚM.AJU/17/ '.$datos[0]['numFolio'].' <br><br> ASUNTO:'.$datos[0]['asunto'].' <br><br>Ciudad de México, A '. $feoficio[2] . ' de ' .$mes2 . ' de ' . $feoficio[0].'<br><br> "Fiscalizar con Integridad para Prevenir y Mejorar"</b></p></font></td>
    </tr>
</table>';

$pdf->SetFontSize(9);
$pdf->writeHTML($text1);

//$pdf->SetFont('helvetica', '', 8);

// -------------------------------------------------------------------

$tbl = <<<EOD
<table cellspacing="0" cellpadding="0" border="0">
    
    <tr>
        <td colspan="1"><b>$name<br>$puesto<br>PRESENTE</b></td>
        
        
    </tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');



// -------------------------------------------------------------------

$tbl = <<<EOD
<table cellspacing="0" cellpadding="0" border="0">
    
    <tr>
        <td align="justify">$texto</td>
    </tr>

</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

// -----------------------------------------------------------------------------
/*
$sql="SELECT ROW_NUMBER() OVER (ORDER BY vo.idVolante  desc ) as fila,a.idAuditoria,a.clave,dbo.lstSujetosByAuditoria(a.idAuditoria) sujeto, ta.nombre tipo, a.rubros FROM sia_Volantes vo INNER JOIN sia_ObservacionesDoctosJuridico ob on vo.idVolante=ob.idVolante INNER JOIN sia_auditorias a on ob.cveAuditoria=a.idAuditoria INNER JOIN sia_tiposauditoria ta on a.tipoAuditoria=ta.idTipoAuditoria WHERE vo.idVolante='$idVolante' GROUP BY a.idAuditoria,a.clave, ta.nombre, a.rubros,vo.idVolante;";

$db=conecta();
$datos=consultaRetorno($sql, $db);

$tbl = <<<EOD
  <table cellspacing="0" cellpadding="1" border="1">
    <tr style="background-color:#E7E6E6;">
      <th colspan="1" align="center"><b>No.</b></th>
      <th colspan="1" align="center"><b>AUDITORÍA NÚM.</b></th>
      <th colspan="1" align="center"><b>SUJETO FISCALIZADO</b></th>
      <th colspan="1" align="center"><b>TIPO DE AUDITORÍA</b></th>
      <th colspan="3" align="center"><b>RUBRO</b></th>
    </tr>
    
EOD;

foreach ($datos as $row) {
$tbl .= <<<EOD
  <tr>
    <td colspan="1" align="center">{$row['fila']}</td>
    <td colspan="1" align="center">{$row['clave']}</td>
    <td colspan="1">{$row['sujeto']}</td>
    <td colspan="1">{$row['tipo']}</td>
    <td colspan="3">{$row['rubros']}</td>
  </tr>
EOD;
}

$tbl .= <<<EOD
  </table>
EOD;


$pdf->writeHTML($tbl, true, false, false, false, '');*/

// -----------------------------------------------------------------------------

$tbl = <<<EOD
<table cellspacing="0" cellpadding="0" border="0">
    
    <tr>
        <td>Sin otro particular por el momento, hago propicia la ocasión para enviarle un cordial saludo.<br><br></td>
    </tr>
    <tr>
        <td><b>ATENTAMENTE<br>El DIRECTOR GENERAL<br><br><br><br><br></b></td>
    </tr>
    <tr>
        <td><b>DR. IVÁN DE JESÚS OLMOS CANSINO<br></b></td>
    </tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');
// -----------------------------------------------------------------------------


$tbl = <<<EOD
<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td colspan="1">c.c.p.</td>  
        <td colspan="5"><b>DR. DAVID MANUEL VEGA VERA,</b> Auditor Superior.- Presente.- Para su conocimiento.<br><b>DR. ARTURO VÁZQUEZ ESPINOSA,</b> Titular de la Unidad Técnica Sustantiva de Fiscalización Especializada y de Asuntos Jurídicos.- Presente.- Para su conocimiento.</td>
    </tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

// -----------------------------------------------------------------------------

$tbl = <<<EOD
  <table cellspacing="0" cellpadding="0" border="0">
    <tr><td colspan="6" align="left">$siglas<br><br></td></tr>
  </table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');
// -----------------------------------------------------------------------------

//Close and output PDF document
$pdf->Output('Oficio Generico', 'I');

//============================================================+
// END OF FILE
//============================================================+