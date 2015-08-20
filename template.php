<?php
/**
 * Barebones template.php to call index.php
 * Activate child theme notice
 *
 * @package 	WordPress
 * @subpackage 	Simple
 * @version 	1.0
*/
?>

<!DOCTYPE html>

	<head>
		<meta charset="utf-8">
		<title><?php __(wp_title('&laquo;', true, 'right'), SIMPLE_THEME_SLUG); ?></title>
	</head>

	<body <?php body_class(); ?>>

		<main role="main" id="main">

			<?php include simple_template_path(); ?>

		</main>

	</body>

</html>