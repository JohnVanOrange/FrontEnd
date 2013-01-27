{include file='head.tpl'}
<h1>Uploaded Images</h1>
<div id='carousel'>
{foreach from=$images item=image}
 <a href="{$image.page_url}"><img src="{$image.thumb_url}"></a>
{/foreach}
</div>
{include file='foot.tpl'}