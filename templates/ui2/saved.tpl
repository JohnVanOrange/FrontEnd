{include file='head.tpl'}
<h1>Saved Images</h1>
<div>
{foreach from=$images item=image}
 <div class='thumb_wrap'>
  <a href="{$image.page_url}"><img class='cloudcarousel' src="{$image.thumb_url}"></a>
 </div>
{/foreach}
</div>
{include file='foot.tpl'}