<!doctype html>
<html class="no-js" lang="">
	<head>
		<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
	</head>
	<body>
		<h1><?= $hello ?></h1>

        <?php

            $form = new Form(FORMS.'/main.json');


            echo '<pre>';
            print_r( htmlentities( $form->render() ) );
            echo '</pre>';

        ?>

        <?= $form->render() ?>
	</body>
</html>