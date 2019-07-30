<?php
namespace Digraph\Modules\Gallery;

class Gallery extends \Digraph\DSO\Noun
{
    const FILESTORE = true;
    const ROUTING_NOUNS = ['gallery'];
    const FILESTORE_FILE_CLASS = GalleryFile::class;
    const FILESTORE_PATH = 'filefield';
    const GALLERY_PATH = 'filefield';

    public function formMap(string $action) : array
    {
        $map = parent::formMap($action);
        $map['file'] = [
            'weight' => 250,
            'label' => 'Gallery files',
            'class' => 'Digraph\\Modules\\Gallery\\GalleryUploadField',
            'required' => false,
            'extraConstructArgs' => [static::GALLERY_PATH]
        ];
        $map['disable_auto'] = [
            'weight' => 251,
            'label' => 'Disable automatic [gallery] tag. To place a gallery on the page with this option enabled you\'ll need to include your own [gallery] tag in the body.',
            'class' => 'checkbox',
            'required' => false,
            'field' => 'gallery.disable_auto'
        ];
        return $map;
    }
}
