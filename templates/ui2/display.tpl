{include file='head.tpl'}

<div id='content'>

	<section id='img_container' itemscope itemtype="http://schema.org/ImageObject">
		<a id='rand_image' href='/?{$rand}' rel='nofollow'>
			<img id="{$image.uid}" class="image" alt='Main Image' src='{$image.image_url}' height='{$image.height}' width='{$image.width}' itemprop='contentUrl' />
		</a>
		{if isset($image.uploader)}
		<div><p class='icon-upload'>
			Uploaded by <a href='/u/{$image.uploader.username}'>{$image.uploader.username}</a>
		</p></div>
		{/if}
	</section>


	<span id='tags'>{if isset($image.tags.0)}{foreach name=tags from=$image.tags item=tag}<a class='tag' href='{$tag.url}'>{$tag.name}</a>{/foreach}{else}<em>no current tags</em>{/if}</span>
	<button class='icon-tag' id='add_tag'>Add Tag</button>

<br>

{if ($show_social == 1)}<div id='social'>
	<div id='facebook_like' class="fb-like" data-send="true" data-layout="button_count" data-show-faces="false" data-colorscheme="dark"></div>
	<div id='pintrest'><a href="http://pinterest.com/pin/create/button/?url={$image.page_url|escape:"url"}&amp;media={$image.image_url|escape:"url"}" class="pin-it-button" count-layout="horizontal"><img src="//assets.pinterest.com/images/PinExt.png" title="Pin It" alt='Pin It' /></a></div>
	<div class="g-plusone" data-size="medium"></div>
</div>{/if}

</div>

{include file='foot.tpl'}