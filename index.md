---
layout: page
title: Behind the scenes of John VanOrange
---

Welcome to the John VanOrange Tech Blog.  I hope to update this with a number of technical posts the explain how the John VanOrange site works.

<ul class="posts">
  {% for post in site.posts %}
    <li><span>{{ post.date | date_to_string }}</span> &raquo; <a href="{{ BASE_PATH }}{{ post.url }}">{{ post.title }}</a></li>
  {% endfor %}
</ul>

