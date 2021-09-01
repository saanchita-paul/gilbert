/* eslint-disable no-use-before-define */
import isString from 'lodash-es/isString';
import GoogleMapMapper from "@scripts/api/mappers/GoogleMapMapper";

export default {
    initialize() {
        if (window.google === undefined || window.google.maps === undefined) {
            setTimeout(() => {
                this.initialize();
            }, 100);
            return;
        }
        this.autoCompleteService = new window.google.maps.places.AutocompleteService();
        this.geocoder = new window.google.maps.Geocoder();
    },

    /**
     *
     * @param keyWord
     * @param country
     * @returns {Object[] || Promise<unknown>}
     */
    getStreetAddressesByKeyword(keyWord, country = '') {
        // keyWord = country === '*' ? keyWord : `${keyWord} ${country}`;
        return new Promise((resolve, reject) => {
            try {
                this.autoCompleteService.getPlacePredictions({
                    input: keyWord,
                    types: ['address'],
                    //https://stackoverflow.com/questions/47892127/succinct-concise-syntax-for-optional-object-keys-in-es6-es7
                    ...(isString(country) && country.length > 0 && { componentRestrictions: { country } })
                }, (data, status) => {
                    if (status === 'OK') {
                        resolve(data);
                    } else {
                        resolve([])
                    }
                });
            } catch (error) {
                reject(error);
            }
        });
    },
    getAddressByKeyword(keyWord, country = 'au') {
        return new Promise((resolve, reject) => {
            try {
                this.autoCompleteService.getPlacePredictions({
                    input: keyWord,
                    componentRestrictions: {country: country}
                }, (data, status) => {
                    if (status === 'OK') {
                        resolve(data);
                    } else {
                        resolve([])
                    }
                });
            } catch (error) {
                reject(error);
            }
        });
    },

    /**
     *
     * @param placeId
     * @returns {Promise<Object>}
     */
    getAddressDetailsByPlaceId(placeId) {
        const that = this;
        return new Promise((resolve, reject) => {
            try {
                that.geocoder.geocode({
                    placeId,
                }, (data, status) => {
                    if (status === 'OK') {
                        console.log("ADDRESS", data)
                        resolve(GoogleMapMapper.mapGeocoderResultToAddress(data[0]));
                    } else {
                        reject(status);
                    }
                });
            } catch (error) {
                reject(error);
            }
        });
    },
};
