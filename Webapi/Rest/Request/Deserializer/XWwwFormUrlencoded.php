<?php

namespace Perspective\LiqPayPayment\Webapi\Rest\Request\Deserializer;

use InvalidArgumentException;
use Magento\Framework\App\State;
use Magento\Framework\Phrase;
use Magento\Framework\Webapi\Exception;
use Magento\Framework\Webapi\Rest\Request\DeserializerInterface;

/**
 * Class XWwwFormUrlencoded
 */
class XWwwFormUrlencoded implements DeserializerInterface
{
    /**
     * @var State
     */
    protected $appState;

    /**
     * @param State $appState
     */
    public function __construct(State $appState)
    {
        $this->appState = $appState;
    }

    /**
     * Parse Request body into array of params.
     *
     * @param string $encodedBody Posted content from request.
     * @return array|null Return NULL if content is invalid.
     * @throws InvalidArgumentException
     * @throws Exception If decoding error was encountered.
     */
    public function deserialize($encodedBody)
    {
        if (!is_string($encodedBody)) {
            throw new InvalidArgumentException(
                sprintf('"%s" data type is invalid. String is expected.', gettype($encodedBody))
            );
        }
        try {
            $decodedBody = [];
            \parse_str($encodedBody, $decodedBody);
        } catch (InvalidArgumentException $e) {
            if ($this->appState->getMode() !== State::MODE_DEVELOPER) {
                throw new Exception(new Phrase('Decoding error.'));
            } else {
                throw new Exception(
                    new Phrase(
                        'Decoding error: %1%2%3%4',
                        [PHP_EOL, $e->getMessage(), PHP_EOL, $e->getTraceAsString()]
                    )
                );
            }
        }
        return $decodedBody;
    }
}
