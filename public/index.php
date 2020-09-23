<?php

require_once('../vendor/autoload.php');

use League\CommonMark\CommonMarkConverter;

class MetaReader {
    public function __construct($subject) {
        $separator = "\r\n";
        $line = strtok($subject, $separator);
        while ($line !== false) {
            list($key,$value) = explode(':', $line, 2);
            $this->$key = trim($value);
            $line = strtok($separator);
        }
        return $this;
    }
}

function maybe_404_file($file) : string {
	if (!$file) {
		header("HTTP/1.0 404 Not Found");
		$file = '../errors/404.md';
	}
	return $file;
}

function path_to_file(string $path_info) {
	$path_info = trim( $path_info, '/');
	$file = realpath("../{$path_info}.md" );
	$file = maybe_404_file($file);
	return $file;
}

function get_data(string $contents) : object {
	$content_sections = preg_split('/\R\R\R/', trim($contents), 2);
	$n = count($content_sections);
	if ($n > 1) {
		$data['meta'] = new MetaReader($content_sections[0]);
	}
	$converter = new CommonMarkConverter();
	$data['body'] = $converter->convertToHtml($content_sections[$n-1]);
	return (object)$data;
}

function get_pathinfo() : string {
    $path_info = '';
    if (isset($_SERVER['PATH_INFO'])) {
        $path_info = $_SERVER['PATH_INFO'];
    }
    return $path_info;
}

$path_info = get_pathinfo();
$file = path_to_file($path_info);
$contents = @file_get_contents($file) ?: '<h2>Sorry, content not found!</h2>'; 
$data = get_data($contents);
?>
<!DOCTYPE html>
<html>
<head>
	<title><?= @$data->meta->title ?: 'My Site' ?></title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<style type="text/css">
		body {
			font: 1em/1.5em Helvetica, Arial, sans-serif;
		}
	</style>
</head>

<body>
<header>
	<h1><?= @$data->meta->title ?: 'My Title' ?></h1>
	<nav>
<!-- 		<ul>
			<li>Website main section 1</li>
			<li>Website main section 2</li>
			<li>Website main section 3</li>
		</ul> -->
	</nav>
</header>

<main>
    <article>
		<?= $data->body ?>
    </article>
</main>
<!-- <aside>
	<p>Sidebar content</p>
</aside>

<footer>
  <p>This is my footer</p>
</footer> -->
</body>
</html>
