<template>
	<v-app v-if="planDetails">
		<div fluid>
			<v-container class="container-box">
				<v-card class="card-section">

					<ElectricityPlan v-if="willShowELectricity" :plan="planDetails.plans.electricity"></ElectricityPlan>

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
							<p class="pl-2 mb-0" style="font-size:14px;">{{ planDetails.benefit_period }}</p>
						</div>
						<p class="font-weight-bold mb-0" style="font-size:14px;">Green options</p>
						<p class="plan-text mt-0" style="font-size:14px;">{{ planDetails.green_options }}</p>
						<hr class="mb-4" style="width:95%" />

						<p class="plan-text mt-0" style="font-size:14px;">Your meter details will be verified with the distributor, and your charges may charge if any details are incorrect.
							Origin will confirm these once they’ve identified your meter type and processed your application and will notify
							you by letter about any such change.
						</p>

						<div v-if="willShowELectricity">
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
    },
	data() {
		return {
			planDetails: null,
		}
	},
	computed: {
        getServiceText() {
            switch(this.serviceType) {
                case "power":
                    return "Electricity"
                case "gas":
                    return "Gas"
                default:
                    return "Electricity & Gas"
            }
        },
        getPlanText() {
            return LeadApplicationService.mapPlan(this.selectedPlan);
        },
		// electricityPlan() {
        //     return this.planDetails.plans.find(plan => plan.title === "Electricity");
		// },
		// gasPlan() {
        //     return this.planDetails.plans.find(plan => plan.title === "Gas");
		// },
		isBothEnergySubmit() {
                return UtilityStoreService.getIsBothEnergySelected();
        },
		willShowELectricity() {
			return this.planDetails?.plans?.electricity && (this.serviceType == "power" || this.isBothEnergySubmit);
		},
		willShowGas() {
			return this.planDetails?.plans?.gas && (this.serviceType == "gas" || this.isBothEnergySubmit);
		},
		service_Type() {
			switch(this.serviceType) {
                case "power":
                    return "electricity"
                case "gas":
                    return "gas"
				default:
					return null
            }
		},
		state() {
			switch(this.leadSummary.state) {
				case "New South Wales":
					return 'nsw'
				case "Victoria": 
					return 'vic'
				case "Queensland": 
					return 'qld'
				case "South Australia": 
					return 'sa'
				case "Northern Territory": 
					return 'nt'
				case "Tasmania":
					return 'tas'
				case "Australian Capital Territory": 
					return 'act'
				case 'Western Australia': 
					return 'wa'
			}
		},
	},
	watch: {
		isBothEnergySubmit() {
			this.getOriginData()
		}
	},
	mounted() {
		this.getOriginData();
	},
	methods: {
		async getOriginData() {
			let query = null;
			if(this.isBothEnergySubmit) {
				 query = {
					state: this.state,
					postcode: this.leadSummary.postcode,
				}
			} else {
				 query = {
					service_type: this.service_Type,
					state: this.state,
					postcode: this.leadSummary.postcode,
				}
			}
            
			this.planDetails = await OriginService.getOriginData(query);
			// console.log("Origin Plan Details Response", this.planDetails)
		},
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
</style>
