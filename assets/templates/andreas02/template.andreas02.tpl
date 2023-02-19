<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=[(modx_charset)]" />
<title>[[++site_name]] | [[*pagetitle]]</title>
<link rel="stylesheet" type="text/css" href="assets/templates/andreas02/style/style2.css" media="screen,projection" />
<link rel="stylesheet" type="text/css" href="assets/templates/andreas02/style/print.css" media="print" />
<!--[if lte IE 6]>
<style type="text/css" media="screen, tv, projection">
body { behavior: url(assets/js/csshover.htc); }
</style>
<script type="text/javascript" src="assets/js/sleight.js"></script>
<![endif]-->
</head>
<body>

<div id="toptabs">
[[Wayfinder?startId=`1` &level=`2`]]
</div> <!-- end toptabs -->

<div id="container">

<div id="logo">
<h1><a href="[[~[[++site_start]]]]">[[++site_name]]</a></h1>
</div> <!-- end logo -->



<div id="navitabs">
<h2 class="hide">Site menu:</h2>
[[Wayfinder? &startId=`0` &level=`1`]]
</div> <!-- end navitabs -->

	

<div id="desc">
<h2>Welcome to andreas02!</h2>
<p>This is the second design I made for <a href="http://oswd.org">OSWD.org</a>. This time, I made a 2-column layout featuring two versions of my CSS tab menu. The design is made with XHTML and CSS, and it has no tables. To give some suggestions on how the design can be used, I have filled it with some example content.</p>
<p class="right"><a href="#">Read more...</a></p>
</div> <!-- end desc -->

    

<div id="main">
  [[*content]]
</div> <!-- end main -->

<div id="sidebar">
<div id="sideMenu">
[[Wayfinder? &startId=`[[*id]]` &displayStart=`1`]]
</div> <!-- end sideMenu -->
<h3>Wise words:</h3>
<p>"It happens every day: information overload! Time for a reboot..."<br />
(traditional haiku poem)</p>
</div> <!-- end sidebar -->

<div id="footer">
&copy; 2007 <a href="mailto:[[++emailsender]]">[[++site_name]]</a> | Content managed by <a href="http://www.modxcms.com">MODx</a> | Design by <a href="http://andreasviklund.com">Andreas Viklund</a>.
</div> <!-- end footer -->
</div> <!-- end container -->

</body>
</html>
