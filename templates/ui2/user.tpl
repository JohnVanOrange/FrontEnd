{include file='head.tpl'}
<h1>Profile for {$profile.username}</h1>

<div id='content'>
 
<div id='profile_image'>
 <img src='http://www.gravatar.com/avatar/{$profile.email_hash}?s=300&d=retro&r=pg' alt='Profile image for {$profile.username}'>
 {if $profile.id == $user.id}<p><a href='http://gravatar.com'>Update Profile Image with Gravatar</a></p>{/if}
</div>
{if $profile.id == $user.id}<p><a href='/s/{$profile.username}'>View your saved images</a></p>{/if}

</div>
{include file='foot.tpl'}