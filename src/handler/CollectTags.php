<?php namespace ostark\upper\handler;


use craft\events\PopulateElementEvent;
use ostark\upper\Plugin;

class CollectTags extends AbstractPluginEventHandler implements InvokeEventHandlerInterface
{
    /**
     * @param \craft\events\PopulateElementEvent $event
     */
    public function __invoke($event)
    {
        // Don't collect MatrixBlock and User elements for now
        if (!Plugin::getInstance()->getSettings()->isCachableElement(get_class($event->element))) {
            return;
        }

        // Tag with GlobalSet handle
        if ($event->element instanceof \craft\elements\GlobalSet) {
            Plugin::getInstance()->getTagCollection()->add($event->element->handle);
        }

        // Avoid tagging sections for single entry
        if (array_key_exists('uri', $event->row)) {
            if ($this->plugin->requestUri == $event->row['uri']) {
                $event->row['sectionId'] = null;
            }
        }

        // Add to collection
        Plugin::getInstance()->getTagCollection()->addTagsFromElement($event->row);
    }
}
