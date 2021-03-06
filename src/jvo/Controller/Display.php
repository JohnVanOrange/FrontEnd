<?php
namespace JohnVanOrange\jvo\Controller;

class Display extends Standard {

 public function process() {

  $this->setTemplate('image');

  $is_brazz = FALSE;
  $request = $this->route->get_page();
  if ($request == 'brazzify') $is_brazz = TRUE;

  $image_name = $this->route->get_data(1);

  $image = $this->api('image/get',
  [
   'image'		=>	$image_name,
   'brazzify'	=>	$is_brazz
  ]);

  if (isset($image['merged_to'])) {
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: /" . $image['merged_to']);
  }

  $this->addData(
  [
   'image'	=>	$image,
   'rand'	=>	md5(uniqid(rand(), true)),
   'can_remove' => $this->api('image/canRemove', ['image' => $image_name])
  ]);

  $tips = [
   'Press the spacebar to see another random image.',
   'When logged in, you can save your favorite images.',
   'You can <a href="#addImageDialog" data-dialog="add_image">add images</a> saved to your computer, or from other websites.',
   'Click a tag name to view similar images.',
   'Click on <span class="fa fa-flag"></span> to report any images that shouldn\'t be on this site.',
   'Want more info about an image? More <a href="/info/' . $image['uid'] . '">details</a> are available under the <span class="fa fa-cog"></span> menu.',
   'Want to track your uploaded images? Log in first before uploading.',
   'Are you a developer? A full <a href="docs">API</a> is available.',
   'Did you find a bug?  <a href="https://github.com/JohnVanOrange/FrontEnd/issues/new">Bug reports</a> are greatly appreciated.'
  ];

  $rand = rand(1, 10);

  if ($rand == 1) {
   $this->addData(
   [
    'tip'	=>	$tips[array_rand($tips)]
   ]
  );

  }
 }

}
