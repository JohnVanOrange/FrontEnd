<?php
namespace JohnVanOrange\jvo;

class Route {
    
    private $page;
    private $data;
    private $map;
    private $page_dir;
    
    /*
     * Route constructor
     *
     * Route requires a URL that will be routed, and a map to route with.
     *
     * @param string $url URL to route.
     * @param mixed[] $map Array making URL requests to pages.
     * @param string $page_dir Location of the pages to route to.
     */

    public function __construct($url=NULL, $map=[], $page_dir = '/pages/') {
       $this->process_data($this->process_URL($url));
       $this->map = $map;
       $this->page_dir = $page_dir;
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
        $this->page = $data[0];
        if (strlen($data[0]) == 6 && !strstr($data[0], '/')) {
            $data[1] = $data[0];
            $this->page = 'display';
        }
        array_shift($data);
        $this->data = $data;
    }
    
    /*
     * Get page
     *
     * Get the page that needs to be routed to based on the URL given.
     */
    
    public function get() {
        switch($this->page) {
            case '':
                return ROOT_DIR . $this->page_dir . 'random.php';
                break;
            case 'admin':
                return ROOT_DIR . $this->page_dir . 'admin/'.$this->get_data(0).'.php';
                break;
            default:
                $location = ROOT_DIR . $this->page_dir . $this->map->{$this->page}.'.php';
                if(file_exists($location)) {
                    return $location;
                }
                else {
                    return ROOT_DIR . $this->page_dir . 'random.php';
                } 
                break;
        }
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