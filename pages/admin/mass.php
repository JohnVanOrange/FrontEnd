<?php
namespace JohnVanOrange\jvo;

$iface = new SiteInterface\Admin;

$iface->template('admin_mass');
echo $iface->render();