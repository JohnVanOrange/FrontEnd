{include file='header.tpl'}
<h1>Images tagged {$tag.name}</a></h1>
<p><a href='{$tag.random_url}'>View random images with this tag</a></p>
<div id='carousel'>
{foreach from=$images item=image}
 <a href="/v/{$image.uid}"><img class='cloudcarousel' src="/media/thumbs/{$image.filename}"></a>
{/foreach}
</div>
<p>Use scrollwheel to scroll images</p>
{include file='footer.tpl'}