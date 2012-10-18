{include file='header.tpl'}

<h1>Reported Image</h1>

<p>Reason: {$image.report.value}

<button id='approve'>Approve</button> <button id='nsfw'>Approve as NSFW</button> <button id='reject'>Reject</button> <button id='skip'>Skip</button></p>
	<section id='img_container' itemscope itemtype="http://schema.org/ImageObject">
     <a id='rand_link' href='reported?{$rand}' rel='nofollow'>
      <img id="{$image.image.uid}" class="image" alt='Main Image' src='{$image_loc}' height='{$image.image.height}' width='{$image.image.width}' itemprop='contentUrl' />
     </a>
	</section>

{include file='footer.tpl'}