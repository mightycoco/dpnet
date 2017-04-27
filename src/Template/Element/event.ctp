<div class='event-item view-item swipe-h'>
	
	<col1>
		<img src='<?= $item->cover ?>' class='event-cover'/>
	</col1>
	<col2>
		<span class="badge"><?=$item->getWeight()?></span><a href='https://facebook.com/events/<?= $item->id ?>' target='facebook'><span class='fa fa-facebook-square'></span></a>
		<?= $this->Html->link(__($item->event_name), ['action' => 'edit', $item->id], ['class'=>'fa fa-edit']) ?>
		<?= $this->Form->postLink(__(''), 
										['action' => 'delete', $item->id], 
										['class'=>'fa fa-trash right', 'confirm' => __('Are you sure you want to delete "{0}"?', $item->event_name)]) ?>
		<div class='short-info'>
			<b class='date'><?= h($item->event_start ? $item->event_start->format("D. d M, Y @ H:i") : "n/a") ?> - <?= h($item->event_end ? $item->event_end->format("D. d M, Y @ H:i") : "n/a") ?></b><br/>
			<?= h($item->place_name) ?>, <?= h($item->loc_city) ?> (<?= h($item->loc_country) ?>)
			<?php if($item->datasource_id > 0) { ?>
				<?php if($item->datasource() != null) { ?>
					 | <a href='datasource/edit/<?= h($item->datasource()->uuid) ?>'>Datasource <?= h($item->datasource()->description) ?></a>
				<?php } else { ?>
					 | <a href='https://facebook.com/<?= h($item->datasource_id) ?>' target='facebook'>External <?= h($item->datasource_id) ?></a> | <a href='datasource/add-from-uri?uri=https://facebook.com/<?=$item->datasource_id?>' target='addDs'>Add</a>
				<?php } ?>
			<?php } ?>
			<br/>
			
			<span>
				<?php if($item->event_approval != 'approved'): ?>
		        <?= $this->Html->link(
		            	$this->Form->button('approve', array('class' => 'fa fa-check-square approve')),
						['action' => 'approve', $item->id],
						['escape' => false]
					) ?>
				<?php endif; ?>
				<?php if($item->event_approval != 'rejected'): ?>
		        <?= $this->Html->link(
		            	$this->Form->button('reject', array('class' => 'fa fa-minus-square reject')),
						['action' => 'reject', $item->id],
						['escape' => false]
					) ?>
				<?php endif; ?>
		        <?= $this->Html->link(
		            	$this->Form->button('info', array('class' => 'fa fa-chevron-circle-down more hiddenController', 'ref'=>$item->id)),
						[],
						['escape' => false]
					) ?>
			</span>
			
			<div class='content' hidden hidden-ref='<?= $item->id ?>'>
				<?php if(strlen($item->event_description) > 2): ?>
				<a href='#' class='translate button' state='original'>Translate</a><br/>
				<span class='text'><?= str_replace(["\r\n","\r","\n"], "<br/>", $item->htmlify($item->event_description)) ?></span>
				<span class='translated'></span>
				<?php endif; ?>
			</div>
		</div>
	</col2>
</div>