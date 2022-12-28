export default class {
    constructor({
                    id_detail = null,
                    personal_details = null,
                    property_details = null,
                }) {

        this.id_detail = id_detail;
        this.personal_details = personal_details;
        this.property_details = property_details;
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
