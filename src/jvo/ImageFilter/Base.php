<?php
namespace JohnVanOrange\jvo\ImageFilter;

class Base extends \Peyote\Select {
  
  protected $options;
  
  public function __construct($options = NULL) {
    
    parent::__construct('images');
    $this->options = $options;
    
    $media = [
      'format'
    ];
    $resources = [
      'uploader'
    ];
    
    if (isset($options)) {
      foreach ($options as $key => $option) {
        if (in_array($key, $media)) {
          $this->join_process('media', ['media.uid' => 'images.uid']);
          $this->where('media.type', '=', 'primary');
        }
        if (in_array($key, $resources)) $this->join_process('resources', ['resources.image' => 'images.uid']);
      }
    }
    
    $this->columns_process();
    $this->display();
    $this->sort();
    $this->limit_process();
    $this->format();
    $this->uploader();
    $this->animated();
    $this->nsfw();
    $this->approved();
  }
  
  protected function join_process($table, $on) {
    $this->join($table);
    $this->on(key($on), '=', current($on));
  }
  
  protected function columns_process() {
    //TODO: add override to specify columns
    $this->columns('images.uid');
  }
  
  protected function display() {
    // TODO: include an override where any image can be displayed
    $this->where('display', '=', 1);
  }
  
  protected function sort() {
   if (isset($this->options['sort'])) $this->orderBy($this->options['sort']);
  }
  
  protected function limit_process() {
    if (isset($this->options['limit'])) $this->limit($this->options['limit']);
  }
  
  protected function format() {
    if (isset($this->options['format'])) $this->where('format', '=', $this->options['format']);
  }
  
  protected function animated() {
    if (isset($this->options['animated'])) $this->where('animated', '=', $this->options['animated']);
  }
  
  protected function nsfw() {
    if (isset($this->options['nsfw'])) $this->where('nsfw', '=', $this->options['nsfw']);
  }
  
  protected function approved() {
    if (isset($this->options['approved'])) $this->where('approved', '=', $this->options['approved']);
  }
  
  protected function uploader() {
    if (isset($this->options['uploader'])) $this->where('user_id', '=', $this->options['uploader']);
  }
}