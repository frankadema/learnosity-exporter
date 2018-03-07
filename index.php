<?php

if (isset($_GET['path'])) {
    $path = urldecode($_GET['path']);
} else {
    $path = __DIR__.'/data';
}

if (is_file($path)) {
    $contents = json_encode(json_decode(file_get_contents($path)), JSON_PRETTY_PRINT);
} else {
    $files = glob($path.'/*');
}

?><!doctype html>
<html>
    <body>
        <a href="/">root folder</a>

        <?php if (is_file($path)) { ?>
            <pre><?php echo htmlentities($contents); ?></pre>
        <?php } else { ?>
            <ul>
                <?php foreach ($files as $file) { ?>
                    <li><a href="/?path=<?php echo urlencode($file) ?>"><?php echo $file ?></a></li>
                <?php } ?>
            </ul>
        <?php } ?>
    </body>
</html>
