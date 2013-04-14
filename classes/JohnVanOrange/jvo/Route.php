<?php
namespace JohnVanOrange\jvo;

class Route {
    
    private $request;
    private $map;
    
    /*
     * Route constructor
     *
     * Route requires a URL that will be routed, and a map to route with
     *
     * @param string $url URL to route
     * @param mixed[] $map Array making URL requests to pages
     */

    public function __construct($url, $map) {
       $this->request = $this->process_URL($url);
       $this->map = $map;
    }
    
    /*
     * Process requested URL
     *
     * Breaks up URL requested into seperate pieces to be used in routing
     *
     * @param string $url URL to process
     */
    
    private function process_URL($url) {
       return explode('/',trim(explode('?',$url)[0],'/'));
    }
    
    public function get() {
        if (strlen($this->request[0]) == 6 && !strstr($this->request[0], '/')) {
            $this->request[1] = $this->request[0];
            return ROOT_DIR.'/pages/display.php';
        }
        else {
            switch($this->request[0]) {
                case '':
                    return ROOT_DIR.'/pages/random.php';
                    break;
                case 'admin':
                    return ROOT_DIR.'/pages/admin/'.$this->request[1].'.php';
                    break;
                default:
                    $location = ROOT_DIR.'/pages/'.$this->map->{$this->request[0]}.'.php';
                    if(file_exists($location)) {
                        return $location;
                    }
                    else {
                        return ROOT_DIR.'/pages/random.php';
                    } 
                    break;
            }
        }
        
    }
 
}