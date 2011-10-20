<? 
    require_once 'classes/Usuario.php';
    require_once 'classes/CicloEscolar.php';
    require_once 'classes/Materia.php';
    require_once 'libs/Browser.php';
    session_start();
    $browser = new Browser();
    if($browser->getBrowser() == 'Safari' && !isset($_SESSION['usuarioPriv'])) {
        header('Location: error.php?id=9');
        return;
    }
    if (!isset($_SESSION['usuarioPriv'])) {
        header('Location: error.php?id=4');
        return;
    }
    if (!isset($_SESSION['ciclo'])) {
        header('Location: error.php?id=5');
        return;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/facebook.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
        google.load("jquery", "1.4.1");
        google.setOnLoadCallback(function() {
                $('.micromodal').microModal({
                        overlay: {
                                color: '#000',
                                opacity: 0.5
                        }
                });
        });
</script>
<script type="text/javascript" src="libs/jquery.micromodal-0.1.js"></script>
</head>
<body class="fbbody"> 
<noscript>
    <meta http-equiv="Refresh" content="0; url=http://www.braindepot.com.mx/MiHorarioITAM/error.php?id=6">
</noscript>
<img src="http://www.braindepot.com.mx/MiHorarioITAM/imgs/logo.png">
<p>Hola <?=$_SESSION['usuarioPriv']->getNombre(); ?>, tus materias para el semestre <?=$_SESSION['ciclo']->getNombreCiclo();?> son:</p>
    <div class="fbbluebox">
      <div class="fbgreybox">
        <form id="frmEliminarMateria" action="dispatch.php" method="post">
            <p>
            <?
            $materiasUsuario = $_SESSION['usuarioPriv']->getArrMateriasCursa();
            if($materiasUsuario) {
            echo 'Tus materias este semestre son:';
	    echo '<ul>';
            foreach($materiasUsuario as $materia)
                echo '<li>'.$materia->getNombre().'</li>';
	    echo '</ul>';
	    echo '<p></p>';
            echo '<label>Materia: </label>';
            echo '<select id="idEliminar" name="idEliminar">';
            foreach($materiasUsuario as $materia)
                echo '<option value='.$materia->getId().'>'.$materia->getNombre().'</option>';
            echo '</select>';
            echo '<input type="submit" class="inputbutton micromodal target-confirmarEliminar" value="Quitar de mi horario"/>';
            echo '<input id="idMateriaVer" name="idMateriaVer" type="hidden" value="unset"/>';
            echo '<input type="button" class="inputbutton" value="Ver alumnos" onclick="document.getElementById(\'idMateriaVer\').value=document.getElementById(\'idEliminar\').value; document.forms[\'frmEliminarMateria\'].submit();"/>';
            echo '<div id="confirmarEliminar" class="dialog fancy">';
            echo '<span>¿Seguro deseas quitar esta materia de tu horario?</span><br /><br />';
            echo '<button class="inputbutton" onclick="$.fn.microModal.dialogs[\'#confirmarEliminar\'].close()">No</button>';
            echo '<button class="inputbutton" onclick="document.forms[\'frmEliminarMateria\'].submit()">Sí</button>';
            echo '</div>';
            }
            else
                echo 'No has agregado ninguna materia a tu horario en este semestre.';
            ?>
        </form>
      </div>
    </div>   
    <p></p> 
    <div class="fbbluebox">
      <div class="fbgreybox">
      	Agrega materias a tu horario:
          <form id="frmAgregarMateria" action="dispatch.php" method="post">
      <p>
                <label>Materia: </label>
                <select name="idAgregar">
                    <?
                    $materiasCiclo = $_SESSION['ciclo']->getArrMateriasImpartidas();
                    foreach($materiasCiclo as $materia)
                    echo '<option value='.$materia->getId().'>'.$materia->getNombre().'</option>';
                    ?>
                </select>
                <input type="submit" class="inputbutton micromodal target-confirmarAgregar" value="Agregar a mi horario"/>
            <div id="confirmarAgregar" class="dialog fancy">
                    <span>¿Seguro deseas agregar esta materia a tu horario?</span><p></p>
                    <button class="inputbutton" onclick="$.fn.microModal.dialogs['#confirmarAgregar'].close()">No</button>
                    <button class="inputbutton" onclick="document.forms['frmAgregarMateria'].submit()">Sí</button>
            </div>
        </form>
      </div>
</div>
<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
    <p>
    	<fb:like href="http://www.facebook.com/apps/application.php?id=182576705104355" layout="button_count" show_faces="false" width="450"></fb:like>
    </p>
    <p><a href="dispatch.php?invita">Invita otras personas a utilizar esta aplicación</a>.</p>
    <p>Para más información de la aplicación, comentarios, sugerencias y reporte de errores visita el <a href="javascript: top.location.href='http://www.facebook.com/apps/application.php?id=182576705104355'">perfil de Horario ITAM</a>.</p>
</body>
</html>
