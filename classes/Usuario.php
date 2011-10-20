<?php
    class Usuario {
        private $id;
        private $nombre;
        private $apellido;
        private $arrMateriasCursa = array();
        
        public function __construct($id, $nombre, $apellido) {
            $this->id = $id;
            $this->nombre = $nombre;
            $this->apellido = $apellido; 
        }
        
        public function getId() {
            return $this->id;
        }
        
        public function getNombre() {
            return $this->nombre;
        }
        
        public function getApellido() {
            return $this->apellido;
        }
        
        public function getArrMateriasCursa() {
            return $this->arrMateriasCursa;
        }
        
        public function agregaMateria($materia) {
            if($this->buscaMateria($materia)==FALSE)
                $this->arrMateriasCursa[] = $materia;
            return;
        }
        
        public function eliminaMateriaId($idMateria) {
            $i = $this->buscaIndiceMateria($idMateria);
            unset($this->arrMateriasCursa[$i]);
            $this->arrMateriasCursa = array_values($this->arrMateriasCursa);
        }
                
        private function buscaIndiceMateria($idMateria) {
        	for ($i=0; $i<count($this->arrMateriasCursa); $i++ )
        		if ($this->arrMateriasCursa[$i]->getId() == $idMateria)
                    return $i;
        }
        
        private function buscaMateria($materiaBusca)
        {
            foreach($this->arrMateriasCursa as $materia)
                if ($materiaBusca == $materia)
                    return TRUE;
            return FALSE; 
        }
    }
?>