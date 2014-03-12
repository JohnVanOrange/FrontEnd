<?php
namespace JohnVanOrange\jvo;

class ImageFilter {
  
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
    
    $this->display();
    $this->sort();
    $this->limit();
    $this->format();
    $this->uploader();
    $this->animated();
    $this->nsfw();
    $this->approved();
  }
  
  private function join($table, $on) {
    $this->query->join($table);
    $this->query->on(key($on), '=', current($on));
  }
  
  private function display() {
    // TODO: include an override where any image can be displayed
    $this->query->where('display', '=', 1);
  }
  
  private function sort() {
    // TODO: include an override for sort order
    $this->query->orderBy('RAND()');
  }
  
  private function limit() {
    // TODO: include an override to specify a limit
    $this->query->limit(1);
  }
  
  private function format() {
    if (isset($this->options['format'])) $this->query->where('format', '=', $this->options['format']);
  }
  
  private function animated() {
    if (isset($this->options['animated'])) $this->query->where('animated', '=', $this->options['animated']);
  }
  
  private function nsfw() {
    if (isset($this->options['nsfw'])) $this->query->where('nsfw', '=', $this->options['nsfw']);
  }
  
  private function approved() {
    if (isset($this->options['approved'])) $this->query->where('approved', '=', $this->options['approved']);
  }
  
  private function uploader() {
    if (isset($this->options['uploader'])) $this->query->where('user_id', '=', $this->options['uploader']);
  }
}
