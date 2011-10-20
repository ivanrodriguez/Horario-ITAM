<?php
    class Materia {
        private $id;
        private $nombre;
        
        public function __construct($idMateria, $nombreMateria) {
            $this->id=$idMateria;
            $this->nombre=$nombreMateria;
        }
        
        public function getId(){
            return $this->id;
        }
        
        public function getNombre() {
            return $this->nombre;
        }
    }
?>