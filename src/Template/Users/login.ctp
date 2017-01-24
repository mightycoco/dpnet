<!-- File: src/Template/Users/login.ctp -->

<div class="users form">
<?= $this->Flash->render() ?>
<?= $this->Flash->render('auth') ?>
<?= $this->Form->create() ?>
    <fieldset>
        <legend><?= __('Please enter your username and password') ?></legend>
    	<fieldset>
        	<?= $this->Form->input('username') ?>
	        <?= $this->Form->input('password') ?>
	    </fieldset>
		<?= $this->Form->button(__('Login')); ?>
    </fieldset>
<?= $this->Form->end() ?>
</div>