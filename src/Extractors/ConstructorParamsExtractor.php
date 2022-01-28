<?php
declare(strict_types=1);

namespace BrenoRoosevelt\PhpAttributes\Extractors;

use BrenoRoosevelt\PhpAttributes\ParsedAttribtubeCollection;
use BrenoRoosevelt\PhpAttributes\Exception\ClassDoesNotExists;
use BrenoRoosevelt\PhpAttributes\Extractor;
use ReflectionParameter;

class ConstructorParamsExtractor implements Extractor
{
    use ReflectionTrait;

    /** @var string[] */
    private readonly array $params;

    public function __construct(private readonly string|object $classOrObject, string ...$params)
    {
        $this->params = $params;
    }

    /**
     * @inheritDoc
     * @throws ClassDoesNotExists if the class does not exist
     */
    public function extract(string $attribute = null, int $flag = 0): ParsedAttribtubeCollection
    {
        $reflectionClass = $this->getClassOrFail($this->classOrObject);
        $reflectionConstructorParams =
            empty($this->params) ?
                $reflectionClass->getConstructor()?->getParameters() ?? [] :
                array_filter(
                    $reflectionClass->getConstructor()?->getParameters() ?? [],
                    fn(ReflectionParameter $rp) => $this->filterByName($rp, $this->params)
                );

        return (new ReflectionExtractor(...$reflectionConstructorParams))->extract($attribute, $flag);
    }
}
