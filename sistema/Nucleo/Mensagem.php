<?php 

    /**
     * @author Pablo <pablopbs940@gmail.com>
     */
    class Mensagem 
    {
        private $texto;
        private $css;

        public function sucesso(string $mensagem) : Mensagem
        {
            $this->css = 'alert alert-success';
            $this->texto = $this->filtrar($mensagem);
            return $this;
        }


        /**
         * Método responsável pela renderização
         */
        public function renderizar() : string 
        {
            return "<div class='{$this->css}'>{$this->texto}</div>";
        }

        private function filtrar(string $mensagem) : string
        {
            return filter_var(strip_tags($mensagem), FILTER_SANITIZE_SPECIAL_CHARS);
        }
    }

?>