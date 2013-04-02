<link rel='image_src' href='{$image.image_url}'>
<meta property='og:image' content='{$image.image_url}'>
<meta name='og:image:width' content='{$image.width}'>
<meta name='og:image:height' content='{$image.height}'>
<meta property='og:type' content='website'>
<meta property='og:title' content='{$image.page_title|escape}'>
<meta property='og:description' content='{$image.page_title|escape}'>
<meta property="og:url" content="{$current_url}">

<meta property="fb:admins" content="503316760">
{if isset($fb_app_id)}<meta property="fb:app_id" content="{$fb_app_id}">{/if}
 
<meta name="twitter:card" content="photo">
<meta name='twitter:title' content='{$image.page_title|escape}'>
<meta name='twitter:image' content='{$image.image_url}'>
<meta name='twitter:image:width' content='{$image.width}'>
<meta name='twitter:image:height' content='{$image.height}'>