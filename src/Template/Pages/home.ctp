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
	<div id="mapv8"></div>
</section>

<article id='event'>
	<h1><span class='fa fa-close' id='close_event'></span><name></name></h1>
	<span class='timerow'><span class='fa fa-calendar-o'></span> <date></date> <span class='fa fa-clock-o'></span> <time></time></span>
	<scrollable>
		<img class='cover'/>
		<links>
			<a href='' class='extern-facebook' target='dpnet-facebook'><span class='fa fa-facebook-square'></span> Facebook</a> / 
			<a href='javascript' class='intern-ics'><span class='fa fa-calendar'></span> Export to Calendar</a>
		</links>
		<description></description>
	</scrollable>
	</article>
<gray>
<margin>
	<section id='agenda'>
		<?php foreach ($events as $event): ?>
		<?php
			$time = $event->event_start->format('D, d/m/Y H:i\h');
			if($event->event_start->isToday()) $time = 'Today '.$event->event_start->format('H:i\h');
			else if($event->event_start->isTomorrow()) $time = 'Tomorrow '.$event->event_start->format('H:i\h');
			else if($event->event_start->isThisWeek()) $time = $event->event_start->format('l H:i\h');
		?>
        <div id='<?= $event->id ?>' class='event_item <?= in_array($event->event_start->format('N'), [5,6], false) ? "weekend" : ""  ?>' index='1'>
        	<time><?=$time?></time>
        	<cover><?php if($event->cover): ?><img src='<?=$event->cover?>'/><?php else: ?><placeholder></placeholder><?php endif; ?></cover>
        	<info>
        		<name><!--<a href='https://facebook.com/<?= $event->id ?>' target='fb'>--><?= $event->event_name ?><!--</a>--></name>
	        	<place><?= $event->place_name ?></place>
	        	<!--<a href="https://www.google.com/maps?q=<?= $event->loc_latitude ?>,<?= $event->loc_longitude ?>" target="gmaps">-->
	        		<location> in <?= $event->loc_city ?> (<?= $event->loc_country ?>)</location>
	        	<!--</a>-->
        	</info>
        </div>
        <?php endforeach; ?>
	</section>
</margin>
</gray>

<?= $this->Html->script('frontend.js') ?>
<script type='text/javascript' src='//www.bing.com/api/maps/mapcontrol?callback=GetMap&mkt=en-us' async defer></script>