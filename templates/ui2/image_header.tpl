<link rel='image_src' href='{$image}'>
<meta property='og:image' content='{$image}'>
 <meta name='og:image:width' content='{$width}'>
<meta name='og:image:height' content='{$height}'>
<meta property='og:title' content='{$site_name}{if $title_text} - {$title_text}{/if}'>
<meta property="og:type" content="image">
<meta property="og:url" content="{$current_url}">

<meta property="fb:admins" content="503316760">
<meta property="fb:app_id" content="416046151777678">
 
<meta name="twitter:card" content="photo">
<meta name='twitter:title' content='{$site_name}{if $tags} -{foreach from=$tags item=tag} {$tag.name}{/foreach}{/if}'>
<meta name='twitter:image' content='{$image}'>
<meta name='twitter:image:width' content='{$width}'>
<meta name='twitter:image:height' content='{$height}'>