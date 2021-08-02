<?php

namespace Sup7even\Mailchimp\Hooks\Frontend;

use TYPO3\CMS\Form\Domain\Model\FormElements\GenericFormElement;
use TYPO3\CMS\Form\Domain\Model\Renderable\RenderableInterface;
use TYPO3\CMS\Form\Domain\Finishers\AbstractFinisher;
use TYPO3\CMS\Form\Domain\Finishers\FinisherInterface;
use Sup7even\Mailchimp\Domain\Model\FormElements\Interests;
use Sup7even\Mailchimp\Domain\Finishers\MailchimpFinisher;

class Form
{
    public function afterBuildingFinished(\TYPO3\CMS\Form\Domain\Model\Renderable\RenderableInterface $renderable)
    {
        if ($renderable->getType() === 'Interests') {
            /** @var Interests $renderable */
            
            $finishers = $renderable->getRootForm()->getFinishers();

            /** @var AbstractFinisher $finisher */
            foreach ($finishers as &$finisher) {
                if ($finisher->getFinisherIdentifier() === 'Mailchimp') {
                    /** @var MailchimpFinisher $finisher */

                    $categories = $finisher->getCategories();

                    $renderable->setProperty('options', $categories['options']);
                    $renderable->setProperty('title', $categories['title']);
                    $renderable->setProperty('type', $categories['type']);

                    $useGroupName = $renderable->useGroupNameAsLabel();
                    if ($useGroupName) {
                        $renderable->setLabel($categories['title']);
                    }
                }
            }
        
        }
    }
}