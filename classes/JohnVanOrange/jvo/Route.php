<?php
namespace JohnVanOrange\jvo;

class Route {
    
    private $page;
    private $data;
    private $config;
    private $controllerNS;
    private $controller;
    
    /*
     * Route constructor
     *
     * Route translates URLs into controllers.
     *
     * @param string $url URL to route.
     * @param mixed[] $config Array containing config data for router.
     * @param string $controllerNS Namespace of the controllers.
     */

    public function __construct($url=NULL, $config=[], $controllerNS) {
        $this->config = $config;
        $this->controllerNS = $controllerNS;
        $this->process_data($this->process_URL($url));
    }
    
    /*
     * Process requested URL
     *
     * Breaks up URL requested into seperate pieces to be used in routing.
     *
     * @param string $url URL to process.
     */
    
    private function process_URL($url) {
       return explode('/',trim(explode('?',$url)[0],'/'));
    }
    
    /*
     * Process data
     *
     * Processes all the info that was extracted from the URL.
     *
     * @param string[] $data Pieces of the URL that were extracted.
     */
    
    private function process_data($data) {
        if (strlen($data[0]) == 6 && !strstr($data[0], '/')) {
            $data[1] = $data[0];
            $this->controller = 'Display';
        }
        if ($data[0] == '') $this->controller = $this->config->default;
        if (!isset($this->controller)) {
            $controller_struct = '';
            $target = $this->config->map;
            $count = 0;
            while (is_object($target)) {
                $new = @$target->{$data[$count]};
                if (is_object($new)) {
                    $controller_struct = $controller_struct . key($new) . '\\';
                    $target = $new->{key($new)};
                }
                else {
                    $controller_struct = $controller_struct . $new;
                    $this->page = $data[$count];
                    $target = $new;
                }
                $count++;
            }
            $this->controller = $controller_struct;
        }
        if (!class_exists($this->controllerNS . $this->controller)) $this->controller = $this->config->not_found;
        array_shift($data);
        $this->data = $data;
    }
    
    /*
     * Get full page
     *
     * Get the page that needs to be routed to based on the URL given.
     */
    
    public function get() {
        $controller_name = $this->controllerNS . $this->controller;
        $controller = new $controller_name($this);
        $controller->load();
    }
    
    /*
     * Get page
     *
     * Get the literal page that was parsed from the URL.
     */
    
    public function get_page() {
        return $this->page;
    }
    
    /*
     * Get data
     *
     * Retrieves pieces of data that were given in the URL.
     *
     * @param int $index Index of data to retrieve.
     */
    
    public function get_data($index) {
        if (isset($this->data[$index])) return $this->data[$index];
        return NULL;
    }
    
    public function set_data($index, $data) {
        $this->data[$index] = $data;
    }
 
}