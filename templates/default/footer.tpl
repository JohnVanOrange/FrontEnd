{include file='addimage_dialog.tpl'}
{if $show_social}
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
<a class="addthis_button_tweet"></a>
<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
<a class="addthis_counter addthis_pill_style"></a>
</div>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f95e38340e66b80"></script>
<!-- AddThis Button END -->
{/if}
{if $show_brazz}<p id='brazz_credit'>Brazzification provided by <a href='http://brazzify.me'>brazzify.me</a></p>{/if}
<p id='bugs'>
{if $image_id}
<a href='' id='report'>Report Image</a> |
{/if}
<a href='' id='upload'>Add Images</a> | <a href='/tos'>Legal</a> | 
<a href='/m/'>Mobile</a> | 
<a href='https://github.com/cbulock/JohnVanOrange/issues/new'>Suggestions/Bugs?</a>
</p>
<script defer src='https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'></script>
<script defer src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js'></script>
<script defer src='{$web_root}js/jquery.imagefit.js'></script>
<script defer src='{$web_root}js/jquery.cookie.js'></script>
<script defer src='{$web_root}js/jquery.history.js'></script>
<script defer src='{$web_root}js/jquery.noty.js'></script>
<script defer src='{$web_root}js/main.js?20120617'></script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '{$ga}']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
