<?php
namespace JohnVanOrange\jvo;

if ($user['type'] < 2) throw new Exception('Must be an admin to access', 401);