{include file='header.tpl'}
<div id='carousel'>
{foreach from=$images item=image}
 <a href="/v/{$image.image}"><img class='cloudcarousel' src="/media/thumbs/{$image.filename}"></a>
{/foreach}
</div>
{include file='footer.tpl'}