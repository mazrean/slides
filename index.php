<?php
// minify html
function sanitize_output($buffer) {
	$search = array(
		'/\>[^\S]+/s',  // strip whitespaces after tags, except space
		'/[^\S]+</s',  // strip whitespaces before tags, except space
		'/(\s)+/s'       // shorten multiple whitespace sequences
	);
	$replace = array(
		'>',
		'<',
		'\\1'
	);
	$buffer = preg_replace($search, $replace, $buffer);
	return $buffer;
}
ob_start("sanitize_output");

$slideDirectories = array_filter(scandir('./dist/'), function($dir) {
    return is_dir($dir) && $dir !== '.' && $dir !== '..' && !preg_match('/^\..*/', $dir);
});
$slideDirectories = array_map(function($dir) {
    return htmlspecialchars($dir, ENT_QUOTES, 'UTF-8');
}, $slideDirectories);
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.9.2/dist/css/uikit.min.css" integrity="sha512-UHBrmHVNXc5h0FJ4G3HOmy/E4cFaWCUrFWtLRrr6D21v5oiOJg+GV1jSgjnNgQgIjZUmFlfou1ktGAh0/hxPsQ==" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.9.2/dist/js/uikit.min.js" integrity="sha512-hwXvTG80g7qjy6skV0/5hvMV2m1h/Gy6SC9UkCoFG6za2CBphStzTPqlS3vdlIxPAkA4rUhhiKc38gRUAn9/9A==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/uikit@3.9.2/dist/js/uikit-icons.min.js" integrity="sha512-TSwLL8y0O6IWOVlTP6TOGq+dG1iDFZOk8sjxu00t3cY1mHOF/9vCeP0jt0I4LQBHkx3TRddCoIdS6O2mDeqIRw==" crossorigin="anonymous"></script>
    <title>スライド一覧</title>
  </head>
  <body>
    <div class="uk-background-default uk-padding">
      <p class="uk-h1 uk-text-lead">スライド一覧</p>
      <ul class="uk-list uk-list-circle uk-list-muted uk-link-muted">
        <?php foreach ($slideDirectories as $dir): ?>
          <li><a href="<?= $dir ?>/"><?= $dir ?></a></li>
        <?php endforeach ?>
      </ul>
    </div>
  </body>
</html>
