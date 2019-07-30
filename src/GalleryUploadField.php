<?php
namespace Digraph\Modules\Gallery;

use Digraph\DSO\Noun;

class GalleryUploadField extends \Digraph\Forms\Fields\FileStoreFieldMulti
{
    protected $metaFields = [];

    protected function &metaField(GalleryFile $file)
    {
        if (!$this->metaFields[$file->uniqid()]) {
            $this->metaFields[$file->uniqid()] = new \Formward\Fields\Container('', $file->uniqid().'_meta');
            $this->metaFields[$file->uniqid()]['title'] = new \Formward\Fields\Input('Title');
            $this->metaFields[$file->uniqid()]['title']->default($file->title());
            $this->metaFields[$file->uniqid()]['caption'] = new \Formward\Fields\Textarea('Caption');
            $this->metaFields[$file->uniqid()]['caption']->default($file->caption());
        }
        return $this->metaFields[$file->uniqid()];
    }

    /**
     * Needs to create ordering field sub-fields named by file uniqids,
     * which will be used in hook_formWrite to save titles/captions.
     *
     * @param Digraph\DSO\Noun $noun
     * @return void
     */
    public function dsoNoun(&$noun)
    {
        $this->noun = $noun;
        if ($files = $this->nounValue()) {
            $opts = [];
            foreach ($files as $file) {
                $opts[$file->uniqid()] = $file->metaCard(false, true);
                if ($file instanceof GalleryFile) {
                    $opts[$file->uniqid()] .= $this->metaField($file);
                }
            }
            $this['current']->opts($opts);
        }
    }

    /**
     * Needs to identify form fields created in dsoNoun, and write their values into
     * the filestore.
     *
     * @param Digraph\DSO\Noun $noun
     * @param array $map
     * @return void
     */
    public function hook_formWrite(Noun &$noun, array $map)
    {
        parent::hook_formWrite($noun, $map);
        if ($files = $this->nounValue()) {
            foreach ($files as $file) {
                if ($file instanceof GalleryFile) {
                    $field = $this->metaField($file);
                    $file->title($field['title']->value());
                    $file->caption($field['caption']->value());
                }
            }
            $noun->update();
        }
    }
}
