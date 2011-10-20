<?
    function imprimeError($idError) {
        switch($idError) {
            case 1: return 'No se pudieron obtener los datos asociados al usuario de Facebook.'; break;
            case 2: return 'No se encontro en la base de datos la informacion del ciclo actual.'; break;
            case 3: return 'No se encontro en la base de datos la informacion de las materias ciclo actual.'; break;
            case 4: return 'No hay datos del usuario.'; break;
            case 5: return 'No hay datos del ciclo.'; break;
            case 6: return 'Esta aplicacion requiere que tengas activado Javascript en tu navegador.'; break;
            case 7: return 'No hay datos de la materia.'; break;
            case 8: return 'No hay datos de los usuarios asociados a la materia.'; break;
            case 9: return 'Por favor habilita los cookies en tu navegador Safari para poder ver la aplicacion.'; break;
            case 10: return 'No hay datos del SDK de Facebook.'; break;
            case 11: return 'No puedes registrar más de 8 materias.'; break;
            case 12: return 'No existe ese usuario o no ha registrado la aplicación.'; break;
            default: return 'Error desconocido.'; break;
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/facebook.css" rel="stylesheet" type="text/css" />
</head>
<body>
<noscript>
    <meta http-equiv="Refresh" content="0; url=http://www.braindepot.com.mx/MiHorarioITAM/error.php?id=6"></meta>
</noscript>
<div class="fberrorbox fbbody">Ocurrió un fallo:<p>
 <? echo imprimeError($_GET['id']); ?><br />
 <?
    if ($_GET['id']==9)
        echo '<br /><img src="imgs/cookies_safari.png"><br />';
?>
Cualquier pregunta, comentario o reporte lo puedes hacer en el <a href="javascript: top.location.href='http://www.facebook.com/apps/application.php?id=182576705104355'">perfil de la aplicación</a>.</div>
    <p class="fbbody">
        <a href="javascript: top.location.href='http://apps.facebook.com/horario-itam/'">
            Regresar
        </a>
    </p>
</body>
</html>