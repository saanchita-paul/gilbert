<template>
    <v-container v-if="application">
        <v-row no-gutters>
            <v-col cols="6">
                <CopyToClipboard title="Name" :data="applicant_name"/>
                <IdCopyToClipboard class="mb-3" :applicationId="application.id"/>
            </v-col>
            <v-col cols="6" style="text-align:right">
                <v-icon small color="red">mdi-phone-off-outline</v-icon>
                Waiting for consent...
            </v-col>
        </v-row>
        <v-row no-gutters>
            <v-col cols="4">
                <h4 class="header">Personal Details</h4>
                <div>
                    <div class="item" v-if="application.phone_type === 2">
                        <p class="item-title">Homephone</p>
                        <p class="item-value">{{ application.homephone }}</p>
                    </div>
                    <div class="item" v-else>
                        <p class="item-title">Mobile</p>
                        <p class="item-value">{{ application.phone }}</p>
                    </div>
                </div>
                <div class="item">
                    <p class="item-title">Date of Birth</p>
                    <p class="item-value">{{ date_of_birth }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Email</p>
                    <p class="item-value">{{ application.email }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Moving Date</p>
                    <p class="item-value">{{ moving_date }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Billing</p>
                    <p class="item-value">{{ application.is_email_billing === 1 ? 'Email' : 'Paper' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Identification</p>
                    <p class="item-value">
                        {{identification_type}}<br>
                        {{identification_number}}<br>
                        {{identification_expire_date}}
                    </p>
                </div>
                <div class="item">
                    <p class="item-title">Consent Sent</p>
                    <p class="item-value">02/22/2022 HH:MM</p>
                </div>
                <div class="item">
                    <p class="item-title">Consent Received</p>
                    <p class="item-value">DD/MM/YYYY HH:MM (Email)</p>
                </div>
            </v-col>
            <v-col cols="4" class="hr-bar">
                <h4 class="header">Property Details</h4>
                <div class="item">
                    <p class="item-title">Occupancy Type</p>
                    <p class="item-value">{{ application.tenancy_type === 1? 'Renter': 'Home Owner' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Service Address:</p>
                    <p class="item-value">
                        {{ application.address_text }}
                    </p>
                </div>
                <div class="item">
                    <p class="item-title">Billing Address</p>
                    <p class="item-value">
                        {{ application.billing_address_text }}
                    </p>
                </div>
            </v-col>
            <v-col cols="4" class="hr-bar">
                <h4 class="header">Agent's Additional Instructions</h4>
                <div class="item">
                    <p class="item-title">Agency Office</p>
                    <p class="item-value">{{ application.agency_office }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Additional Instructions</p>
                    <p class="item-value">
                        {{ application.additional_instruction}}
                    </p>
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import dayjs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import IDENTIFICATION from "@scripts/data/constants/IDENTIFICATION";
import IdCopyToClipboard from '@scripts/components/common/IdCopyToClipboard.vue';
import CopyToClipboard from '@scripts/components/common/CopyToClipboard.vue';

export default {
    name: "AgentApplicationDetails",
    props: ["application"],
    components: {
      IdCopyToClipboard,
      CopyToClipboard
    },
    data() {
        return {};
    },
    computed: {
        applicant_name() {
            let title = this.application.title ? this.application.title + ' ' : '';
            let first_name = this.application.first_name ? this.application.first_name + ' ' : '';
            let middle_name = this.application.middle_name ? this.application.middle_name + ' ' : '';
            let last_name =  this.application.last_name ? this.application.last_name : '';
            return title + first_name + middle_name + last_name;
        },
        date_of_birth() {
          return this.application.date_of_birth
            ? dayjs(this.application.date_of_birth,'YYYY-MM-DD').format(DATE_FORMAT.DB_MONTH_FIRST)
            : null;
        },
        moving_date() {
          return this.application.moving_date
            ? dayjs(this.application.moving_date,'DD/MM/YYYY').format(DATE_FORMAT.DB_MONTH_FIRST)
            : null;
        },
        identification_type() {
            switch (this.application?.identification?.type) {
              case IDENTIFICATION.PASSPORT:
                return 'Passport';
              case IDENTIFICATION.DL:
                return 'DL';
              case IDENTIFICATION.MEDICARE:
                return 'Medicare';
              default:
                return '';
            }
        },
        identification_number() {
            return this.application?.identification?.card_number;
        },
        identification_expire_date() {
            return this.application?.identification?.expire_date
              ? 'Expires on ' + dayjs(this.application?.identification?.expire_date, 'YYYY-MM-DD').format('MM/YY')
              : null;
        },
    }
};
</script>

<style scoped>
.lead-name {
    font-size: 1.3em;
}
.header {
    font-size: 1.2em;
    margin-bottom: 8px;
}
.hr-bar {
    border-left: 1px solid #ccc;
    padding-left: 10px;
}
.item {
    display: flex;
}
.item-title {
    width: 40%;
    font-weight: 600;
    margin-bottom: 5px !important;
}
.item-value {
    width: 60%;
    margin-bottom: 5px !important;
}
</style>
