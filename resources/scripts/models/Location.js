export default class Location {
    constructor(
        {
            id,
            postcode,
            city,
            address,
            latitude,
            longitude,
            formatted_address,
            webview_address,
            suburb,
            state,
            neighborhood,
            country_code
        } = {}
    ) {
        this.id = id || null;
        this.postcode = postcode || null;
        this.city = city || null;
        this.address = address || null;
        this.latitude = latitude || null;
        this.longitude = longitude || null;
        this.formatted_address = formatted_address || null;
        this.webview_address = webview_address || null
        this.suburb = suburb || null;
        this.state = state || null;
        this.neighborhood = neighborhood || null;
        this.country_code = country_code || null;
    }
}
