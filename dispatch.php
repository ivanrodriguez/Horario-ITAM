<?
    // Importa clases y configuraciones de db y fb
    require_once 'config/db.php';
    require_once 'config/fbapp.php';
    require_once 'classes/Usuario.php';
    require_once 'classes/Materia.php';
    require_once 'classes/CicloEscolar.php';

    // Inicia sesion php
    session_start();
    // Para solucionar bug de IE
    header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
    
    // Verifica solicitud de invitar amigos
    if(isset($_GET['invita'])) {
        header('Location: invita.php');
    }

    // Verifica solicitud de ver perfil publico
    elseif(isset($_GET['verPerfil'])) {
        $idUsuarioPub = $_GET['id'];
        $usuarioPub = buscaUsuario($db, $idUsuarioPub);
        if (!$usuarioPub)
            despliegaError(12);
        else {
            registraSesionPub($db, $usuarioPub);
            despliegaPerfilPublico();
        }
    }
    // Verifica solicitud de agregar materia
    elseif(isset($_POST['idAgregar'])) {
        $session = $facebook->getSession();
        $accessToken = $session['access_token'];
        $idMateria = $_POST['idAgregar'];
        $materia = $_SESSION['ciclo']->buscaMateria($idMateria);
        $numMaterias = count($_SESSION['usuarioPriv']->getArrMateriasCursa());
        if($numMaterias>=8)
            despliegaError(11);
        elseif($numMaterias==0) {
            notificaPared($facebook, $accessToken, $materia);
            registraMateriaUsuario($db, $materia);
            despliegaPerfilPrivado();
        }
        else {
            registraMateriaUsuario($db, $materia);
            despliegaPerfilPrivado();
        }
    }
    // Verifica solicitud de eliminar materia
    elseif(isset($_POST['idEliminar']) && $_POST['idMateriaVer']=='unset')
    {
        $session = $facebook->getSession();
        //$accessToken = $session['access_token'];
        $idMateria = $_POST['idEliminar'];
        $materia = $_SESSION['ciclo']->buscaMateria($idMateria);
        //notificaPared('baja', $facebook, $accessToken, $materia);
        eliminaMateriaUsuario($db, $materia);
        despliegaPerfilPrivado();
    }
    // Verifica solicitud de ver perfil de una materia
    elseif(isset($_POST['idMateriaVer']))
    {
        $idMateria = $_POST['idMateriaVer'];
        $materia = $_SESSION['ciclo']->buscaMateria($idMateria);
        $_SESSION['materia'] = $materia;
        $qryBuscaUsuarios = "select id from Usuarios join MateriasUsuarios on Usuarios.id=MateriasUsuarios.idUsuario where idMateria='$idMateria'";
        $rsUsuariosMateria = $db->Execute($qryBuscaUsuarios);
        $_SESSION['usuariosMateria'] = array();
        while (!$rsUsuariosMateria->EOF) {
            $_SESSION['usuariosMateria'][] = buscaUsuario($db, $rsUsuariosMateria->fields[0]);
            $rsUsuariosMateria->MoveNext();
        }
        $_SESSION['facebook'] = $facebook;
        despliegaPerfilMateria();
    }
            
    // Obtiene datos de FB, inicia sesion de perfil privado
    else
    {
        // Verifica si hay solicitud de ver perfil público
        if(isset($_SESSION['usuarioPub']))
            despliegaPerfilPublico();
        // Valida sesion y usuarioPriv de Facebook
        elseif (!$facebook->getSession()) {
            $url = $facebook->getLoginUrl(array(
                       'canvas' => 1,
                       'fbconnect' => 0,
                       'req_perms' => 'publish_stream'
                   ));
            echo "<script type='text/javascript'>top.location.href = '$url';</script>";
        }
        else {
            try {
                    // Obtiene datos del usuarioPriv que intenta acceso
                    $usuarioFB = $facebook->api('/me');
                    $id = $usuarioFB['id'];
                    $nombre = $usuarioFB['first_name'];
                    $apellido = $usuarioFB['last_name'];
                    // Verifica si no pudo obtener algun dato del usuarioPriv y despliega GUI de error
                    if (!$id || !$nombre || !$apellido)
                        despliegaError(1);
                    // Verifica si el usuarioPriv ya esta registrado en la aplicacion, si no lo registra
                    $usuarioAccesa = new Usuario ($id, $nombre, $apellido);
                    if(buscaUsuario($db, $id)==FALSE)
                        registraUsuario($db, $usuarioAccesa);
                    registraSesionPriv($db, $usuarioAccesa);
                    despliegaPerfilPrivado();
                } catch (FacebookApiException $e) {
                //print 'Error al obtener sesion de Facebook. Intenta de nuevo, si el problema persiste por favor repórtalo en el perfil de la aplicación.';
                //print '<pre>';
                //print_r($e); 
                //print '</pre>';
                }
        }
    }

    // Funciones para la atencion de solicitudes del dispatcher
    function buscaUsuario($db, $idUsuario) {
        $qryBuscaUsuario = "select * from Usuarios where id=$idUsuario";
        $rsUsuario = $db->Execute($qryBuscaUsuario);
        if($rsUsuario->fields[0]) {
            $usuarioEncontrado = new Usuario($rsUsuario->fields[0], $rsUsuario->fields[1], $rsUsuario->fields[2]);
            return $usuarioEncontrado;
        }
        else
            return FALSE;
    }
      
    function registraUsuario($db, $usuarioPriv) {
        $idUsuario = $usuarioPriv->getId();
        $nombre = $usuarioPriv->getNombre();
        $apellido = $usuarioPriv->getApellido();
        $qryAgregaUsuario="insert into Usuarios values ($idUsuario, '$nombre', '$apellido')";
        $db->Execute($qryAgregaUsuario);
    }
    
    function registraMateriaUsuario($db, $materia) {
        $idUsuario = $_SESSION['usuarioPriv']->getId();
        $idMateria = $materia->getId();
        $qryAgregaMateria="insert into MateriasUsuarios values ('$idMateria', $idUsuario)";
        $db->Execute($qryAgregaMateria);
        $_SESSION['usuarioPriv']->agregaMateria($materia);
    }

    function eliminaMateriaUsuario($db, $materia) {
        $idUsuario = $_SESSION['usuarioPriv']->getId();
        $idMateria = $materia->getId();
        $qryEliminaMateria = "delete from MateriasUsuarios where idMateria='$idMateria' and idUsuario=$idUsuario";
        $db->Execute($qryEliminaMateria);
        $_SESSION['usuarioPriv']->eliminaMateriaId($idMateria);
    }
    
    function registraSesionPriv($db, $usuarioPriv) {
        buscaMateriasUsuario($db, $usuarioPriv);
        $_SESSION['usuarioPriv'] = $usuarioPriv;
        $ciclo = buscaCicloActual($db);
        buscaMateriasCicloActual($db, $ciclo);
        $_SESSION['ciclo'] = $ciclo;           
    }

    function registraSesionPub($db, $usuarioPub) {
        buscaMateriasUsuario($db, $usuarioPub);
        $_SESSION['usuarioPub'] = $usuarioPub;
        $ciclo = buscaCicloActual($db);
        $_SESSION['ciclo'] = $ciclo;
    }

    function buscaCicloActual($db) {
        $numCiclo = getNumCicloActual();
        $ano = date("Y");
        $qryBuscaCiclo = "select * from CiclosEscolares where numCicloAno=$numCiclo and ano=$ano";
        $rsCiclo = $db->Execute($qryBuscaCiclo);
        if($rsCiclo->fields[0]) {
            $ciclo = new CicloEscolar($rsCiclo->fields[0], $rsCiclo->fields[1]);
            return $ciclo;            
        }
        else 
            despliegaError(2);
    }
    
    function buscaMateriasCicloActual($db, $ciclo) {
        $numCicloAno = $ciclo->getNumCicloAno();
        $ano = $ciclo->getAno();
        $qryBuscaMaterias = "select id, nombre from Materias join MateriasCiclos on Materias.id=MateriasCiclos.idMateria where numCicloAno=$numCicloAno and ano=$ano order by nombre";
        $rsMaterias = $db->Execute($qryBuscaMaterias);
        if(!$rsMaterias->fields[0])
            despliegaError(3);
        while (!$rsMaterias->EOF) {
            $materiaImpartida = new Materia($rsMaterias->fields[0], $rsMaterias->fields[1]);
            $ciclo->agregaMateria($materiaImpartida);
            $rsMaterias->MoveNext();
        }  
    }
     
    function buscaMateriasUsuario($db, $usuario) {
        $idUsuario = $usuario->getId();
        $qryBuscaMaterias = "select * from Materias join MateriasUsuarios on Materias.id=MateriasUsuarios.idMateria where idUsuario=$idUsuario";
        $rsMaterias = $db->Execute($qryBuscaMaterias);
        while (!$rsMaterias->EOF) {
            $materiaUsuario = new Materia($rsMaterias->fields[0], $rsMaterias->fields[1]);
            $usuario->agregaMateria($materiaUsuario);
            $rsMaterias->MoveNext();
        }
    }
      
    function getNumCicloActual() {
        $mesActual = (int)date("m");
        if($mesActual<=5)
            return 1; 
        elseif ($mesActual==6 || $mesActual ==7)
            return 2;
        else
            return 3;
    }
    
    function despliegaError($idError) {
        header('Location: error.php?id='.$idError);
    }
    
    function despliegaPerfilPrivado() {
        header('Location: perfil_priv.php');
    }

    function despliegaPerfilPublico() {
        header('Location: perfil_pub.php');
    }

    function despliegaPerfilMateria() {
        header('Location: perfil_materia.php');
    }

    function notificaPared($facebook, $accessToken, $materia) {
        $idUsuario = $_SESSION['usuarioPriv']->getId();
        $nombreMateria = $materia->getNombre();
        $attachment =  array (
            'access_token' => $accessToken,
            'message' => 'acaba de agregar una materia a su horario de este semestre utilizando Horario ITAM.',
            'name' => "Aqui puedes ver las materias que esta cursando",
            'link' => "http://apps.facebook.com/horario-itam/?verPerfil&id=$idUsuario",
            'caption' => "Horario ITAM",
            'description' => "Registra las materias que estas cursando este semestre y podras ver otras personas que las estan cursando y tus amigos en comun con ellas",
            'picture'=>"http://www.braindepot.com.mx/MiHorarioITAM/imgs/logo.png",
        ); 
        $facebook->api('/me/feed', 'POST', $attachment);
    }
