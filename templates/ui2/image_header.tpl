<link rel='image_src' href='{$image}'>
<meta property='og:image' content='{$image}'>
<meta property='og:title' content='{$site_name}{if $tags} -{foreach from=$tags item=tag} {$tag.name}{/foreach}{/if}'>
 
<meta name="twitter:card" content="photo">
<meta name='twitter:title' content='{$site_name}{if $tags} -{foreach from=$tags item=tag} {$tag.name}{/foreach}{/if}'>
<meta name='twitter:image' content='{$image}'>
<meta name='twitter:image:width' content='{$width}'>
<meta name='twitter:image:height' content='{$height}'>