{include file='head.tpl'}
<h1>Uploaded Images</h1>
<div>
{foreach from=$images item=image}
 <div class='thumb_wrap'>
  <a href="{$image.page_url}"><img src="{$image.thumb_url}"></a>
 </div>
{/foreach}
</div>
{include file='foot.tpl'}