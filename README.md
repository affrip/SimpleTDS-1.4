# SimpleTDS-1.4

SimpleTDS is a Traffic Distribution System (TDS) written in php
Traffic distribution system allows you to merge all your streams in a single link
this way you can promote multiple offers to users with only one url which is much
more effective, furthermore TDS allows you to redirect to different url based on
many parameters from the users browser, get parameters, server variables, referrer...

Simpletds is an old software but now it's brought back to life with this update to php 8.2!

Beware of the SQL injections in this program its very old and compatibility layer does not prevent them!
Do not use the root database user and do not store sensitive information in database accessible by simpletds db user! 

This is a mod of simpletds with the following changes:
- fixed RCE exploit (https://github.com/affrip/stds1.3-rce)
- Added compatibility layer so it works with php 8.2 and php-mysqli (compat.php)

to install simply upload the mysql database and update config.php accordingly with the database information then you can access the admin panel
and start using simpletds :)

Visit our site for more https://aff.rip/
Donate: bc1qfy8ldp4ggzetmak8jyml9mqlla9h3dreqznge7