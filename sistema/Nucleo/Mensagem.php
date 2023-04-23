<?php 

    namespace sistema\Nucleo;

    /**
     * @author Pablo <pablopbs940@gmail.com>
     */
    class Mensagem 
    {
        private $texto;
        private $css;

        public function __toString()
        {
            return $this->renderizar();
        }

        /**
         * Mostra uma mensagem de sucesso (fundo verde)
         * @param string $mensagem A mensagem a ser exibida
         */
        public function sucesso(string $mensagem) : Mensagem
        {
            $this->css = 'alert alert-success';
            $this->texto = $this->filtrar($mensagem);
            return $this;
        }


        /**
         * Método responsável pela renderização
         * @param string $mensagem
         * @return Mensagem
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