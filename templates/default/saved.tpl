{include file='header.tpl'}
<h1>Saved Images</h1>
<div id='carousel'>
{foreach from=$images item=image}
 <a href="/v/{$image.image}"><img class='cloudcarousel' src="/api/image/scale?image={$image.image}"></a>
{/foreach}
</div>
{include file='footer.tpl'}