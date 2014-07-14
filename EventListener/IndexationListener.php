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
     * Constructor
     *
     * @param \Jb\Bundle\SearchBundle\Search\SearchEngineInterface $searchEngine
     * @param \Jb\Bundle\SearchBundle\Search\DocumentBuilderInterface $builder
     */
    public function __construct(SearchEngineInterface $searchEngine, DocumentBuilderInterface $builder)
    {
        $this->searchEngine = $searchEngine;
        $this->documentBuilder = $builder;
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
     * Index on after run event
     *
     * @param \Sculpin\Core\Event\SourceSetEvent $event
     */
    public function afterRun(SourceSetEvent $event)
    {
        $documents = array();
        foreach ($event->allSources() as $item) {
            if ($item->data()->get('indexed')) {
                $documents[] = $this->documentBuilder->build($item);
            }
        }

        $this->searchEngine->synchronize($documents);
    }
}
