<div class='event-item view-item'>
	<span class='actions'>
		<button class="dropdown">
			<span class='fa fa-list'></span>
			<div class="dropdown-content">
                <?= $this->Html->link(__('Edit'), ['action' => 'edit', $item->id], ['class'=>'fa fa-edit']) ?>
				<?= $this->Form->postLink(__('Delete'), 
										['action' => 'delete', $item->id], 
										['class'=>'fa fa-trash', 'confirm' => __('Are you sure you want to delete # {0}?', $item->name)]) ?>
			</div>
		</button>
	</span>
	<span class='id'><span class="badge"><?=$item->getWeight()?></span><?= $item->id ?></span>
	<span class='type'><span class="label"><?= $item->event_start->format("d/m/Y H:i:s") ?></span></span>
	<span class='description'><?= $this->Html->link(__($item->event_name), ['action' => 'edit', $item->id]) ?></span>
	<span class='source'><span class='fa fa-<?= $item->approval_icon()?>'><?= $item->event_approval ?></span></span>
	<span class='created'>
		<?= h($item->place_name) ?> (<?= h($item->loc_city) ?>, <?= h($item->loc_country) ?>)
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
	</span>
	<span class='content' hidden hidden-ref='<?= $item->id ?>'>
		<img src='<?= $item->cover ?>' class='event-cover'/><br/>
		<?= str_replace(["\r\n","\r","\n"], "<br/>", $item->htmlify($item->event_description)) ?>
	</span>
</div>