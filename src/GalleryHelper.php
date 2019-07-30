<?php
namespace Digraph\Modules\Gallery;

class GalleryHelper extends \Digraph\Helpers\AbstractHelper
{
    protected static $counter = 0;

    const ARGS = [
        'depth' => 0
    ];

    public function gallery_tag($context, $text, $args)
    {
        $args = array_replace(static::ARGS, $args);
        $items = $this->items($context, !!$args['nouns'], $args['depth']);
        return $this->galleryMarkup($items);
    }

    public function gallery($noun, $includeNouns=false, $depth=0)
    {
        $items = $this->items($noun, $includeNouns, $depth);
        return $this->galleryMarkup($items);
    }

    protected function defaultItemGenerator($noun)
    {
        $items = [];
        $path = null;
        if (defined(get_class($noun).'::GALLERY_PATH')) {
            $path = $noun::GALLERY_PATH;
        } elseif (defined(get_class($noun).'::FILESTORE_PATH')) {
            $path = $noun::FILESTORE_PATH;
        }
        if ($path) {
            $fs = $this->cms->helper('filestore');
            foreach ($fs->list($noun, $path) as $f) {
                if ($f instanceof GalleryFile) {
                    $items[] = GalleryItem::fromFile($f);
                }
            }
        }
        return $items;
    }

    protected function items($noun, $includeNouns=false, $depth=0)
    {
        $items = [];
        //get this object's items
        if (method_exists($noun, 'galleryItems')) {
            //prefer items from noun's galleryItems() method
            $items = $items + $noun->galleryItems();
        } else {
            //include nouns if no galleryItems() method exists and it is requested
            // if ($includeNouns) {
            //     $items[] = $this->nounItem($noun);
            // }
            //fall back to default item generator, which will try to make gallery items from files
            $items = $items + $this->defaultItemGenerator($noun);
        }
        //recursive strategy
        if ($depth != 0) {
            $graph = $this->cms->helper('graph');
            $graph->traverse(
                $noun['dso.id'],
                function ($id) use (&$items,$includeNouns,$noun) {
                    if ($id == $noun['dso.id']) {
                        return;
                    }
                    if ($n = $this->cms->read($id)) {
                        foreach ($this->items($n, $includeNouns) as $i) {
                            $items[] = $i;
                        }
                    }
                }
            );
        }
        //return full list
        return array_filter($items);
    }

    protected function galleryMarkup($items)
    {
        static::$counter++;
        $frameID = 'digraph-gallery-viewer-'.static::$counter;
        foreach ($items as &$i) {
            $i->target($frameID);
        }
        return '<div class="digraph-gallery">'.
            '<div class="digraph-gallery-items">'.implode('', $items).'</div>'.
            '<div class="digraph-gallery-viewer"><iframe id="'.$frameID.'" name="'.$frameID.'"></iframe></div>'.
            '</div>';
    }
}
