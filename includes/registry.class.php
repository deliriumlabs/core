<?php
/**
 * Esta clase se utiliza para pasar valores globales entre objetos individuales
 *
 */
Class Registry {
    private $vars = array();
    public $view;
    public $model;
    /**
     * Inicializa una variables
     *
     * @param string $key El nombre de la variable a inicializar
     * @param string $var El valor con el que se inicializara la variable
     * @return bool
     */
    function set($key, $var) {
        if(!$var){
            throw new Exception('  `' . $key . '`. .');
        }
        if (isset($this->vars[$key]) == true) {
            throw new Exception('Unable to set var `' . $key . '`. Already set.');
        }

        $this->vars[$key] = $var;
        return true;
    }


    /**
     * Obtiene una de las variables
     *
     * @param string $key El nombre de la variable a obtener
     * @return vavriable
     */
    function get($key) {
        if (isset($this->vars[$key]) == false) {
            return null;
        }

        return $this->vars[$key];
    }

    /**
     * Remueve una de las variables
     *
     * @param string $var La variable a eliminar
     */
    function remove($var) {
        unset($this->vars[$key]);
    }
}?>
