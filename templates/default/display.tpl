{include file='header.tpl'}
<p id='head_text'>Click picture for more.
 Search image on: <a href='http://www.google.com/searchbyimage?image_url={$image}'>Google</a> | <a href='http://tineye.com/search?url={$image}'>Tineye</a>
</p>
{if $tag_name}<p id='tag_filter'>Images filtered by tag {$tag_name}. <a href='{$web_root}'>View all images</a></p>{/if}
<div id='img_container'>
<a id='rand_link' href='../random?{$rand}' rel='nofollow'>
 <input type='hidden' name='uid' id='uid' value='{$uid}'>
 <input type='hidden' name='image_id' id='image_id' value='{$image_id}'>
 <img id='main_image' src='{if $brazzify}http://brazzify.me/?s={/if}{$image}' {if $brazzify}class='brazzified' {/if}name='{$image_name}' height='{$height}' width='{$width}' alt='Main Image' />
</a>
</div>
<p id='tags'>
 Tags: <span id='tagtext'>{if $tags}{foreach from=$tags item=tag}
 <a href='{$tag.url}'>{$tag.name}</a> 
{/foreach}{else}<em>none currently </em>{/if}
 </span>
 <button id='add_tag'>Add</button>
</p>
{if $c_link}
<p><a href='{$c_link}' id='c_link'>External Comments</a></p>
{/if}
{if $disqus_shortname}
{include file='disqus.tpl'}
{/if}
<div class='empty'>
 <!--{if $type != 'gif' AND !$brazzify AND $show_brazz}<div id='brazzers_text'></div>{/if}-->
 {if $type != 'gif' AND !$brazzify AND $show_brazz}<div id='brazzers_text'> <a href='{$web_root}brazzify/{$image_name}' id='brazzify'><img src='/img/brazzify_24.png' height=24 width=122 alt='Brazzify Image' /></a></div>{/if}
</div>
<div class='empty'>
 <div id='star' class='{if !$data.save}not_{/if}saved' title='Save Image'></div>
</div> 

</div>
{include file='report_dialog.tpl'}
{include file='tag_dialog.tpl'}
{include file='footer.tpl'}
