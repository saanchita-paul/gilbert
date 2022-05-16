<template>
	<v-app v-if="originPlanDetails">
		<div fluid>
			<v-container class="container-box">
				<v-card class="card-section">
                    <div class="plan-title-header pt-4 pl-4 pr-4 pb-2 d-flex justify-space-between">
                        <div>
                            <p class="font-weight-bold mb-0" style="font-size:24px">Origin Plan - {{ getPlanText }}</p>
                            <p style="font-size:20px">{{ getServiceText }}</p>
                        </div>
                        <div class="pt-4" style="cursor:pointer" @click="closeDialog">
                            <v-icon aria-hidden="false" color="white">
                                mdi-close
                            </v-icon>
                        </div>
                    </div>
					<div class="pl-6 pt-8">
						<p class="font-weight-bold" style="font-size:20px">{{ originPlanDetails.title }}</p>
						<p class="pb-4">{{ originPlanDetails.short_des }}</p>
					</div>

					<ElectricityPlan :planData = "electricityPlanData"></ElectricityPlan>

					<GasPlan :planData = "gasPlanData"></GasPlan>

					<div class="pl-8 pr-8">
						<p class="font-weight-bold" style="font-size:14px">Inlcuded in your plan</p>
						<div class="d-flex">
							<p class="font-weight-bold mb-0" style="font-size:14px;">Rates:</p>
							<div class="d-flex">
								<p class="pl-2 mb-0" style="font-size:14px;">{{ originPlanDetails.rates }}</p>
								<v-icon aria-hidden="false" class="pl-1 mb-0" size="80%">
									mdi-progress-question
								</v-icon>
							</div>
						</div>
						<div class="d-flex">
							<p class="font-weight-bold mb-0" style="font-size:14px;">Exit Fees:</p>
							<p class="pl-2 mb-0" style="font-size:14px;">{{ originPlanDetails.exit_fees }}</p>
						</div>
						<div class="d-flex">
							<p class="font-weight-bold mb-0" style="font-size:14px;">Benefit Period:</p>
							<p class="pl-2 mb-0" style="font-size:14px;">{{ originPlanDetails.benefit_period }}</p>
						</div>
						<p class="font-weight-bold mb-0" style="font-size:14px;">Green options</p>
						<p class="plan-text mt-0" style="font-size:14px;">{{ originPlanDetails.green_options }}</p>
						<hr class="mb-4" style="width:95%" />

						<div class="pb-6" style="font-size:14px; text-decoration: underline;">
							<p class="mb-1">Energy Fact Sheet (Electricity)</p>
							<p class="mb-1">Energy Fact Sheet (Gas)</p>
							<p class="mb-1">Terms and conditions</p>
						</div>
					</div>

					<v-btn class="selectButton" color="#cd5b32">Select Plan</v-btn>
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
        }
    },
	data() {
		return {
			originPlanDetails: null,
		}
	},
	mounted() {
		this.getOriginData()
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
		electricityPlanData() {
			return this.originPlanDetails.plan.map(plan => {
				if(plan.title == "Electricity") {
					return plan
				}
			})
		},

		gasPlanData() {
			return this.originPlanDetails.plan.map(plan => {
				if(plan.title == "Gas") {
					return plan
				}
			})
		},
	},
	methods: {
		async getOriginData() {
			this.originPlanDetails = await OriginService.getOriginData()
			// console.log("console here", this.originPlanDetails)
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
.plan-title-header {
	background-color: #cd5b32;
	color: white;
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
