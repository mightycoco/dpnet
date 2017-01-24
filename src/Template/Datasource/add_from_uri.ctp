<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Back'), ['action' => 'index'], ['class'=>'fa fa-arrow-left']) ?></li>
    </ul>
</nav>
<div class="datasource form large-9 medium-8 columns content">
    <?= $this->Form->create($uri) ?>
    <fieldset>
        <legend><?= __('Add Datasource') ?></legend>
        <?php
            echo $this->Form->input('uri');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
