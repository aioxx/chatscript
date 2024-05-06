<?php
include 'fns/firewall/load.php';
include 'fns/sql/load.php';
include 'fns/variables/load.php';

cache(['rebuild' => 'css_variables']);
cache(['rebuild' => 'css']);
cache(['rebuild' => 'js']);
cache(['rebuild' => 'languages']);
cache(['rebuild' => 'settings']);
cache(['rebuild' => 'sitemap']);
cache(['rebuild' => 'manifest']);
cache(['rebuild' => 'service_worker']);
cache(['rebuild' => 'site_roles']);
cache(['rebuild' => 'group_roles']);
?>
