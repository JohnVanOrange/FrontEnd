{include file='head.tpl'}

<h1>{$site_name} API Docs</h1>

<div id='apidocs'>
 <p>{$site_name} provides an API to interact with all the features available through the general website.
 You can browse through the methods lists on this page to see what options are available.</p>
 
 <p>Here is an example of how to access a random image using jQuery:</p>
 
 <pre><code>
  $.get('{$web_root}api/image/random', function(data) {
    console.log(data);
  });
 </code></pre>
 
 <p>The response will look like:</p>
 
 <pre><code>
 {
   "id":"2005",
   "name":"f8e796dc9b2bb54843d24babbc98f6f0",
   "filename":"f8e796dc9b2bb54843d24babbc98f6f0.jpeg",
   "uid":"PINhTS",
   "hash":"47cb779eae621ba6117f6390afda108e",
   "type":"jpeg",
   "width":"1024",
   "height":"614",
   "display":"1",
   "nsfw":"0",
   "approved":"0",
   "tags":[],
   "page_title":"{$site_name}",
   "image_url":"http:\/\/media.{$web_root}\/media\/f8e796dc9b2bb54843d24babbc98f6f0.jpeg",
   "thumb_url":"http:\/\/thumbs.{$web_root}\/media\/thumbs\/f8e796dc9b2bb54843d24babbc98f6f0.jpeg",
   "page_url":"http:\/\/{$web_root}\/PINhTS",
   "response":"PINhTS"
 }
 </code></pre>
 
 <p>The 'uid' is really important and used to identify images. To add a tag to the previously mentioned image, this is how it would look:</p>
 
 <pre><code>
  $.post('{$web_root}api/tag/add',{
    image: 'PINhTS',
    name: 'Here is a new tag'
  }, function(data) {
    console.log(data);
  });
 </code></pre>
 
 <p>This is the response from that request:</p>
 
 <pre><code>
 {
   "message":"Tag added",
   "tags":[
      {
         "name":"Here is a new tag",
         "basename":"here-is-a-new-tag",
         "uid":"PINhTS",
         "url":"\/t\/here-is-a-new-tag"
      }
   ]
 }
 </code></pre>
 
</div>

<div id='classes'>
    <h2>Methods</h2>
    {foreach $classes as $classname}
     <p><a href='/docs/{$classname}'>{$classname}</a></p>
    {/foreach}
</div>

{include file='foot.tpl'}