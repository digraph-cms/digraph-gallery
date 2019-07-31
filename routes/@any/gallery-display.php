<?php
//f arg is required, and indicates either a filename or a uniqid
if (!($f = $package['url.args.file'])) {
    $package->error(404, 'file not specified');
    return;
}

$package['fields.page_title'] = '';
$package['response.template'] = 'content-only.twig';

//ask filestore for matching files
$fs = $cms->helper('filestore');
$noun = $package->noun();
if (!($files = $fs->get($noun, $f))) {
    $package->error(404);
    return;
}

//if more than one file is returned, generate a 300 page with uniqid links
if (count($files) > 1) {
    $s = $cms->helper('strings');
    $package->error(300, 'Multiple files match');
    $package['response.300'] = [];
    foreach ($files as $f) {
        $args = $package['url.args'];
        $args['f'] = $f->uniqid();//use file's uniqid instead of filename
        $package->push('response.300', [
            'link' => $noun->link(
                $f->name().' uploaded '.$s->datetimeHTML($f->time()),//link text
                'file',//link verb
                $args,//args with uniqid
                true//canonical URL
            )
        ]);
    }
    return;
}

//finally if everything is good, output the file
$f = array_pop($files);
$package['fields.page_title'] = $package['fields.page_name'] = '';

//if image handler can do this file, use it
$i = $cms->helper('image');
$preset = $package['url.args.a'];
if (!$preset) {
    $preset = 'gallery-default';
}
$ext = preg_replace('/.+\./', '', $f->name());
if ($i->supports($ext)) {
    echo "<div class='digraph-gallery-display'>";
    echo "<div class='digraph-gallery-content'>";
    echo "<img src='".$f->imageUrl('gallery-full')."'>";
    echo "</div>";
    echo "<div class='digraph-gallery-meta'>";
    echo "<div class='centered-content'>";
    if ($f instanceof \Digraph\Modules\Gallery\GalleryFile) {
        echo "<h1>".$f->titleSanitized()."</h1>";
        echo "<div>".$f->captionSanitized()."</div>";
    }else {
        echo "<h1>".$f->name()."</h1>";
    }
    echo "</div>";
    echo "</div>";
    echo "</div>";
    return;
}

//output with fs if image handler can't process this file
$package->error(500,"Can't process file with image handler");
