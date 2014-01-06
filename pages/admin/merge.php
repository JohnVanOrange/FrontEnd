<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Admin;

$iface->template('admin_merge');
echo $iface->render();