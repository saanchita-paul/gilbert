export default class {
    constructor({
                    id_detail = null,
                    personal_details = null,
                    property_details = null,
                    application_notes = null,
                    connection_services = null,
                    eleService = null,
                    gasService = null,
                    concession_details = null,
                    property_address= null,
                    cafStatus = false,
                    id = null,
                    status_log=null,
                }) {

        this.id_detail = id_detail;
        this.personal_details = personal_details;
        this.property_details = property_details;
        this.application_notes = application_notes;
        this.connection_services = connection_services?.reverse();
        this.eleService = eleService;
        this.gasService = gasService;
        this.concession_details = concession_details;
        this.property_address = property_address;
        this.cafStatus = cafStatus? 'CAF Submitted': '--';
        this.id = id;
        this.status_log = status_log;
        this.provider_name = this.getProviderName(this.connection_services);
    }

    getProviderName(services){
        let filteredService = services.filter(item => ['electricity', 'gas'].includes(item.service_type))
        return filteredService[0].provider_name;
    }

    setIdDetails(idDetail) {
        this.id_detail = idDetail;
    }

    setPersonalDetails(personalDetails) {
        this.personal_details = personalDetails;
    }

    setPropertyDetails(propertyDetails) {
        this.property_details = propertyDetails;
    }


}
