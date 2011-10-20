<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/facebook.css" rel="stylesheet" type="text/css" />
</head>
    <body class="fbbody">
<?php
    if (isset($_REQUEST['ids'])){
        echo "Gracias por invitar a mas personas a la aplicacion!";
    }
    else {
?>
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
  FB.init({
    appId  : '182576705104355',
    status : true, // check login status
    cookie : true, // enable cookies to allow the server to access the session
    xfbml  : true  // parse XFBML
  });
</script>
<fb:serverfbml>
    <script type="text/fbml">
          <fb:request-form
                    action="http://www.braindepot.com.mx/MiHorarioITAM/invita.php"
                    target="_top"
                    method="POST"
                    invite="true"
                    type="Horario ITAM"
                    content="Invitacion para usar Horario ITAM para agregar tus materias de este semestre.<fb:req-choice url='http://apps.facebook.com/horario-itam' label='Accept' />"
                    >

                    <fb:multi-friend-selector
                    showborder="false"
                    actiontext="Invitacion para usar Horario ITAM para agregar tus materias de este semestre.">
        </fb:request-form>
    </script>
  </fb:serverFbml>
<?php } ?>
    <span>
        <a href="javascript: top.location.href='http://apps.facebook.com/horario-itam/'">
            Regresar
        </a>
    </span>
    </body>
</html>