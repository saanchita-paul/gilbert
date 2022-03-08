/* eslint-disable no-use-before-define */
import isString from "lodash-es/isString";
import GBGMapMapper from "@scripts/api/mappers/GBGMapMapper";

export default {
    mountGBG() {
        let recaptchaScript = document.createElement("script");
        recaptchaScript.setAttribute(
            "src",
            "https://common.mastersoftgroup.com/scripts/harmony-2.0.1.min.js"
        );
        document.head.appendChild(recaptchaScript);
    },
    initialize() {
        Harmony.init(
            "hoodmovetech_test_user",
            "f3se7N14GrCxHWQDgAJTu7wluFw7jDW9",
            Harmony.AUSTRALIA
        );

        // Use the JSONP protocol
        // Harmony.useProtocol(Harmony.JSONP);

        // Harmony.v2.find({ fullAddress:"100 plenty road" , country: "au"}, null,
        //     function(response) {
        //         var outputText = "";
        //         console.log('getting response'  , response)
        //     }
        // );

        // Harmony.v2.retrieve({ id: "AU|AUPAF|46550005"},
        //     function(response) {
        //         console.log('getting response'  , response)
        //     }
        // );
    },

    /**
     *
     * @param keyWord
     * @param country
     * @returns {Object[] || Promise<unknown>}
     */
    getStreetAddressesByKeyword(keyWord, country = "au") {
        Harmony.useFeatureOptions({ singleLineHitNumber: "5" });
        // keyWord = country === '*' ? keyWord : `${keyWord} ${country}`;
        return new Promise((resolve, reject) => {
            try {
                Harmony.v2.find(
                    { fullAddress: keyWord, country },
                    null,
                    function (response) {
                        var outputText = "";
                        console.log("getting response", response);
                        let mapper = GBGMapMapper.mapAddressList(
                            response.payload
                        );
                        console.log("getting response", mapper);
                        resolve(mapper);
                    }
                );
            } catch (error) {
                reject(error);
            }
        });
    },
    getAddressByKeyword(keyWord, country = "au") {
        return new Promise((resolve, reject) => {
            try {
                this.autoCompleteService.getPlacePredictions(
                    {
                        input: keyWord,
                        componentRestrictions: { country: country },
                    },
                    (data, status) => {
                        if (status === "OK") {
                            resolve(data);
                        } else {
                            resolve([]);
                        }
                    }
                );
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
    getAddressDetailsById(id) {
        // keyWord = country === '*' ? keyWord : `${keyWord} ${country}`;
        console.log("id printing" , id)
        return new Promise((resolve, reject) => {
            try {
                Harmony.v2.retrieve({  id: id }, function (response) {
                    let address = GBGMapMapper.mapSingleAddress( response.payload[0] );
                    console.log(address)
                    resolve( address )
                });
            } catch (error) {
                reject(error);
            }
        });
    },
};
