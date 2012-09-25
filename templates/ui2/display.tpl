{include file='head.tpl'}

<div id='content'>

<!--This can be removed once reported images start using uid-->
<input type='hidden' name='image_id' id='image_id' value='{$image_id}'>

	<section id='img_container'>
		<a id='rand_image' href='../{$page}?{$rand}' rel='nofollow'>
			<img id="{$uid}" class="image" alt='Main Image' src='{$image}' height='{$height}' width='{$width}'/>
		</a>
	</section>



<fieldset>
	<legend>Tags</legend>
	<span id='tagtext'>{if $tags}{foreach name=tags from=$tags item=tag}<a href='{$tag.url}'>{$tag.name}</a>{if !$smarty.foreach.tags.last}, {/if}{/foreach}{else}<em>none currently </em>{/if}</span>
	<button id='add_tag'>Add</button>
</fieldset>

<br>

<div class="addthis_toolbox addthis_default_style ">
	<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
	<a class="addthis_button_google_plusone" g:plusone:count="false"></a>
</div>

</div>

{include file='foot.tpl'}