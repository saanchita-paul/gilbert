<template>
	<div class="plan-details">
		<div class="d-flex">
			<v-icon color="yellow" size="20" class="pb-4 pr-2">mdi-flash</v-icon>
			<p class="font-weight-bold">{{ plan.title }}</p>
		</div>
		<div class="pb-2">
			<p class="font-weight-bold" style="font-size:26px">{{ plan.charge }}</p>
			<p class="font-weight-bold" style="font-size:14px">{{ plan.slogan }}</p>
			<p class="plan-content">{{ plan.details }}</p>
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
					<div v-for="item in plan.supply_charge" :key="item.title" class="price-list">
						<div class="pr-12 plan-text" style="font-size:14px">
							{{ item.title }}
						</div>
						<div class="pl-14 plan-text">{{ item.value }}</div>
					</div>

					<div class="d-flex">
						<p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Usage Charges</p>
						<v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
							mdi-progress-question
						</v-icon>
					</div>
					<div v-for="item in plan.usage_charge" :key="item.title"  class="price-list">
						<div class="plan-text" style="font-size:14px">
							{{ item.title }}
						</div>
						<div class="plan-text">{{ item.value }}</div>
					</div>

					<div class="d-flex">
						<p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Fees</p>
					</div>
					<div v-for="item in plan.fees" :key="item.title"  class="price-list">
						<div class="plan-text" style="font-size:14px">
							{{ item.title }}
						</div>
						<div class="plan-text">{{ item.value }}</div>
					</div>

					<p class="plan-text mt-3">We’ve already included any discounts in the rates above. All prices are inclusive of GST.</p>
					<p class="plan-text">Rates are rounded up to the nearest 2 decimal places where applicable.</p>
				</v-expansion-panel-content>
			</v-expansion-panel>
		</v-expansion-panels>
	</div>
</template>

<script>
export default {
	props: {
		plan: {
			require: true,
        },
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
	margin-bottom: 5px;
}
</style>
