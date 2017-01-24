<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $datasource->uuid],
                ['confirm' => __('Are you sure you want to delete # {0}?', $datasource->uuid), 'class'=>'fa fa-trash']
            )
        ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index'], ['class'=>'fa fa-arrow-left']) ?></li>
    </ul>
</nav>
<div class="datasource form large-9 medium-8 columns content">
    <?= $this->Form->create($datasource) ?>
    <fieldset>
        <legend><?= __('Edit Datasource') ?></legend>
        <?php
            echo $this->Form->input('type');
            echo $this->Form->input('source');
            echo $this->Form->input('trusted');
            echo $this->Form->input('reject_zero_weight');
            echo $this->Form->input('accessed', ['empty' => true]);
            echo $this->Form->input('description');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

    <?= $this->Html->link(__('Fetch Events'), ['action' => 'edit', $datasource->uuid, 'fetch']) ?>
    
    <?php if($events) { ?>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col" width="50%">Event Name</th>
                <th scope="col">Place Name</th>
                <th scope="col">Event Start</th>
                <th scope="col">Weight</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $event): ?>
            <tr class='<?= $event->past ? "light-red":""?>'>
                <td><?= h($event->id) ?></td>
                <td class='hiddenController' ref='<?=$event->id?>'><?= h($event->event_name) ?></td>
                <td><?= h($event->place_name) ?>(<?= h($event->loc_city) ?>, <?= h($event->loc_country) ?>)</td>
                <td><?= h((new Datetime($event->event_start))->format('D, d/m/Y')) ?></td>
                <td><?= h($event->getWeight()) ?></td>
            </tr>
            <tr hidden hidden-ref='<?=$event->id?>'>
                <td colspan="4"><?= str_replace(["\r\n","\r","\n"], "<br/><br/>", $event->event_description) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php } ?>
</div>
