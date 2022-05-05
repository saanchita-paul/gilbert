<template>
	<v-app>
		<div fluid>
			<v-container fluid>
				<v-card class="card-section">
					<div class="plan-title-header pt-4 pl-4 pb-2">
						<p class="font-weight-bold mb-0" style="font-size:24px">Origin Plan - Origin Go</p>
						<p style="font-size:20px">Electricity & Gas</p>
					</div>
					<div class="pl-6 pt-8">
						<p class="font-weight-bold" style="font-size:20px">Great rates that wont change over 12 months</p>
						<p class="pb-4">Rates guaranteed for 12 months, simplified pricing, Everyday Rewards points</p>
					</div>

					<ElectricityPlan></ElectricityPlan>

					<GasPlan></GasPlan>

					<div class="pl-8 pr-8">
						<p class="font-weight-bold" style="font-size:14px">Inlcuded in your plan</p>
						<div class="d-flex">
							<p class="font-weight-bold mb-0" style="font-size:14px;">Rates:</p>
							<div class="d-flex">
								<p class="pl-2 mb-0" style="font-size:14px;">Guarranteed</p>
								<v-icon aria-hidden="false" class="pl-1 mb-0" size="80%">
									mdi-progress-question
								</v-icon>
							</div>
						</div>
						<div class="d-flex">
							<p class="font-weight-bold mb-0" style="font-size:14px;">Exit Fees:</p>
							<p class="pl-2 mb-0" style="font-size:14px;">No</p>
						</div>
						<div class="d-flex">
							<p class="font-weight-bold mb-0" style="font-size:14px;">Benefit Period:</p>
							<p class="pl-2 mb-0" style="font-size:14px;">12 months</p>
						</div>
						<p class="font-weight-bold mb-0" style="font-size:14px;">Green options</p>
						<p class="plan-text mt-0" style="font-size:14px;">Everyday Rewards members enjoy 25% GreenPower and 100% Green Gas for nothing extra</p>
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
import OriginService from "@scripts/services/OriginService"

export default {
	name: "OriginView",
	components: {
		ElectricityPlan,
		GasPlan,
	},
	data() {
		return {
			title: null,
			charge: null,
			slogan: null,
			planDetails: null,
			priceList: {
				listTitle: null,
				title: null,
				value: null,
				description: null,
			},
			rates: null,
			exitFees: null,
			benefitPeriod: null,
			greenOptions: null,
		}
	},
	mounted() {
		this.getOriginData()
	},

	methods: {
		async getOriginData() {
			let data = await OriginService.getOriginData()
			// data will be here
		},
	},
}
</script>

<style scoped>
.card-section {
	max-width: 400px;
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
