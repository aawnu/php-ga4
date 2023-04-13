<?php

namespace AlexWestergaard\PhpGa4;

use AlexWestergaard\PhpGa4\Facade\Type\CampaignType;

/**
 * EXPERIMENTAL MODEL
 * @version 1.1.3-beta
 */
class Campaign implements CampaignType
{
    public function __construct(
        protected null|string $id = null,
        protected null|string $source = null,
        protected null|string $medium = null,
        protected null|string $name = null,
        protected null|string $term = null,
        protected null|string $content = null
    ) {
    }

    public function setId(null|string $id)
    {
        $this->id = trim($id);
        return $this;
    }

    public function setSource(null|string $source)
    {
        $this->source = trim($source);
        return $this;
    }

    public function setMedium(null|string $medium)
    {
        $this->medium = trim($medium);
        return $this;
    }

    public function setName(null|string $name)
    {
        $this->name = trim($name);
        return $this;
    }

    public function setTerm(null|string $term)
    {
        $this->term = urlencode(trim($term));
        return $this;
    }

    public function setContent(null|string $content)
    {
        $this->content = trim($content);
        return $this;
    }

    public function toArray(): array
    {
        return array_filter(
            [
                'campaign_id' => $this->id,
                'campaign_source' => $this->source,
                'campaign_medium' => $this->medium,
                'campaign_name' => $this->name,
                'campaign_term' => $this->term,
                'campaign_content' => $this->content,
            ],
            fn ($val) => !empty($val) && strval($val) !== '0'
        );
    }

    public static function new($id = null, $source = null, $medium = null, $name = null, $term = null, $content = null)
    {
        return new static($id, $source, $medium, $name, $term, $content);
    }
}
