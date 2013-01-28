{include file='head.tpl'}

<img src='{$image.image_url}' alt='Background image' id='info_bg' />

<div id='info'>

<h1>Image Details</h1>

<a href='{$image.page_url}'><img id="{$image.uid}" class="image" alt='Main Image' src='{$image.thumb_url}' /></a>

<ul>
 <li>Type: <span class='data'>{$image.type|upper}</span></li>
 <li>Width: <span class='data'>{$image.width}px</span></li>
 <li>Height: <span class='data'>{$image.height}px</span></li>
 {if $image.data.upload}<li>Added on {$image.data.upload.created|date_format}</li>{/if}
 {if $image.uploader.username}<li>Uploaded by <a href='/u/{$image.uploader.username}'>{$image.uploader.username}</a></li>{/if}
 {if $image.approved}
  {if $image.nsfw}<li>Flagged NSFW</li>{/if}
 {/if}
 {if $image.saved}<li>Saved{if $image.data.save.created} on {$image.data.save.created|date_format}{/if}</li>{/if}
 {if $image.tags}
  <li>
   <ul>Tags:
    {foreach name=tags from=$image.tags item=tag}<li class='icon-tag'><a href='/t/{$tag.basename}'>{$tag.name}</a></li>{/foreach}
   </ul>
  </li>
 {/if}
 <li>
  <ul>External Resources:
   <li class='icon-picture-1'><a href='http://regex.info/exif.cgi?url={$image.image_url}'>Additional image info (EXIF and technical data)</a></li>
   <li class='icon-google'><a href='hsttp://www.google.com/searchbyimage?image_url={$image.image_url}'>Reverse Google search</a></li>
   <li class='icon-search'><a href='http://tineye.com/search?url={$image.image_url}'>Reverse Tineye search</a></li>
   <li class='icon-tools'><a href='http://imgops.com/{$image.image_url}'>Image tools</a></li>
   {if $image.c_link}<li class='icon-comment'><a href='{$image.c_link}'>Comments</a></li>{/if}
  </ul>
 </li>
</ul>

</div>

{include file='foot.tpl'}