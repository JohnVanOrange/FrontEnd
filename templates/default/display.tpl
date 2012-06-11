{include file='header.tpl'}
<p>Click picture for more{if $type != 'gif' AND !$brazzify}<span id='brazzers_text'>, or <a href='{$web_root}brazzify/{$image_name}' id='brazzify'>Brazzify</a></span>{/if}
</p>
<a href='../random?{$rand}'>
 <input type='hidden' name='uid' id='uid' value='{$uid}'>
 <input type='hidden' name='image_id' id='image_id' value='{$image_id}'>
 <img id='main_image' src='{if $brazzify}http://brazzify.me/?s={/if}{$image}' name='{$image_name}' height='{$height}' width='{$width}' alt='Main Image' />
</a>
<p id='tags'>
Tags: <span id='tagtext'>{if $tags}{foreach from=$tags item=tag}
{$tag} 
{/foreach}{else}<em>none currently </em>{/if}
</span>
<a href='' id='add_tag'>Add</a></p>
{if $c_link}
<p><a href='{$c_link}' id='c_link'>External Comments</a></p>
{/if}
{if $disqus_shortname}
{include file='disqus.tpl'}
{/if}
{include file='report_dialog.tpl'}
{include file='tag_dialog.tpl'}
{include file='footer.tpl'}
