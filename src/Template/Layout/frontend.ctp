<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="application-name" content="dark-party.net"/>
	<meta name="msapplication-TileColor" content="#000000"/>
	<meta charset="utf-8">
	<meta name="author" content="asc-ii">
	<meta name="description" content="dark-party.net - the darker party network"/>
	<meta id="meta" name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="image_src" href="https://dark-party.net/webroot/img/dpnet.png" />
	<meta property="og:url" content="https://dark-party.net/" />
	<meta property="og:image" content="https://dark-party.net/webroot/img/dpnet.png" />
	<meta property="og:title" content="dark-party.net - the darker party network for europe" />
	<meta property="og:description" content="find events around you. ebm, wave, gothic, synth-wave, electropop, industrial, neo-folk, electronica, minimal, noise..." />
	<title>dark-party.net</title>
	<?= $this->Html->css('reset.css') ?>
	<?= $this->Html->css('frontend.css') ?>
	<?= $this->Html->script('jquery.min.js') ?>
	<?= $this->Html->script('translate.js') ?>
    <link rel="stylesheet" href="//opensource.keycdn.com/fontawesome/4.7.0/font-awesome.min.css" integrity="sha384-dNpIIXE8U05kAbPhy3G1cz+yZmTzA6CY8Vg/u2L9xRnHjJiAK76m2BIEaSEV+/aU" crossorigin="anonymous">
</head>
<body>
	<header>
		<div class='maximize_button'><img src='./webroot/img/max.png'></div><a href='<?= $this->Url->build('/') ?>'><span class='dpn'>dpn</span> <span class='text'>dark-party.net</span></a>
	</header>
	
	<?= $this->fetch('content') ?>
	
	<footer>
		the darker party network for central europe
		| <a href="pages/imprint">imprint</a>
	</footer>
</body>

<script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	
	if(!location.href.match("dpnet")) {
		ga('create', 'UA-96356895-1', 'auto');
		ga('send', 'pageview');
	}
	var pageview = function(url) {
		if(!location.href.match("dpnet")) {
			ga('send', 'pageview', url);
		}
	}
</script>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
<script>
window.addEventListener("load", function(){
window.cookieconsent.initialise({
  "palette": {
    "popup": {
      "background": "#efefef",
      "text": "#404040"
    },
    "button": {
      "background": "transparent",
      "text": "#8ec760",
      "border": "#8ec760"
    }
  },
  "position": "bottom-right",
  "content": {
    "href": "//dark-party.net/pages/imprint"
  }
})});
</script>

</html>