<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Authenticate'), ['action' => 'authenticate']) ?></li>
        <li><?= $this->Html->link(__('Test Fetch'), ['action' => 'test_fetch_events']) ?></li>
    </ul>
</nav>
<div class="users index large-9 medium-8 columns content">
    <h3><?= __('Subsystem') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('start') ?></th>
                <th scope="col"><?= $this->Paginator->sort('end') ?></th>
                <th scope="col"><?= $this->Paginator->sort('Place') ?></th>
            </tr>
        </thead>
        <tbody>
		<?php foreach ($events as $event): ?>
			<tr>
                <td><b><?=$event['name']?></b></td>
                <td><?=$event['start_time']->format(DATE_RFC1036)?></td>
                <td><?=$event['end_time']->format(DATE_RFC1036)?></td>
                <td><?=$event['place']['name']?></td>
            </tr>
            <tr>
            	<td></td>
            	<td colspan="3">
            		<pre><?=$event['description']?></pre>
            	</td>
            </tr>
		<?php endforeach; ?>
		</tbody>
    </table>
</div>
