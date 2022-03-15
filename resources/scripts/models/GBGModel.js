import STATES_DD from "@scripts/data/constants/STATES_DD";

export default class GBGModel {
    constructor({
        id,
        fullAddress,
        country,
        flatUnitNumber,
        flatUnitType,
        floorLevelNumber,
        floorLevelType,
        locality,
        lotNumber,
        postal,
        postalNumber,
        postalType,
        postcode,
        state,
        street,
        street2,
        streetName, 
        streetNumber, 
        streetSuffix, 
        streetType, 
        subdwelling, 
        _type,
    } = {}) 
    {
        this.id = id;
        this.fullAddress = fullAddress;
        this.country = country;
        this.flatUnitNumber = flatUnitNumber;
        this.flatUnitType = flatUnitType;
        this.floorLevelNumber = floorLevelNumber;
        this.floorLevelType = floorLevelType;
        this.locality = locality;
        this.lotNumber = lotNumber;
        this.postal = postal;
        this.postalNumber = postalNumber;
        this.postalType = postalType;
        this.postcode = postcode;
        this.state = state;
        this.street = street;
        this.street2 = street2;
        this.streetName = streetName;
        this.streetNumber = streetNumber;
        this.streetSuffix = streetSuffix;
        this.streetType = streetType;
        this.subdwelling = subdwelling;
        this._type = _type;
    }

    getApplicationAddress(){
        return {
            id: this.id,
            address_text: this.fullAddress,
            street_address : this.street,
            city : this.locality,
            country : this.country,
            postcode : this.postcode,
            state : this.mapState(),
            street_number : this.streetNumber,
            unit_number : this.flatUnitNumber,
            street_name : this.streetName,
            street_name_only : this.streetName,
            street_type : this.streetType,
        }
    }


    mapState(){
        let state = STATES_DD.find(state => state.text === this.state)?.value;
        return state;
    }

}
