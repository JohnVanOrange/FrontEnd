{include file='header.tpl'}
<div id='carousel'>
{foreach from=$images item=image}
 <a href="/v/{$image.image}"><img class='cloudcarousel' src="/api/image/scale?image={$image.image}"></a>
{/foreach}
</div>
<input id="left-but"  type="button" value="Left" />
<input id="right-but" type="button" value="Right" />
{include file='footer.tpl'}