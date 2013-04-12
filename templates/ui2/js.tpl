<script type='text/javascript'>
 var web_root = '{$web_root}';
 var site_name = '{$site_name}';
 var icon_set = '{$icon_set}';
 var fb_app_id = '{$fb_app_id}';
</script>

<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js'></script>
<script src='https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js'></script>
<script async src='{$web_root}components/history.js/scripts/bundled/html5/jquery.history.js'></script>
<script src='{$web_root}components/noty/js/noty/jquery.noty.js'></script>
<script src='{$web_root}components/noty/js/noty/layouts/topLeft.js'></script>
<script src='{$web_root}components/noty/js/noty/themes/default.js'></script>
<script src='{$web_root}js/ui2.js?20130411'></script>

<script async src="//assets.pinterest.com/js/pinit.js"></script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id; js.async = true;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

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

<script type="text/javascript">
  var feedback_widget_options = {};

  feedback_widget_options.display = "overlay";  
  feedback_widget_options.company = "johnvanorange";
  feedback_widget_options.placement = "left";
  feedback_widget_options.color = "#222";
  feedback_widget_options.style = "idea";
</script>
