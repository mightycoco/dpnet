<!DOCTYPE html>
<html lang="en">
<head>
	<title>dark-party.net</title>
	<meta charset="utf-8">
	<meta name="author" content="ascii">
	<meta name="description" content="dark-party.net - the darker party network"/>
	<meta id="meta" name="viewport" content="width=device-width; initial-scale=1.0" />
	<?= $this->Html->css('reset.css') ?>
	<?= $this->Html->css('frontend.css') ?>
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
	
	<div class='map_background'>
	<section id='map'>
	</section>
	</div>
	
	<section id='event'>
		<article>
			<h1><span class='fa fa-chevron-left' id='close_event'></span><etitle></etitle></h1>
			<img class='cover'/>
			<description></description>
		</article>
	</section>
	
	<section id='agenda'>
		<?php foreach ($events as $event): ?>
		<?php
			$time = $event->event_start->format('D, d/m/Y H:i\h');
			if($event->event_start->isToday()) $time = 'Today '.$event->event_start->format('H:i\h');
			else if($event->event_start->isTomorrow()) $time = 'Tomorrow '.$event->event_start->format('H:i\h');
			else if($event->event_start->isThisWeek()) $time = $event->event_start->format('l H:i\h');
		?>
        <div id='<?= $event->id ?>' class='event <?= in_array($event->event_start->format('N'), [5,6], false) ? "weekend" : ""  ?> hvr-sweep-to-bottom' index='1'>
        	<time><?=$time?></time>
        	<cover><?php if($event->cover): ?><img src='<?=$event->cover?>'/><?php else: ?><placeholder></placeholder><?php endif; ?></cover>
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