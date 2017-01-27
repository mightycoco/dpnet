<!doctype html>
<?php 
use Cake\Routing\Router;
$home  = Router::fullbaseUrl().Router::url(['controller' => '', 'action' => ' ']);
?>
<html>
	<head>
		<title>Oh no, something happened</title>
	</head>
	<style>
		body {
			padding: 0px;
			margin: auto auto;
			outline: 1px  solid red;
			width: 100%;
			height: 100%;
			background: #333;
			color: #999;
			font: verdana;
		}
		a {
			color:#008CBA;
		}
		.debugError {
			background:#eff;
			max-width: 900px;
			margin: auto auto;
			min-height: 50%;
		}
	</style>
	<body>
		Oh no, something happened... 
		<br/>
		<a href="<?= $home ?>">home</a>
			<?php
			use Cake\Core\Configure;
			use Cake\Error\Debugger;

			if (Configure::read('debug')):
			?>
			<div class='debugError'>
			<?php if (!empty($error->queryString)) : ?>
			    <p class="notice">
			        <strong>SQL Query: </strong>
			        <?= h($error->queryString) ?>
			    </p>
			<?php endif; ?>
			<?php if (!empty($error->params)) : ?>
			        <strong>SQL Query Params: </strong>
			        <?php Debugger::dump($error->params) ?>
			<?php endif; ?>
			<?= $this->element('auto_table_warning') ?>
			<p class="error">
			    <strong><?= __d('cake', 'Error') ?>: </strong>
			    <?= __d('cake', 'The requested address {0} was not found on this server.', "<strong>'{$url}'</strong>") ?>
			</p>
			</div>
			<?php
			    if (extension_loaded('xdebug')):
			        xdebug_print_function_stack();
			    endif;
			
			    $this->end();
			endif;
			?>

	</body>
</html>