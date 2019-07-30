<?php
namespace Digraph\Modules\Gallery;

class GalleryItem
{
    protected $url;
    protected $target;
    protected $content;
    protected $name;

    public static function fromFile($file)
    {
        $item = new GalleryItem();
        //get name (hover text)
        if ($file instanceof GalleryFile) {
            $item->name($file->title());
        } else {
            $item->name($file->name());
        }
        //get url from either noun or default gallery-display url
        $noun = $file->noun();
        if (method_exists($noun, 'galleryDisplayUrl')) {
            $item->url($noun->galleryDisplayUrl($file));
        }
        if (!$item->url()) {
            $item->url($noun->url('gallery-display', ['file'=>$file->uniqid()]));
        }
        //get content
        if ($file->isImage()) {
            $item->content("<img src='".$file->imageUrl('gallery-thumbnail')."'>");
        }
        //return
        return $item;
    }

    public function url($set=null)
    {
        if ($set !== null) {
            $this->url = $set;
        }
        return $this->url;
    }

    public function target($set=null)
    {
        if ($set !== null) {
            $this->target = $set;
        }
        return $this->target;
    }

    public function content($set=null)
    {
        if ($set !== null) {
            $this->content = $set;
        }
        return $this->content;
    }

    public function name($set=null)
    {
        if ($set !== null) {
            $this->name = $set;
        }
        return $this->name;
    }

    public function id()
    {
        return hash('crc32', $this->target().$this->url());
    }

    public function __toString()
    {
        return '<a class="digraph-gallery-item" id="'.$this->id().'" href="'.$this->url().'" title="'.$this->name().'" target="'.$this->target().'">'.$this->content().'</a>';
    }
}
