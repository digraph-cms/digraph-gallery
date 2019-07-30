<?php
namespace Digraph\Modules\Gallery;

use Digraph\DSO\Noun;
use Digraph\FileStore\FileStoreHelper;

class GalleryFile extends \Digraph\FileStore\FileStoreFile
{
    protected $title, $caption;

    public function __construct(array $e, Noun &$noun, string $path, FileStoreHelper &$fs)
    {
        parent::__construct($e, $noun, $path, $fs);
        $this->title = @$e['title'];
        $this->caption = @$e['caption'];
    }

    public function url($args=[])
    {
        $args['f'] = $this->uniqid();
        return $this->noun->url('gallery-file',$args);
    }

    public function titleSanitized()
    {
        return $this->noun->cms()->helper('filters')
            ->filterPreset($this->title(),'text-safe');
    }

    public function captionSanitized()
    {
        return $this->noun->cms()->helper('filters')
            ->filterPreset($this->caption(),'text-safe');
    }

    public function title($set=null)
    {
        if ($set !== null) {
            $this->set('title', $set);
            $this->title = $set;
        }
        if ($this->title) {
            return $this->title;
        }else {
            return trim(preg_replace('/\.(jpe?g|gif|png|tiff?|webp|bmp)$/i', '', $this->name()));
        }
    }

    public function caption($set=null)
    {
        if ($set !== null) {
            $this->set('caption', $set);
            $this->caption = $set;
        }
        return $this->caption;
    }
}
