<?php
namespace JohnVanOrange\jvo;

class Router extends \cbulock\Simple\Router {

    /*
     * Process data
     *
     * Processes all the info that was extracted from the URL.
     *
     * @param string[] $data Pieces of the URL that were extracted.
     */
    
    protected function process_controller($data) {
        if (strlen($data[0]) == 6 && !strstr($data[0], '/')) {
            $this->set_data(1, $data[0]);
            $this->controller = 'Display';
        } else {
            parent::process_controller($data);
        }
    }

}