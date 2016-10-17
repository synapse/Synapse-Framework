<!doctype html>
<html class="no-js" lang="">
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	</head>
	<body>
		<h1><?= $hello ?></h1>

		<div>
			<!--<include template="include" />-->

			<decorate template="decorator">
				<h1>1. Hello decorator</h1>
				<h1>2. Hello decorator</h1>
			</decorate>
		</div>
	</body>
</html>
