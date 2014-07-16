<?php

namespace Jb\Bundle\SearchBundle\EventListener;

use Sculpin\Core\Sculpin;
use Sculpin\Core\Event\SourceSetEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Jb\Bundle\SearchBundle\Search\SearchEngineInterface;
use Jb\Bundle\SearchBundle\Search\DocumentBuilderInterface;

/**
 * IndexationListener
 *
 * @author Jonathan Bouzekri <jonathan.bouzekri@gmail.com>
 */
class IndexationListener implements EventSubscriberInterface
{
    /**
     * @var \Jb\Bundle\SearchBundle\Search\SearchEngineInterface
     */
    private $searchEngine;

    /**
     * @var \Jb\Bundle\SearchBundle\Search\DocumentBuilderInterface
     */
    private $documentBuilder;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * Constructor
     *
     * @param \Jb\Bundle\SearchBundle\Search\SearchEngineInterface $searchEngine
     * @param \Jb\Bundle\SearchBundle\Search\DocumentBuilderInterface $builder
     * @param bool $enabled
     */
    public function __construct(SearchEngineInterface $searchEngine, DocumentBuilderInterface $builder, $enabled = true)
    {
        $this->searchEngine = $searchEngine;
        $this->documentBuilder = $builder;
        $this->enabled = $enabled;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            Sculpin::EVENT_AFTER_RUN => 'afterRun',
        );
    }

    /**
     * Override enabled parameter on runtime
     *
     * @param bool $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Index on after run event
     *
     * @param \Sculpin\Core\Event\SourceSetEvent $event
     */
    public function afterRun(SourceSetEvent $event)
    {
        if (!$this->enabled) {
            return;
        }

        $documents = array();
        foreach ($event->allSources() as $item) {
            if ($item->data()->get('indexed')) {
                $documents[] = $this->documentBuilder->build($item);
            }
        }

        $this->searchEngine->synchronize($documents);
    }
}
