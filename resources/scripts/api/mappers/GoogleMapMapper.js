export default {
    mapGeocoderResultToAddress(data) {
        const { formatted_address = '' } = data || {};
        const addressValue = getAddressComponent(data.address_components);

        let street = '';
        if (addressValue.street_number) {
            street = street.length > 0 ? `${street}, ${addressValue.street_number}`
                : `${addressValue.street_number}`;
        }
        if (addressValue.route) {
            street = street.length > 0 ? `${street}, ${addressValue.route}`
                : `${addressValue.route}`;
        }

        let city = '';
        let state= null;
        if (addressValue.locality) {
            city = city.length > 0 ? `${city}, ${addressValue.locality}`
                : `${addressValue.locality}`;
        } else if (addressValue.sublocality) {
            city = city.length > 0 ? `${city}, ${addressValue.sublocality}`
                : `${addressValue.sublocality}`;
        }
        if (addressValue.administrative_area_level_1) {
            state = addressValue.administrative_area_level_1;
        }

        return {
            street_name: addressValue.route,
            street_number: addressValue.street_number,
            street,
            city,
            state,
            postcode: addressValue.postal_code,
            country: addressValue.country,
            longitude: data.geometry.location.lng(),
            latitude: data.geometry.location.lat(),
            googleId: data.place_id,
            formatted_address,
            unit_number: addressValue.subpremise ? addressValue.subpremise : null
        };
    }
};

function getAddressComponent(address_components) {
    const value = {};
    address_components.forEach((addressComponent) => {
        addressComponent.types.forEach((addressType) => {
            value[addressType] = addressComponent.long_name;
        });
    });
    return value;
}
