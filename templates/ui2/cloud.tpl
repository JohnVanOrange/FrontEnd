{include file='head.tpl'}
<h1>Tag Cloud</h1>
<div id='tag_cloud'>
{foreach from=$tags item=tag}
 <span class='tag_cloud_name' style='font-size: {math equation= 50 * $tag.count}%'><a href='/t/{$tag.basename}'>{$tag.name}</a></span>
{/foreach}
</div>
{include file='foot.tpl'}