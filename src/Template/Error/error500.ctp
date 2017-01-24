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
	</style>
	<body>
		Oh no, something happened... 
		<br/>
		<a href="<?= $home ?>">home</a>
	</body>
</html>