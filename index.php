<!DOCTYPE html>
<html>
	<head>
		<title>Chemistree Counter</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="shortcut icon" type="image/ico" href="icon.ico">

		<script>0</script>
	</head>

	<body>
		<?php require 'request.php'; ?>
		<h1>
			Fanfics Since Chemistree Last Updated <a href="https://archiveofourown.org/works/58842772">Calling Names</a>:
		</h1>

		<?php if (isset($worksArray)): ?>
		<div id="count">
			<?php echo count($worksArray); ?>
		</div>
		<?php else: ?>
		<p>Could not fetch Ao3</p>
		<p>
			<?php echo "curl error: " . curl_error($ch); ?>
		</p>
		<?php endif; ?>

		<div id="works">
			<?php foreach($worksArray as $work): ?>
			<h2 id="title">
				<span><a href="https://archiveofourown.org<?=$work->url;?>"><?=$work->title;?></a></span> <span>Chapter: <?=$work->chapter;?></span>
			</h2>
			<div>
				<?php foreach($work->summary as $paragraph): ?>
				<p>
					<?=$paragraph;?>
				</p>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</body>
</html>