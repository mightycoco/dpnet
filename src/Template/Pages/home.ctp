<!DOCTYPE html>
<html lang="en">
<head>
	<title>dark-party.net</title>
	<meta charset="utf-8">
	<meta name="author" content="ascii">
	<meta name="description" content="dark-party.net - the darker party network"/>
	<?= $this->Html->css('reset.css') ?>
	<?= $this->Html->css('frontend.css') ?>
	<link href="//ianlunn.github.io/Hover/css/hover.css" rel="stylesheet" media="all">
    <link rel="stylesheet" href="https://opensource.keycdn.com/fontawesome/4.7.0/font-awesome.min.css" integrity="sha384-dNpIIXE8U05kAbPhy3G1cz+yZmTzA6CY8Vg/u2L9xRnHjJiAK76m2BIEaSEV+/aU" crossorigin="anonymous">
</head>
<body>
	<header>
		dark-party.net
	</header>
	
	<script>
		var events = <?php echo json_encode($events) ?>
	</script>
	
	<?php
		$pushpins = "";
		foreach ($events as $event) {
			$pushpins .= $event->loc_latitude."_".$event->loc_longitude."~";
		}
	?>
	
	<section id='map'>
	</section>
	
	<section id='event'>
		<article>
			<h1><etitle></etitle> <span class='fa fa-times-circle right' id='close_event'></span></h1>
			<img class='cover'/>
			<description></description>
		</article>
	</section>
	
	<section id='agenda'>
		<?php $prev_date = ''; ?>
		<?php foreach ($events as $event): ?>
		<?php 
			$this_date = $event->event_start->format('l d/m/Y');
			if($event->event_start->format('d/m/Y') == (new DateTime('NOW'))->format('d/m/Y')) {
				$this_date = 'Today';
			}
			if($event->event_start->format('d/m/Y') == (new DateTime('NOW'))->modify('+1 day')->format('d/m/Y')) {
				$this_date = 'Tomorrow';
			}
			if($prev_date != $this_date) {
				$prev_date = $this_date;
		?>
		<div class='event daysplit'>
			<name><?= $prev_date ?></name>
		</div>
		<?php
			}
		?>
        <div id='<?= $event->id ?>' class='event <?= in_array($event->event_start->format('N'), [5,6,7], false) ? "weekend" : ""  ?> hvr-sweep-to-bottom' index='1'>
        	<time><?= $event->event_start->format('D, d/m/Y H:i\h') ?></time>
        	<name><!--<a href='https://facebook.com/<?= $event->id ?>' target='fb'>--><?= $event->event_name ?><!--</a>--></name>
        	<place><?= $event->place_name ?></place>
        	<!--<a href="https://www.google.com/maps?q=<?= $event->loc_latitude ?>,<?= $event->loc_longitude ?>" target="gmaps">-->
        		<location><?= $event->loc_city ?> (<?= $event->loc_country ?>)</location>
        	<!--</a>-->
        </div>
        <?php endforeach; ?>
	</section>
	
	<footer>
		the darker party network for central europe
	</footer>
</body>
<?= $this->Html->script('jquery.min.js') ?>
<?= $this->Html->script('frontend.js') ?>
<script type='text/javascript' src='//www.bing.com/api/maps/mapcontrol?callback=GetMap&mkt=en-us' async defer></script>
</html>