<template>
    <v-menu offset-y v-model="showMenu">
        <template v-slot:activator="{ on }">
            <v-text-field
                outlined
                class="custom-field sm-text"
                :error-messages="error"
                hide-details
                placeholder="Location" rounded
                v-model="textFieldValue"
                @keyup.native="onLocationTextFieldChange"
            >
            </v-text-field>
        </template>
        <v-list class="mt-2">
            <v-list-item
                v-for="place in searchResult"
                :key="place.place_id"
                @click="onAddressSelected(place)"
            >
                <v-list-item-title v-text="place.description">
                </v-list-item-title>
            </v-list-item>
        </v-list>
    </v-menu>
</template>

<script>
    import debounce from 'lodash-es/debounce';
    import GoogleMapService from "@scripts/services/GoogleMapService";

    export default {
        name: "SearchAddress",
        data() {
            return {
                showFull: false,
                showMenu: false,
                searchResult: [],
                error: '',
                textFieldValue: null,
            }
        },
        created() {
            this.onLocationTextFieldChange = debounce((e) => {
                this.showMenu = true;
                this.onAddressValueChanged(e.target.value)
            }, 250);
        },
        methods: {
            async onAddressSelected(place) {
                this.error = "";
                let address = await GoogleMapService.getAddressDetailsByPlaceId(place.place_id);
                if(!address.postcode) {
                    this.error = "Please provide full address";
                }
                this.updateAddress(address);
            },
            onAddressValueChanged(value) {
                if (value.length > 0) {
                    try {
                        GoogleMapService.getAddressByKeyword(value)
                            .then((data) => {
                                this.searchResult = data;
                                this.showMenu = this.searchResult.length > 0;
                                if( this.searchResult.length === 0) {
                                    this.error = "Address not found!";
                                }
                            });
                    } catch (e) {
                        this.error = "Address not found!";
                    }
                }
            },
            isOnlyNumber(value) {
                return !!value.match('^\\d+$')
            },
            /**
             * updateAddress
             * @param {Address} address
             */
            updateAddress(address) {
                this.textFieldValue = address.formatted_address;
                this.showMenu = false;
                if (!this.error) {
                    this.saveAddress(address)
                }
            },
            saveAddress(address) {
                console.log('Save address with ', address);
                this.$emit('saveAddress', address);
            }
        }
    }
</script>

<style scoped>
.custom-field {
    border: 1px solid #5C229A;
}
</style>
