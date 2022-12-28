export default class {
    constructor({
                    idDetail = null,
                    personalDetails = null,
                    propertyDetails = null,
                }) {

        this.idDetail = idDetail;
        this.personalDetails = personalDetails;
        this.propertyDetails = propertyDetails;
    }

    setIdDetails(idDetail) {
        this.idDetail = idDetail;
    }

    setPersonalDetails(personalDetails) {
        this.personalDetails = personalDetails;
    }

    setPropertyDetails(propertyDetails) {
        this.propertyDetails = propertyDetails;
    }


}
