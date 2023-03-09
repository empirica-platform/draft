<?php

namespace EmpiricaPlatform\Terminal;

class Feature
{
    public function __construct(
        protected array $extractors
    )
    {
    }

    public function extraxt()
    {
        /** @var FeatureExtractor $extractor */
        foreach ($this->extractors as $extractor) {
            $extractor->extract();
        }
    }

}