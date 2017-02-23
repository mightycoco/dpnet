<!DOCTYPE html>
<html lang="en">
<head>
	<title>dark-party.net</title>
	<meta charset="utf-8">
	<meta name="author" content="ascii">
	<meta name="description" content="dark-party.net - the darker party network"/>
	<meta id="meta" name="viewport" content="width=device-width, initial-scale=1.0" />
	<?= $this->Html->css('reset.css') ?>
	<?= $this->Html->css('frontend.css') ?>
	<?= $this->Html->script('jquery.min.js') ?>
	<?= $this->Html->script('translate.js') ?>
    <link rel="stylesheet" href="//opensource.keycdn.com/fontawesome/4.7.0/font-awesome.min.css" integrity="sha384-dNpIIXE8U05kAbPhy3G1cz+yZmTzA6CY8Vg/u2L9xRnHjJiAK76m2BIEaSEV+/aU" crossorigin="anonymous">
</head>
<body>
	<header>
		<a href='<?= $this->Url->build('/') ?>'><span class='dpn'>dpn</span> <span class='text'>dark-party.net</span></a>
	</header>
	
	<?= $this->fetch('content') ?>
	
	<footer>
		the darker party network for central europe
		| <a href="pages/imprint">imprint</a>
	</footer>
</body>

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