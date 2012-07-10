{include file='header.tpl'}
<p id='head_text'>Click picture for more. {if $type != 'gif' AND !$brazzify AND $show_brazz}<span id='brazzers_text'><a href='{$web_root}brazzify/{$image_name}' id='brazzify'> Brazzify image</a></span>{/if}
 Search image on: <a href='http://www.google.com/searchbyimage?image_url={$image}'>Google</a> | <a href='http://tineye.com/search?url={$image}'>Tineye</a>
</p>
{if $tag_name}<p id='tag_filter'>Images filtered by tag {$tag_name}. <a href='{$web_root}'>View all images</a></p>{/if}
<a id='rand_link' href='../random?{$rand}' rel='nofollow'>
 <input type='hidden' name='uid' id='uid' value='{$uid}'>
 <input type='hidden' name='image_id' id='image_id' value='{$image_id}'>
 <img id='main_image' src='{if $brazzify}http://brazzify.me/?s={/if}{$image}' name='{$image_name}' height='{$height}' width='{$width}' alt='Main Image' />
</a>
<div id='tags'>
Tags: <span id='tagtext'>{if $tags}{foreach from=$tags item=tag}
<a href='{$tag.url}'>{$tag.name}</a> 
{/foreach}{else}<em>none currently </em>{/if}
</span>
<a href='' id='add_tag'>Add</a></div>
{if $c_link}
<p><a href='{$c_link}' id='c_link'>External Comments</a></p>
{/if}
{if $disqus_shortname}
{include file='disqus.tpl'}
{/if}
<div id='empty'>
 {if $user}<div id='star' class='{if !$data.save}not_{/if}saved'></div>{/if}
</div>
{include file='tag_dialog.tpl'}
{include file='footer.tpl'}