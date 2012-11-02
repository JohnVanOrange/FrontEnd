{include file='head.tpl'}

<div id='content'>

	<section id='img_container' itemscope itemtype="http://schema.org/ImageObject">
		<a id='rand_image' href='../{$page}?{$rand}' rel='nofollow'>
			<img id="{$uid}" class="image" alt='Main Image' src='{$image}' height='{$height}' width='{$width}' itemprop='contentUrl' />
		</a>
		<div><p{if !$uploader} style='display:none'{/if}>
			Uploaded by <a href='/u/{$uploader.username}'>{$uploader.username}</a>
		</p></div>
	</section>

<fieldset>
	<legend>Tags</legend>
	<span id='tagtext'><span class='tag'{if !$tags} style='display:none'{/if}>{if $tags}{foreach name=tags from=$tags item=tag}<a href='{$tag.url}'>{$tag.name}</a>{if !$smarty.foreach.tags.last}, {/if}{/foreach}{/if}</span><span class='notag'{if $tags} style='display:none'{/if}><em>none currently </em></span></span>
	<button id='add_tag'>Add</button>
</fieldset>

<br>

<div id='social'>
	<div id='facebook_like' href='{$current_url}' class="fb-like" data-send="true" data-layout="button_count" data-show-faces="false" data-colorscheme="dark"></div>
	<!--<a class="addthis_button_google_plusone" g:plusone:count="false"></a>-->
</div>

</div>

{include file='foot.tpl'}