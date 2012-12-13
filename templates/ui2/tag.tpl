{include file='head.tpl'}
<h1>Images tagged {$tag.name}</h1>
<div id='carousel'>
{foreach from=$images item=image}
 <a href="/v/{$image.uid}"><img class='cloudcarousel' src="{$image.thumb_url}"></a>
{/foreach}
</div>
{include file='foot.tpl'}