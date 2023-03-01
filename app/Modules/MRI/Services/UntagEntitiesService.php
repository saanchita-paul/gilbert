<?php

namespace MRI\Services;

use GuzzleHttp\Client;

class UntagEntitiesService
{
    /**
     * @var string|null
     */
    private ?string $url;

    /**
     * @var string|null
     */
    private ?string $officeKey;

    /**
     * @var string|null
     */
    private ?string $tagGroupName;

    /**
     * @var string|null
     */
    private ?string $tagName;

    /**
     * @var string|null
     */
    private ?string $tagId;

    /**
     * @var array
     */
    private array $entityIds;

    public function __construct($officeKey, $tagGroupName, $tagName, $entityIds = [])
    {
        $this->officeKey = $officeKey;
        $this->tagGroupName = $tagGroupName;
        $this->tagName = $tagName;
        $this->entityIds = $entityIds;
    }

    public function run()
    {
        $allTags = $this->runGetTagAPI();
        $this->tagId = $this->getTagId($allTags);
        return $this->runDeleteAPI();
    }

    private function getTagId($tagGroups)
    {
        $tg = [];
        $tagId = '';
        foreach ($tagGroups as $tagGroup) {
            if ($tagGroup['group_name'] === $this->tagGroupName) {
                $tg = $tagGroup;
                break;
            }
        }

        if (empty($tg)) {
            $message = 'Unable to find tag group name "' . $this->tagGroupName . '" from all tags';
            \Log::error($message, [
                'all_tags' => $tagGroups
            ]);
            throw new \Exception($message);
        }

        foreach ($tg['tags'] as $tag) {
            if ($tag['tag_name'] === $this->tagName) {
                $tagId = $tag['tag_id'];
                break;
            }
        }

        if (empty($tagId)) {
            $message = 'Unable to get tag ID for tag name "' . $this->tagName . '" from tag group';
            \Log::error($message, [
                'tag_group' => $tg,
            ]);
            throw new \Exception($message);
        }

        return $tagId;
    }

    private function setDeleteURL()
    {
        $url = empty(config('mri.base_url')) ? 'https://uatapi.propertytree.io' : config('mri.base_url');
        $endpoint = empty(config('mri.endpoints.update_tenancies_tag')) ? '/residentialproperty/v1/tags/{tag_id}/entities' : config('mri.endpoints.update_tenancies_tag');
        $endpoint = str_replace('{tag_id}', $this->tagId, $endpoint);
        $this->url = $url . $endpoint;
        return $this;
    }

    private function setGetAllTagURL()
    {
        $url = empty(config('mri.base_url')) ? 'https://uatapi.propertytree.io' : config('mri.base_url');
        $endpoint = empty(config('mri.endpoints.get_all_tags')) ? '/residentialproperty/v1/taggroups' : config('mri.endpoints.get_all_tags');
        $this->url = $url . $endpoint;
        return $this;
    }

    private function runGetTagAPI()
    {
        $this->setGetAllTagURL();
        $client = new Client([
            'headers' => [
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->officeKey
            ],
        ]);

        $response = $client->request('GET', $this->url);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data;
    }

    private function runDeleteAPI()
    {
        $this->setDeleteURL();
        $client = new Client([
            'headers' => [
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'authorization' => 'Bearer ' . $this->officeKey
            ],
        ]);

        $body = [
            'entity_ids' => $this->entityIds
        ];

        $options = [
            'json' => $body
        ];

        $response = $client->request('DELETE', $this->url, $options);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data;
    }
}
