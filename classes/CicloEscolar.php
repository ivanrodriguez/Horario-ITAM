<?php
    class CicloEscolar {
        private $numCicloAno;
        private $ano;
        private $arrMateriasImpartidas;  
        
        public function __construct($numCicloAno, $ano) {
            $this->numCicloAno = $numCicloAno;
            $this->ano = $ano;
            $this->arrMateriasImpartidas = array();
        }
        
        public function getNombreCiclo() {
            switch($this->numCicloAno) 
            {
                case 1: return 'Invierno-Primavera '.$this->ano; break;
                case 2: return 'Verano '.$this->ano; break;
                case 3: return 'Otono-Invierno '.$this->ano; break;
            }
        }
        
        public function getNumCicloAno() {
            return (int)$this->numCicloAno;
        }
        
        public function getAno() {
            return (int)$this->ano;
        }
        
        public function getArrMateriasImpartidas() {
            return $this->arrMateriasImpartidas;
        }
        
        public function agregaMateria($materia) {
            $this->arrMateriasImpartidas[] = $materia;
        }

        public function buscaMateria($idMateria) {
            foreach($this->arrMateriasImpartidas as $materia)
                if($materia->getId()==$idMateria)
                    return $materia;
        }
    }
?>