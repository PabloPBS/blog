<?php

    namespace sistema\Nucleo;

    class Helpers 
    {
        /**
         * Verifica se um CPF é válido.
         * @param string $cpf CPF recebido
         * @return bool true se válido, e false se for o contrário
         */
        public static function ValidarCPF(string $cpf) : bool
        {
            $cpf = self::limparNumero($cpf);
            if(mb_strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
                return false;
            }
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }
            return true;
        }

        /**
         * Tira caracteres diferentes de números entre 0 e 9.
         * @param string $numero Número fornecido
         * @return string Número com os caractees removidos
         */
        public static function limparNumero(string $numero) : string
        {
            return preg_replace('/[^0-9]/', '', $numero);
        }

        /**
         * Simplifica URL's
         * @param string $mapa Caracteres a serem substituidos
         * @return string URL simplificado
         */
        public static function slug(string $string) : string
        {
            $mapa['a'] = 
            'ÂÀÁÃÄÈÉÊËÍÌÎÏÓÒÔÕÖÚÙÛÜÑâàáãäèéêëíìîïóòôõöúùûüç;<>{}!@#$%¨&*()-_=+ªº:"".,?\\\///||||';
            $mapa['b'] =
            'aaaaaeeeeiiiiooooouuuunaaaaaeeeeiiiiooooouuuuc                                   ';
        $slug = strtr(utf8_decode($string), utf8_decode($mapa['a']), $mapa['b']);

            $slug = strip_tags(trim($slug));
            $slug = str_replace(' ', '-', $slug);
            $slug = str_replace(['-----','----','---','--','-'], '-', $slug);

            return strtolower(utf8_decode($slug));
        }

        /**
         * Identifica a data atual, com nome do dia, dia, mês e ano.
         */
        public static function dataAtual() : string 
        {
            $diaMes = date('d');
            $diaSemana = date('w');
            $mes = date('m') - 1;
            $ano = date('Y');

            $nomeDiasDaSemana = [
                'Domingo',
                'Segunda-feira',
                'Terça-feira',
                'Quarta-feira',
                'Quinta-feira',
                'Sexta-feira',
                'Sábado'];

            $nomeDosMeses = [
                'Janeiro',
                'Fevereiro',
                'Março',
                'Abril',
                'Maio',
                'Junho',
                'Julho',
                'Agosto',
                'Setembro',
                'Outubro',
                'Novembro',
                'Dezembro'];

            $dataFormatada = "$nomeDiasDaSemana[$diaSemana], $diaMes de $nomeDosMeses[$mes] de $ano";

            return $dataFormatada;
        }


        /**
         * Verifica em qual ambiente você está (local ou web) e retorna sua devida URL
         * @param string $servidor Seu servidor (local/web)
         * @param string $ambiente Seu ambiente com a url
         * @param string $url Seu endereço web
         * @return string Retorna o endereço (não sei)
         */
        public static function url (string $url) : string
        {
            $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');
            $ambiente = ($servidor == 'localhost' ? URL_DESENVOLVIMENTO : URL_PRODUCAO);

            if(str_starts_with($url, '/')) {
                return $ambiente.$url;
            }

            return $ambiente.'/'.$url;
        }

        /**
         * Verifica se você está no ambiente local
         * @param string $servidor Seu servidor (local/web)
         * @return bool True se você está no servidor local e False se você não está no servidor local
         */
        public static function localhost() : bool
        {
            $servidor = filter_input(INPUT_SERVER, 'SERVER_NAME');

            if ($servidor == 'localhost') {
                return true;
            }
            return false;
        }


        /**
         * Verifica se uma variável é um endereço da web
         * @param string $url URL fornecida
         * @return bool Retorna True se verdadeiro e False para falso
         */
        public static function validarURL (string $url): bool 
        {
            if (mb_strlen($url) < 10) {
                return false;
            }
            if (!str_contains($url, '.')) {
                return false;
            }
            if (str_contains($url, 'http://') or str_contains($url, 'https://')) {
                return true;
            }
            return false;
        }

        /**
         * Verifica se uma variável é um endereço da web
         * @param string $url Endereço web fornecido
         * @return bool Retorna True se verdadeiro e False se falso
         */
        public static function validarURLComFiltro (string $url): bool 
        {
            return filter_var($url, FILTER_VALIDATE_URL);
        }


        /**
         * Verifica se uma variável é um email
         * @param string $email Email fornecido
         * @return bool Retorna True se verdadeiro e False se falso
         */
        public static function validarEmail(string $email) : bool 
        {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }


        /**
         * Função que retorna quanto tempo se passou desde uma data fornecida
         * @param string $agora Data atual
         * @param string $tempo Data fornecida
         * @param string $diferenca Diferença em segundos entre as datas
         * @param string $segundos Segundos de diferença
         * @param string $minutos Minutos de diferença
         * @param string $horas Horas de diferença
         * @param string $dias Dias de diferença
         * @param string $semanas Semanas de diferença
         * @param string $meses Meses de diferença
         * @param string $anos Anos de diferença
         * @return string Diferença de tempo
         */
            public static function contarTempo(string $data) : string
            {
                $agora = strtotime(date('Y-m-d H:i:s'));
                $tempo = strtotime($data);
                $diferenca = $agora - $tempo;

                $segundos = $diferenca;
                $minutos = round($diferenca/60);
                $horas = round($diferenca/3600);
                $dias = round($diferenca/86400);
                $semanas = round($diferenca/604800);
                $meses = round($diferenca/2419200);
                $anos = round($diferenca/29030400);

                if ($segundos <= 60) {
                    return 'agora';
                } elseif ($minutos <= 60) {
                    return $minutos == 1 ? 'há 1 minuto' : 'há '.$minutos.' minutos';
                } elseif ($horas <= 24) {
                    return $horas == 1 ? 'há 1 hora' : 'há '.$horas.' horas';
                } elseif ($dias <= 7) {
                    return $dias == 1 ? 'ontem' : 'há '.$dias.' dias';
                } elseif ($semanas <= 4) {
                    return $semanas == 1 ? 'há 1 semana' : 'há '.$semanas.' semanas';
                } elseif ($meses <= 12) {
                    return $meses == 1 ? 'há 1 mês' : 'há '.$meses.' meses';
                } elseif ($anos >= 1) {
                    return $anos == 1 ? 'há 1 ano' : 'há '.$anos.' anos';
                }
            }


            /**
             * Formata um número para que suas centenas sejam divididas
             * @param float $valor Valor a ser formatado
             * @return string Valor formatado
             */
            public static function formatarValor (float $valor = null) : string
            {
                return number_format(($valor ? $valor : 0), 2, ',','.');
            }


            /**
             * Formata números grandes para facilitar a visualização
             * @param string $numero Número a ser formatado
             * @return string Número formatado
             */
            public static function formatarNumero (string $numero = null) : string
            {
                return number_format(($numero ? $numero : 0), 0, '.','.');
            }


            /**
             * Retorna uma mensagem agradável de acordo com o horário do sistema
             * @param int $hora Hora do sistema
             * @param string $saudacao Mensagem agradável
             * @return string Retorna a saudação que satisfaz a condição
             */
            public static function saudacao() : string
            {

                $hora = date('H');

                /*if ($hora >= 0 && $hora <= 5) {
                    $saudacao = 'boa madrugada';
                } 
                elseif ($hora >= 6 AND $hora <= 12){
                    $saudacao = 'bom dia ';
                }
                elseif ($hora >= 13 AND $hora <= 18) {
                    $saudacao = 'boa tarde';
                }
                else {
                    $saudacao = 'boa noite';
                }*/

                /*switch($hora) {
                    case $hora >= 0 && $hora <= 5: 
                        $saudacao = 'boa madrugada';
                        break;
                    case $hora >= 6 AND $hora <= 12: 
                        $saudacao = 'bom dia';
                        break;
                    case $hora >= 13 AND $hora <= 18: 
                        $saudacao = 'boa tarde';
                        break;
                    default:
                        $saudacao = 'boa noite';
                }*/

                $saudacao = match(true) {
                    $hora >= 0 && $hora <= 5 => 'boa madrugada',
                    $hora >= 6 && $hora <= 12 => 'bom dia',
                    $hora >= 13 && $hora <= 18 => 'boa tarde',
                    default => 'boa noite'
                    
                };


                return $saudacao;
            }


            /**
             * Resume um texto
             * 
             * @param string $texto texto para resumir
             * @param int $limite limite de caracteres
             * @param string $continue opcional - o que deve ser exibido ao final do resumo
             * @return string texto resumido
             */
            public static function resumirTexto(string $texto, int $limite, string $continue = '...') : string
            {
                $textoLimpo = trim(strip_tags($texto));
                if (mb_strlen($textoLimpo) <= $limite) {
                    return $textoLimpo;
                }

                $resumirTexto = mb_substr($textoLimpo, 0 , mb_strrpos(mb_substr($textoLimpo,0 , $limite), ''));

                return $resumirTexto.$continue ;
            }
    }
?>