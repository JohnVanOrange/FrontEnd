{include file='header.tpl'}
<p>Click picture for more{if $type != 'gif' AND !$brazzify}<span id='brazzers_text'>, or <a href='{$web_root}brazzify/{$image_name}' id='brazzify'>Brazzify</a></span>{/if}
</p>
<a href='../random'>
 <img id='main_image' src='{if $brazzify}http://brazzify.me/?s={/if}{$image}' name='{$image_name}' height='{$height}' width='{$width}' />
</a>
{include file='footer.tpl'}
