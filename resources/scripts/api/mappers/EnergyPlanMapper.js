export default {
    mapEnergyPlan(plans)
    {
        return plans.map((data)=> {
            return {...data}
        })
    }
}
