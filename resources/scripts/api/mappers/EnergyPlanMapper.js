export default {
    mapEnergyPlan(plans)
    {
        return plans.map((data)=> {
            return {...data, 
                retailer : "Energy Australia",
                power_sales : 345,
                gas_sales : 400
            }
        })
    }
}
