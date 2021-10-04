import {SERVICE_TYPES} from "@scripts/models/ea/EnergyPlan";
import {getStateKey} from "@scripts/data/constants/STATES";


export const mapEAPlanQuery = query => {
    const state = getStateKey(query.state)
    if (!state) {
        throw new Error("Unknown State: " + query.state)
    }
    return {
        ...query,
        service_type:  mapServices(query.service_type),
        state: state,
    }
}

export const mapServices = services => {
    if (services.includes('gas') && services.includes('power')) {
        return SERVICE_TYPES.ELECTRICITY_AND_GAS
    }
    if (services.includes('gas')) {
        return  SERVICE_TYPES.GAS
    }

    if (services.includes('power')) {
        return  SERVICE_TYPES.ELECTRICITY
    }
    throw new Error("Unknown service type")
}
