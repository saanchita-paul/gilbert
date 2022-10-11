export default {

    mapEnergyPlan(plans)
    {
        const ROOT = `${process.env.MIX_BOT_ROOT_URL}/images/static/plan/`;
        return plans.map((data)=> {
            return {...data,
                power_sales : 'NA',
                gas_sales : 'NA',
                image: ROOT + data.image + '.png'
            }
        })
    }
}
