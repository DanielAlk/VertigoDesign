<!DOCTYPE html>
<html>
<head>
	<?php include 'layouts/_head.php'; ?>
	<link href="<?php $asset->css(); ?>" rel="stylesheet" type="text/css">
</head>

<body>
	<?php include 'layouts/_nav.php'; ?>
	<?php include $app->view; ?>
	<?php include 'layouts/_footer.php'; ?>
	<script src="<?php $asset->js(); ?>" type="application/javascript"></script>
	<script>
		$(function() {
			<?php if (isset($extra_js)) echo $extra_js; ?>
		});
	</script>
	<?php if (isset($log)) echo "<script>console.log(JSON.parse('$log'));</script>"; ?>
</body>
</html>