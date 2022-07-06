<?php

namespace HoodLead\Services;

use App\Models\ConnectionApplication;
use App\Models\Office;
use HoodLead\HoodLead;
use App\Models\ConnectionService;
/**
 *
 */
class StoreHoodLead
{
    /**
     * @var HoodLead
     */
    private HoodLead $hoodLead;

    /**
     * @param array $requestData
     */
    public function __construct(private array $requestData)
    {
        $this->hoodLead = new HoodLead();
    }

    /**
     * @return int
     */
    public function store(): int
    {
        $leadId = $this->dump()->storeApplication();
        $this->hoodLead->connection_application_id = $leadId;
        $this->hoodLead->save();

        return $leadId;
    }

        /**
     * @return int
     */
    public function save(): int
    {
        $leadId = $this->dump()->saveApplication();
        $this->hoodLead->connection_application_id = $leadId;
        $this->hoodLead->save();

        return $leadId;
    }

    /**
     * @return int
     */
    private function storeApplication()
    {
        $office = Office::where('name', HoodLead::DEFAULT_OFFICE)->firstOrFail();

        $app = new ConnectionApplication([
            'office_id' => $office->id,
            'agency_id' => $office->agency_id,
            'moving_date' => isset($this->requestData['moving_date']) &&
                                $this->requestData['moving_date'] !== null ?
                                $this->requestData['moving_date'] : date('2025-05-05'),
            'source' => ConnectionApplication::SOURCE_HOOD_LEAD,
            'status' => ConnectionApplication::STATUS_UNASSIGNED,
            'first_name' => $this->requestData['first_name'] ?? null,
            'last_name' => $this->requestData['last_name'] ?? null,
            'phone' => $this->requestData['phone'] ?? null,
            'email' => $this->requestData['email'] ?? null,
            'postcode' => $this->requestData['postcode'] ?? null,
        ]);
        $app->save();

        // auto adding water service to connection application
        if ($app->id) {
            $connectionService = new ConnectionService();
            $connectionService->service_type = 'water';
            $connectionService->status = ConnectionService::STATUS_EA_PROCESSINF;
            $connectionService->connection_application_id = $app->id;
            $connectionService->save();
        }

        return $app->id;
    }

    /**
     * @return int
     */
    private function saveApplication()
    {
        $office = Office::where('name', HoodLead::DEFAULT_OFFICE)->firstOrFail();

        $hood_utm_source = null;
        $hood_utm_content = null;
        $hood_utm_medium = null;
        $hood_hss_channel = null;

        $form_details = $this->requestData['form-submissions'];
        $page_url_array = array_filter($form_details, function ($item) {
            return array_key_exists('page-url', $item);
        });
        if (count($page_url_array) > 0) {
            $page_url = array_values($page_url_array)[0]['page-url'];
            $hood_utm_source = $this->getUtmSource($page_url);
            $hood_utm_content = $this->getUtmContent($page_url);
            $hood_utm_medium = $this->getUtmMedium($page_url);
            $hood_hss_channel = $this->getHssChannel($page_url);
        }

        $app = new ConnectionApplication([
            'office_id' => $office->id,
            'agency_id' => $office->agency_id,
            'moving_date' => isset($this->requestData['moving_date']) &&
                                $this->requestData['moving_date'] !== null ?
                                $this->requestData['moving_date'] : date('2025-05-05'),
            'source' => ConnectionApplication::SOURCE_HOOD_LEAD,
            'status' => ConnectionApplication::STATUS_UNASSIGNED,
            'first_name' => $this->requestData['properties']['firstname']['value'] ?? null,
            'last_name' => $this->requestData['properties']['lastname']['value'] ?? null,
            'phone' => $this->requestData['properties']['phone']['value'] ?? null,
            'email' => $this->requestData['properties']['email']['value'] ?? null,
            'postcode' => $this->requestData['properties']['postcode']['value'] ?? null,
            'hood_utm_source' => $hood_utm_source,
            'hood_utm_content' => $hood_utm_content,
            'hood_utm_medium' => $hood_utm_medium,
            'hood_hss_channel' => $hood_hss_channel,
        ]);
        $app->save();

        // auto adding water service to connection application
        if ($app->id) {
            $connectionService = new ConnectionService();
            $connectionService->service_type = 'water';
            $connectionService->status = ConnectionService::STATUS_EA_PROCESSINF;
            $connectionService->connection_application_id = $app->id;
            $connectionService->save();
        }

        return $app->id;
    }

    /**
     * @param string $page_url
     * @return string|null
     */
    private function getUtmSource(string $page_url)
    {
        $utm_source = null;
        $query_string = parse_url($page_url, PHP_URL_QUERY);
        parse_str($query_string, $query_params);
        if (array_key_exists('utm_source', $query_params)) {
            $utm_source = $query_params['utm_source'];
        }
        return $utm_source;
    }

    /**
     * @param string $page_url
     * @return string|null
     */
    private function getUtmContent(string $page_url)
    {
        $utm_content = null;
        $query_string = parse_url($page_url, PHP_URL_QUERY);
        parse_str($query_string, $query_params);
        if (array_key_exists('utm_content', $query_params)) {
            $utm_content = $query_params['utm_content'];
        }
        return $utm_content;
    }

    /**
     * @param string $page_url
     * @return string|null
     */
    private function getUtmMedium(string $page_url)
    {
        $utm_medium = null;
        $query_string = parse_url($page_url, PHP_URL_QUERY);
        parse_str($query_string, $query_params);
        if (array_key_exists('utm_medium', $query_params)) {
            $utm_medium = $query_params['utm_medium'];
        }
        return $utm_medium;
    }

    /**
     * @param string $page_url
     * @return string|null
     */
    private function getHssChannel(string $page_url)
    {
        $hss_channel = null;
        $query_string = parse_url($page_url, PHP_URL_QUERY);
        parse_str($query_string, $query_params);
        if (array_key_exists('hss_channel', $query_params)) {
            $hss_channel = $query_params['hss_channel'];
        }
        return $hss_channel;
    }

    /**
     * @return $this
     */
    private function dump(): static
    {
        $this->hoodLead->all_fields_dump = json_encode($this->requestData);
        $this->hoodLead->save();

        return $this;
    }
}
