{include file='header.tpl'}
<a href='../random?{$rand}'>
 <input type='hidden' name='uid' id='uid' value='{$uid}'>
 <input type='hidden' name='image_id' id='image_id' value='{$image_id}'>
 <img id='main_image' src='{$image}' name='{$image_name}' height='{$height}' width='{$width}' alt='Main Image' />
</a>
{include file='footer.tpl'}
