<template>
	<v-app v-if="planDetails">
		<div fluid>
			<v-container class="container-box">
				<v-card class="card-section">
					<div class="plan-title-header" style="cursor:pointer" @click="closeDialog">
						<v-icon aria-hidden="false" color="white">
							mdi-close
						</v-icon>
					</div>
					<ElectricityPlan v-if="willShowElectricity" :plan="planDetails.plans.electricity"></ElectricityPlan>

					<GasPlan v-if="willShowGas" :plan="planDetails.plans.gas"></GasPlan>

					<div class="pl-8 pr-8">
						<p class="font-weight-bold" style="font-size:14px">Included in your plan</p>
						<div class="d-flex">
							<p class="font-weight-bold mb-0" style="font-size:14px;">Rates:</p>
							<div class="d-flex">
								<p class="pl-2 mb-0" style="font-size:14px;">{{ planDetails.rates }}</p>
								<v-icon aria-hidden="false" class="pl-1 mb-0" size="80%">
									mdi-progress-question
								</v-icon>
							</div>
						</div>
						<div class="d-flex">
							<p class="font-weight-bold mb-0" style="font-size:14px;">Exit Fees:</p>
							<p class="pl-2 mb-0" style="font-size:14px;">{{ planDetails.exit_fees }}</p>
						</div>
						<div class="d-flex">
							<p class="font-weight-bold mb-0" style="font-size:14px;">Benefit Period:</p>
							<p class="pl-2 pb-2 mb-0" style="font-size:14px;">{{ planDetails.benefit_period }}</p>
						</div>
						<!-- <p class="font-weight-bold mb-0" style="font-size:14px;">Green options</p>
						<p class="plan-text mt-0" style="font-size:14px;">{{ planDetails.green_options }}</p> -->
						<hr class="mb-4" style="width:95%" />

						<p class="plan-text mt-0" style="font-size:14px;">Your meter details will be verified with the distributor, and your charges may charge if any details are incorrect.
							Origin will confirm these once they’ve identified your meter type and processed your application and will notify
							you by letter about any such change.
						</p>

						<!-- <div v-if="willShowElectricity">
							<span class="font-weight-bold mb-0" style="font-size:14px;">Electricity</span>
							<div v-for="item in planDetails.plans.electricity.bpid_links" :key="item.title" class="pb-2" style="font-size:14px;">
								<a :href="item.file_url" target="_blank">{{ planDetails.plans.electricity.distributor_name }} - {{ item.offer_name }}</a>
							</div>
						</div>

						<div v-if="willShowGas">
							<span class="font-weight-bold mb-0" style="font-size:14px;">Gas</span>
							<div v-for="item in planDetails.plans.gas.bpid_links" :key="item.title" class="pb-2" style="font-size:14px;">
								<a :href="item.file_url" target="_blank">{{ planDetails.plans.gas.distributor_name }} - {{ item.offer_name }}</a>
							</div>
						</div> -->

						<div v-if="willShowElectricity">
							<span class="font-weight-bold mb-0" style="font-size:14px;">Electricity</span>
							<div v-if="this.leadSummary.state == 'Victoria'" class="pb-2" style="font-size:14px;">
								<a href="https://www.originenergy.com.au/vefs/" target="_blank">https://www.originenergy.com.au/vefs/</a>
							</div>
							<div v-else class="pb-2" style="font-size:14px;">
								<a href="https://www.originenergy.com.au/bpidlink/" target="_blank">https://www.originenergy.com.au/bpidlink/</a>
							</div>
						</div>

						<div v-if="willShowGas">
							<span class="font-weight-bold mb-0" style="font-size:14px;">Gas</span>
							<div v-if="this.leadSummary.state == 'Victoria'" class="pb-2" style="font-size:14px;">
								<a href="https://www.originenergy.com.au/vefs/" target="_blank">https://www.originenergy.com.au/vefs/</a>
							</div>
							<div v-else class="pb-2" style="font-size:14px;">
								<a href="https://www.originenergy.com.au/bpidlink/" target="_blank">https://www.originenergy.com.au/bpidlink/</a>
							</div>
						</div>

						<div class="pt-8 pb-2" style="font-size:14px;">
                            <a href="https://google.com" target="_blank">Terms and conditions</a>
						</div>
					</div>

					<v-btn class="selectButton" color="#cd5b32" @click="closeDialog">Select Plan</v-btn>
				</v-card>
			</v-container>
		</div>
	</v-app>
</template>

<script>
import ElectricityPlan from "@scripts/components/origin/ElectricityPlan"
import GasPlan from "@scripts/components/origin/GasPlan"
import OriginService from "@scripts/modules/origin/services/OriginService"
import OriginMapper from "../../../modules/origin/api/mappers/OriginMapper";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";

export default {
	name: "OriginPlanDetails",
	components: {
		ElectricityPlan,
		GasPlan,
	},
    props: {
        serviceType: {
            require: true
        },
        selectedPlan: {
            require: false
        },
        leadSummary: {
            require: true
        },
        planDetails: {
            require: true
        },
    },
	data() {
		return {}
	},
	computed: {
		willShowElectricity() {
			return this.planDetails?.plans?.electricity ;
		},
		willShowGas() {
			return this.planDetails?.plans?.gas ;
		},
	},
	watch: {},
	mounted() {},
	methods: {
        closeDialog(){
            this.$emit('toggleDialog')
        }
	},
}
</script>

<style scoped>
.container-box {
    padding: 0px !important;
}
.card-section {
	max-width: 450px;
	margin: 0px auto;
	border-radius: 2%;
	padding-bottom: 15px;
}

.plan-text {
	color: #505050;
	display: block;
	font-size: 14px;
	font-family: sans-serif;
	line-height: 24px;
	text-rendering: optimizeLegibility;
	text-transform: none;
	-webkit-font-smoothing: antialiased;
}
.selectButton {
	color: white;
	font-weight: bold;
	font-size: 16px;
	width: 80%;
	padding-bottom: 4px;
	padding-top: 10px;
	border-radius: 8px;
}
.v-size--default {
	height: 50px !important;
	min-width: 64px !important;
	padding: 0 16px !important;
	margin: 10px auto !important;
	display: block !important;
}
.plan-title-header {
	background-color: #cd5b32;
	color: white;
	padding-left: 389px;
	padding-top: 14px;
}

</style>
