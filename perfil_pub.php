<?
    require_once 'classes/Usuario.php';
    require_once 'classes/Materia.php';
    require_once 'classes/CicloEscolar.php';
    session_start();
    if (!isset($_SESSION['usuarioPub'])) {
        header('Location: error.php?id=4');
        return;
    }
    if (!isset($_SESSION['ciclo'])) {
        header('Location: error.php?id=5');
        return;
    }
    $ciclo = $_SESSION['ciclo'];
    $usuario = $_SESSION['usuarioPub'];
    unset($_SESSION['usuarioPub']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/facebook.css" rel="stylesheet" type="text/css" />
</head>
<body class="fbbody"> 
<noscript>
    <meta http-equiv="Refresh" content="0; url=http://www.braindepot.com.mx/MiHorarioITAM/error.php?id=6">
</noscript>
<img src="http://www.braindepot.com.mx/MiHorarioITAM/imgs/logo.png"><br />
<table width="508" border="0" cellpadding="5">
  <tr>
    <td width="50"><img src="http://graph.facebook.com/<?=$usuario->getId()?>/picture" alt="Foto de perfil" width="50" height="50" /></td>
    <td width="432"><?='Las materias del semestre '.$ciclo->getNombreCiclo().' de '.$usuario->getNombre().' son: ';?></td>
  </tr>
</table>
<ul>
<?
    $arrMaterias = $usuario->getArrMateriasCursa();
	if($arrMaterias) {
		foreach($arrMaterias as $materia) {
			echo '<li style="font-size:small">'.$materia->getNombre().'</li>';
		}
	}
	else
		echo $usuario->getNombre().' no ha agregado ninguna materia a su horario este semestre.';
?>
</ul>
<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
    <p>
    	<fb:like href="http://www.facebook.com/apps/application.php?id=182576705104355" layout="button_count" show_faces="false" width="450"></fb:like>
    </p>
<p>
Si quieres agregar las materias que estás llevando este semestre a tu perfil y ver otras personas que estén llevando las mismas materias, comienza a utilizar la aplicación <a href="javascript: top.location.href='http://apps.facebook.com/horario-itam/'">Horario ITAM</a>.
</div>
</body>
</html>
