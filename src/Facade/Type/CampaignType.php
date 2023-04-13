<?php

namespace AlexWestergaard\PhpGa4\Facade\Type;

interface CampaignType
{
    const CAMPAIGN_VARS = [
        'id',
        'source',
        'medium',
        'name',
        'term',
        'content',
    ];

    /**
     * Used to identify which campaign this referral references. Use campaign_id to identify a specific campaign.
     *
     * @var id
     * @param null|string $id eg "newsletter_category"
     */
    public function setId(null|string $id);

    /**
     * Use campaign_source to identify a search engine, newsletter name, or other source.
     *
     * @var source
     * @param null|string $id eg "newsletter"
     */
    public function setSource(null|string $source);

    /**
     * Use campaign_medium to identify a medium such as email or cost-per-click.
     *
     * @var medium
     * @param null|string $id eg "email" or "cpc"
     */
    public function setMedium(null|string $medium);

    /**
     * Used for keyword analysis. Use campaign_name to identify a specific product promotion or strategic campaign.
     *
     * @var name
     * @param null|string $id eg "Weekly Newsletter 2020w20"
     */
    public function setName(null|string $name);

    /**
     * Used for paid search. Use campaign_term to note the keywords for this ad.
     *
     * @var term
     * @param null|string $id eg "continue+reading+here"
     */
    public function setTerm(null|string $term);

    /**
     * Used for A/B testing and content-targeted ads. Use campaign_content to differentiate ads or links that point to the same URL.
     *
     * @var content
     * @param null|string $id eg "section1"
     */
    public function setContent(null|string $content);

    /**
     * Should return array of campaign object with variables
     *
     * @return array<string,array<string,null|string>>
     */
    public function toArray(): array;
}
