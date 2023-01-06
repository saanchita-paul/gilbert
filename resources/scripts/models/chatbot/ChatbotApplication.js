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


                }) {

        this.id_detail = id_detail;
        this.personal_details = personal_details;
        this.property_details = property_details;
        this.application_notes = application_notes?.reverse();
        this.connection_services = connection_services?.reverse();
        this.eleService = eleService;
        this.gasService = gasService;
        this.concession_details = concession_details;
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
