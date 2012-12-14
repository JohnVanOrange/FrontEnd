{include file='head.tpl'}

<img src='{$image.image_url}' alt='Background image' id='info_bg' />

<div id='info'>

<img id="{$image.uid}" class="image" alt='Main Image' src='{$image.thumb_url}' />

<ul>
 <li>Type: {$image.type|upper}</li>
 <li>Width: {$image.width}</li>
 <li>Height: {$image.height}</li>
 {if $image.data.upload}<li>Added on {$image.data.upload.created|date_format}</li>{/if}
 {if $image.uploader.username}<li>Uploaded by <a href='/u/{$image.uploader.username}'>{$image.uploader.username}</a></li>{/if}
 {if $image.c_link}<li><a href='{$image.c_link}'>External comments</a></li>{/if}
 {if $image.approved}
  {if $image.nsfw}<li>Flagged NSFW</li>{/if}
 {/if}
 {if $image.saved}<li>Saved on {$image.data.save.created|date_format}</li>{/if}
 {if $image.tags}
  <li>
   <ul>Tags:
    {foreach name=tags from=$image.tags item=tag}<li><a href='/t/{$tag.basename}'>{$tag.name}</a></li>{/foreach}
   </ul>
  </li>
 {/if} 
</ul>

</div>

{include file='foot.tpl'}