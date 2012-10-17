{include file='header.tpl'}

<h1>Approve Image</h1>

<button id='approve'>Approve</button> <button id='nsfw'>Approve as NSFW</button> <button id='reject'>Reject</button> <button id='skip'>Skip</button></p>
<a id='rand_link' href='approve?{$rand}' rel='nofollow'>
 <input type='hidden' name='uid' id='uid' value='{$image.uid}'>
 <img id='main_image' src='{$image_loc}' name='{$image.name}' height='{$image.height}' width='{$image.width}' alt='Main Image' />
</a>

{include file='footer.tpl'}