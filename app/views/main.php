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
			<decorate template="hello/world">
				test
			</decorate>

			<include />
		</div>
	</body>
</html>
