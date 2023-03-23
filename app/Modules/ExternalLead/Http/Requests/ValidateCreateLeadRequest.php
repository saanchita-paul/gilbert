<?php

namespace ExternalLead\Http\Requests;

use App\Models\ConnectionApplication;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\ExternalSource;
use Illuminate\Validation\Rule;
use App\Services\Utility\StateMapService;
use ExternalLead\Services\SaveRawData;
use App\Models\ConnectionApplicationSecondaryACC as AuthorizedPerson;
use ExternalLead\Models\TApp;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class ValidateCreateLeadRequest extends FormRequest
{
    private const TOLOWERCASE = [
        'primary_account.title',
        'primary_account.phone_type',
        'primary_account.identification.type',
        'primary_account.identification.medicare_card_color',
        'secondary_account.title',
        'secondary_account.permission_type',
        'secondary_account.identification.type',
        'secondary_account.identification.medicare_card_color',
        'connection_details.tenancy_type',
        'connection_details.property_type',
        'utility_services'
    ];

    private const TOUPPERCASE = [
        'primary_account.identification.state',
        'secondary_account.identification.state',
        'property_address.state',
        'property_address.country',
        'billing_address.state'
    ];

    private TApp $dump;


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return $this->getRules();
    }

    public function messages()
    {
        $dobDateFormatMessage = 'The :attribute does not match the format yyyy-mm-dd.';
        return [
            'primary_account.dob.date_format' => $dobDateFormatMessage,
            'primary_account.identification.expire_date.date_format' => $dobDateFormatMessage,
            'connection_details.moving_date.date_format' => $dobDateFormatMessage,
            'secondary_account.dob.date_format' => $dobDateFormatMessage,
            'secondary_account.identification.expire_date.date_format' => $dobDateFormatMessage,
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'primary_account.dob' => 'Date of Birth',
            'primary_account.identification.expire_date' => 'identification expire date',
            'connection_details.moving_date' => 'moving date',
            'secondary_account.dob' => 'secondary account DoB',
            'secondary_account.identification.expire_date' => 'secondary account identification expire date',
        ];
    }

    private function getRules(): array
    {
        return [
            'lead_reference' => [
                'required',
                'string',
                Rule::unique('t_app', 'lead_id')
                    ->where(static function ($query) {
                        return $query->whereNotNull('connection_application_id');
                    }),
            ],
            'primary_account.title' => ['required', Rule::in(['mr', 'ms', 'mrs', 'miss', 'dr'])],
            'primary_account.first_name' => 'required|string',
            'primary_account.middle_name' => 'nullable|string',
            'primary_account.last_name' => 'required|string',
            'primary_account.email' => 'required|email',
            'primary_account.dob' => 'required|date_format:Y-m-d',
            'primary_account.phone_type' => ['required', Rule::in(['mobile', 'homephone', 'international_mobile'])],
            'primary_account.phone_number' => 'required|numeric',

            'utility_services' => 'array',
            'utility_services.*' =>  Rule::in("gas", 'power', 'water', 'internet'),

            'primary_account.identification.type' => ['required', Rule::in(['medicare', 'passport', 'driver_license'])],
            'primary_account.identification.number' => 'required|ascii',
            'primary_account.identification.state' => ['required_if:primary_account.identification.type,driver_license', Rule::in(array_keys(StateMapService::SHORT_TO_FULL))],
            'primary_account.identification.country' => 'required_if:primary_account.identification.type,passport|string',
            'primary_account.identification.medicare_card_color' => ['required_if:primary_account.identification.type,medicare', Rule::in(['yellow', 'green', 'blue'])],
            'primary_account.identification.medicare_reference_number' => 'required_if:primary_account.identification.type,medicare|ascii',
            'primary_account.identification.expire_date' => 'required|date_format:Y-m-d',

            'secondary_account.title' => ['required_with:secondary_account', Rule::in(['mr', 'ms', 'mrs', 'miss', 'dr'])],
            'secondary_account.first_name' => 'required_with:secondary_account|string',
            'secondary_account.last_name' => 'required_with:secondary_account|string',
            'secondary_account.email' => 'required_with:secondary_account|string',
            'secondary_account.dob' => 'required_with:secondary_account|date_format:Y-m-d',
            'secondary_account.phone_number' => 'required_with:secondary_account|numeric',
            'secondary_account.permission_type' => ['required_with:secondary_account', Rule::in(array_keys(AuthorizedPerson::ROLE_TYPE_MAPPER))],

            'secondary_account.identification.type' => ['required_with:secondary_account.identification', Rule::in(['medicare', 'passport', 'driver_license'])],
            'secondary_account.identification.number' => 'required_with:secondary_account.identification|ascii',
            'secondary_account.identification.state' => ['required_if:secondary_account.identification.type,driver_license', Rule::in(array_keys(StateMapService::SHORT_TO_FULL))],
            'secondary_account.identification.country' => 'required_if:secondary_account.identification.type,passport|string',
            'secondary_account.identification.medicare_card_color' => ['required_if:secondary_account.identification.type,medicare', Rule::in(['yellow', 'green', 'blue'])],
            'secondary_account.identification.medicare_reference_number' => 'required_if:secondary_account.identification.type,medicare|ascii',
            'secondary_account.identification.expire_date' => 'required_with:secondary_account.identification|date_format:Y-m-d',

            'connection_details.tenancy_type' => ['required', Rule::in(['renter', 'home_owner'])],
            'connection_details.property_type' => Rule::in(array_keys(ConnectionApplication::PROPERTY_TYPE_MAPPING)),
            'connection_details.moving_date' => 'required|date_format:Y-m-d',
            "connection_details.has_power_life_support" => "nullable|boolean",
            "connection_details.has_gas_life_support" => "nullable|boolean",
            "connection_details.is_renovation_on" => "nullable|boolean",
            'connection_details.is_email_billing' => 'nullable|boolean',
            "connection_details.has_solar" => "nullable|boolean",
            "connection_details.nmi" => "nullable|string",
            "connection_details.mirn" => "nullable|string",

            'property_address.unit_number' => 'nullable|alpha_dash',
            'property_address.street_number' => 'required|alpha_dash',
            'property_address.street_name' => 'required|string',
            'property_address.street_type' => 'required|string',
            'property_address.city' => 'required_without:property_address.suburb|string',
            'property_address.suburb' => 'required_without:property_address.city|string',
            'property_address.postcode' => 'required|alpha_dash',
            'property_address.state' => ['required', Rule::in(array_keys(StateMapService::SHORT_TO_FULL))],
            'property_address.country' => ['required', Rule::in(['AUS'])],

            'billing_address.unit_number' => 'nullable|alpha_dash',
            'billing_address.street_number' => 'required_with:billing_address|alpha_dash',
            'billing_address.street_name' => 'required_with:billing_address|string',
            'billing_address.street_type' => 'required_with:billing_address|string',
            'billing_address.city' => [Rule::requiredIf(function () {
                return request()->exists('billing_address') && !request()->exists('billing_address.suburb');
            }), 'string'],
            'billing_address.suburb' => [Rule::requiredIf(function () {
                return request()->exists('billing_address') && !request()->exists('billing_address.city');
            }), 'string'],
            'billing_address.postcode' => 'required_with:billing_address|alpha_dash',
            'billing_address.state' => ['required_with:billing_address', Rule::in(array_keys(StateMapService::SHORT_TO_FULL))],
            // billing address country?

            'agency.agent_email' => 'required|email',
            'agency.agency_name' => 'required|string',
            'agency.office_name' => 'required|string',
        ];
    }

    protected function prepareForValidation()
    {
        $requestData = $this->all();
        info('External Lead: Raw Request Data', $requestData);
        $this->changeCase($requestData, 'strtolower', self::TOLOWERCASE);
        $this->changeCase($requestData, 'strtoupper', self::TOUPPERCASE);
        $username = $requestData['username'];
        $selectedSource = ExternalSource::where('email', $username)->firstOrFail();
        $this->dump = SaveRawData::dump($selectedSource->id, $requestData);
        $requestData['dump_id'] = $this->dump->id;
        $this->replace($requestData);
    }

    protected function failedValidation(Validator $validator)
    {
        $this->dump->exception_log = $validator->errors()->toJson();
        $this->dump->save();
        $response = response()->json([
            'status' => 'fail',
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
            'transaction_id' => $this->dump->id
         ], 422);
        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    private function changeCase(&$requestData, string $type, array $fields)
    {
        foreach ($fields as $field) {
            $keys = explode('.', $field);
            $ref = &$requestData;
            while ($key = array_shift($keys)) {
                $ref = &$ref[$key];
            }
            if (!empty($ref)) {
                $ref = is_array($ref) ? array_map($type, $ref) : $type((string)$ref);
            }
        }
    }
}
