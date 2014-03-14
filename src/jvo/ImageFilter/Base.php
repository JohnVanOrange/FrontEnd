<?php
namespace JohnVanOrange\jvo\ImageFilter;

class Base {
  
  protected $query;
  protected $options;
  
  public function __construct(\Peyote\Query $query, $options = NULL) {
    $this->query = $query;
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
          $this->join('media', ['media.uid' => 'images.uid']);
          $this->query->where('media.type', '=', 'primary');
        }
        if (in_array($key, $resources)) $this->join('resources', ['resources.image' => 'images.uid']);
      }
    }
    
    $this->columns();
    $this->display();
    $this->sort();
    $this->limit();
    $this->format();
    $this->uploader();
    $this->animated();
    $this->nsfw();
    $this->approved();
  }
  
  protected function join($table, $on) {
    $this->query->join($table);
    $this->query->on(key($on), '=', current($on));
  }
  
  protected function columns() {
    //TODO: add override to specify columns
    $this->query->columns('uid');
  }
  
  protected function display() {
    // TODO: include an override where any image can be displayed
    $this->query->where('display', '=', 1);
  }
  
  protected function sort() {
   if (isset($this->options['sort'])) $this->query->orderBy($this->options['sort']);
  }
  
  protected function limit() {
    if (isset($this->options['limit'])) $this->query->limit($this->options['limit']);
  }
  
  protected function format() {
    if (isset($this->options['format'])) $this->query->where('format', '=', $this->options['format']);
  }
  
  protected function animated() {
    if (isset($this->options['animated'])) $this->query->where('animated', '=', $this->options['animated']);
  }
  
  protected function nsfw() {
    if (isset($this->options['nsfw'])) $this->query->where('nsfw', '=', $this->options['nsfw']);
  }
  
  protected function approved() {
    if (isset($this->options['approved'])) $this->query->where('approved', '=', $this->options['approved']);
  }
  
  protected function uploader() {
    if (isset($this->options['uploader'])) $this->query->where('user_id', '=', $this->options['uploader']);
  }
}
