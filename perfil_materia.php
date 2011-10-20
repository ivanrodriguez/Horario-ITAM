<?
    require_once 'classes/Usuario.php';
    require_once 'classes/Materia.php';
    require_once 'libs/Browser.php';
    require_once 'config/fbapp.php';
    session_start();
    $browser = new Browser();
    if ($browser->getBrowser() == 'Safari' && !isset($_SESSION['materia'])) {
        header('Location: error.php?id=9');
        return;
    }
    if (!$_SESSION['materia']) {
        header('Location: error.php?id=7');
        return;
    }
    if (!$_SESSION['usuariosMateria']) {
        header('Location: error.php?id=8');
        return;
    }
    if (!$_SESSION['facebook']) {
        header('Location: error.php?id=10');
        return;
    }
    if (!isset($_SESSION['usuarioPriv'])) {
        header('Location: error.php?id=4');
        return;
    }
    $materia = $_SESSION['materia'];
    $usuarios = $_SESSION['usuariosMateria'];
    $usuarioSesionPriv = $_SESSION['usuarioPriv'];
    $facebook = $_SESSION['facebook'];
    
    function getAmigosComun($facebook, $uid1, $uid2){
        try {
            $param  =   array(
                        'method'  => 'friends.getMutualFriends',
                        'source_uid'    => $uid1,
                        'target_uid'  => $uid2,
                        'callback'=> ''
                    );
            $amigosComun = $facebook->api($param);
            return $amigosComun;
        } catch(Exception $o) {
            print "Error al cargar amigos en común entre tú y el usuario de Facebook $uid2.";
            /*print '<pre>'; 
            print_r($o);
            print '</pre>';*/ 
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
<body class="fbbody">
    <noscript>
        <meta http-equiv="Refresh" content="0; url=http://www.braindepot.com.mx/MiHorarioITAM/error.php?id=6"></meta>
    </noscript>
    <div class="fbbluebox">
    <span><?=$materia->getNombre();?></span><br />
    <p></p>
    <table width="450" border="0" class="fbgreybox">
    <?
    foreach($usuarios as $usuarioMateria) {
        echo '<tr>';
        $uid1 = $usuarioSesionPriv->getId();
        $uid2 = $usuarioMateria->getId();
        $amigosComun = getAmigosComun($facebook, $uid1,$uid2);
        echo '<td width="60">';
        echo '<a href="javascript: top.location.href=\'http://www.facebook.com/profile.php?id='.$usuarioMateria->getId().'\'" title="Ver su perfil"><img src="https://graph.facebook.com/'.$usuarioMateria->getId().'/picture"/></a><br />';
        echo '<span><a href="javascript: top.location.href=\'http://apps.facebook.com/horario-itam/?verPerfil&id='.$usuarioMateria->getId().'\'" title="Ver sus materias de este semestre">'.$usuarioMateria->getNombre().' '.$usuarioMateria->getApellido().'</a></span>';
        echo '</td>';
        echo '<td width="230" class="fbbody">';
        echo 'Amigos en común: ';
        echo '<p></p>';
        if (count($amigosComun)==0)
            echo 'No tienen amigos en común :(';
        elseif ($uid1==$uid2)
            echo 'Eres tú.';
        else{
            foreach($amigosComun as $amigo)
                echo '<a href="javascript: top.location.href=\'http://www.facebook.com/profile.php?id='.$amigo.'\'" title="Ver su perfil"><img src="https://graph.facebook.com/'.$amigo.'/picture"/></a>&nbsp;';  
        }
        echo '<div class="fbcontentdivider"></div>';
        echo '</td>';
        echo '</tr>';
    }
    ?>
    </table>
<p>Comentarios de la materia: </p>
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js#appId=182576705104355"></script>
<script>
  FB.init({
    appId  : '182576705104355',
    status : true,
    cookie : true,
    xfbml  : true
  });
</script>
<fb:comments xid="<?=$materia->getId()?>" numposts="5" css="http://www.braindepot.com.mx/MiHorarioITAM/css/escondelike.css" publish_feed="false"></fb:comments>
    <p><a href="javascript: top.location.href='http://apps.facebook.com/horario-itam/'">Regresar</a></p>
    </div>
</body>
</html>
