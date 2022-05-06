<template>
	<div class="plan-details">
		<div class="d-flex">
			<v-icon color="yellow" size="20" class="pb-4 pr-2">mdi-flash</v-icon>
			<p class="font-weight-bold">{{ planData[0].title }}</p>
		</div>
		<div class="pb-2">
			<p class="font-weight-bold" style="font-size:26px">{{ planData[0].charge }}</p>
			<p class="font-weight-bold" style="font-size:14px">{{ planData[0].slogan }}</p>
			<p class="plan-content">{{ planData[0].details }}</p>
		</div>
		<v-expansion-panels>
			<v-expansion-panel color="red">
				<v-expansion-panel-header class="font-weight-bold">
					See electricity prices
					<template v-slot:actions><v-icon color="orange">mdi-menu-down</v-icon></template>
				</v-expansion-panel-header>
				<v-expansion-panel-content>
					<div class="d-flex">
						<p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Supply Charge</p>
						<v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
							mdi-progress-question
						</v-icon>
					</div>
					<div v-for="(item, index) in planData[0].supply_charge" :key="index" class="price-list">
						<p class="pr-12 plan-text" style="font-size:14px">
							{{ item.title }}
						</p>
						<p class="pl-14 plan-text">{{ item.value }}</p>
					</div>

					<div class="d-flex">
						<p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Usage Charges</p>
						<v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
							mdi-progress-question
						</v-icon>
					</div>
					<div v-for="(item, index) in planData[0].usage_charge" :key="index"  class="price-list">
						<p class="plan-text" style="font-size:14px">
							{{ item.title }}
						</p>
						<p class="plan-text">{{ item.value }}</p>
					</div>
					<p class="plan-text">We’ve already included any discounts in the rates above. All prices are inclusive of GST.</p>
					<p class="plan-text">Rates are rounded up to the nearest 2 decimal places where applicable.</p>
				</v-expansion-panel-content>
			</v-expansion-panel>
		</v-expansion-panels>
	</div>
</template>

<script>
export default {
	props: {
		planData: {
            type: Array,
        },
    },
	data() {
		return {
		}
	},
	
}
</script>

<style scoped>
.plan-details {
	padding: 4% 2% 4% 6%;
	margin: 2% 2% 8% 2%;
	outline: #cccaca solid 1px;
}
.plan-details .v-expansion-panel::before {
	box-shadow: none !important;
}
.plan-title {
	padding-top: 8%;
	padding-left: 8%;
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
.plan-content {
	color: #505050;
	font-size: 14px;
	font-family: sans-serif;
	margin-top: 0%;
	padding-top: 0%;
}
.price-list {
	display: flex;
	justify-content: space-between;
}
</style>
