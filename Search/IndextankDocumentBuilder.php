<?php

namespace Jb\Bundle\SearchBundle\Search;

use Sculpin\Core\Source\AbstractSource;

/**
 * Description of IndextankDocumentBuilder
 *
 * @author jobou
 */
class IndextankDocumentBuilder implements DocumentBuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(AbstractSource $source)
    {
        $sourceId = $source->sourceId();

        $tags = (is_array($source->data()->get('tags'))) ? $source->data()->get('tags') : array();
        $categories = (is_array($source->data()->get('categories'))) ? $source->data()->get('categories') : null;

        $fields = array(
            'title' => $source->data()->get('title'),
            'content' => strip_tags($source->content()),
            'tags' => implode(',', $tags),
            'link' => $source->permalink()->relativeUrlPath(),
            'date' => $source->data()->get('calculated_date'),
            'type' => 'document'
        );

        return $this->as_document(
            md5($sourceId),
            $fields,
            $categories
        );
    }

    /**
     * Copied from indextank php client
     * Because for write operation indexden need Authorization header
     */
    private function as_document($docid, $fields, $categories = NULL) {
        if (NULL == $docid) throw new \InvalidArgumentException("\$docid can't be NULL");
        if (mb_strlen($docid, '8bit') > 1024) throw new \InvalidArgumentException("\$docid can't be longer than 1024 bytes");

        $data = array("docid" => $docid, "fields" => $fields);

        if ($categories != NULL) {
            $data["categories"] = $categories;
        }
        return $data;
    }
}
