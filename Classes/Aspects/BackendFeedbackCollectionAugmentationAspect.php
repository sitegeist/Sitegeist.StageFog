<?php
namespace Sitegeist\StageFog\Aspects;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Neos\Neos\Ui\Domain\Model\Feedback\Messages\Success;
use Neos\Neos\Ui\Domain\Model\FeedbackCollection;
use Neos\Neos\Ui\Domain\Model\FeedbackInterface;
use Neos\Neos\Ui\Domain\Model\Feedback\Operations\ReloadDocument;
use Neos\Neos\Ui\Domain\Model\Feedback\Operations\ReloadContentOutOfBand;
use Neos\Neos\Ui\Domain\Model\Feedback\Operations\RenderContentOutOfBand;
use TYPO3Fluid\Fluid\Core\Parser\SyntaxTree\NodeInterface;

/**
 * @Flow\Scope("singleton")
 * @Flow\Aspect
 */
class BackendFeedbackCollectionAugmentationAspect
{
    /**
     * @Flow\Around("method(Neos\Neos\Ui\Domain\Model\FeedbackCollection->add())")
     * @param JoinPointInterface $joinPoint The current join point
     * @return mixed
     */
    public function replacePartialReloadsInCertainConditions(JoinPointInterface $joinPoint)
    {
        /**
         * @var FeedbackInterface $feedback
         */
        $feedback = $joinPoint->getMethodArgument('feedback');

        /**
         * @var FeedbackCollection $feedbackCollection
         */
        $feedbackCollection = $joinPoint->getProxy();

        if ($feedback instanceof ReloadContentOutOfBand || $feedback instanceof RenderContentOutOfBand) {
            $node = $feedback->getNode();

            // check wether the node or one of the parents up until the documents has the option reloadWithDocument set
            // if so replace the given feedback with ReloadDocument feedback
            while ($node && !$node->getNodeType()->isOfType('Neos.Neos:Document')) {
                if ($node->getNodeType()->getConfiguration('options.reloadPageIfChanged') == TRUE) {
                    $alternateFeedback = new ReloadDocument();
                    $joinPoint->setMethodArgument('feedback', $alternateFeedback);
                    break;
                }
                $node = $node->getParent();
            }
        }

        return $joinPoint->getAdviceChain()->proceed($joinPoint);
    }

}