import DayJS from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import {capitalize, forEach, isNull} from "lodash-es";
import {getApplicationStatusText} from "@scripts/data/ConnectionApplicationStatuses";
import dayjs from "dayjs";

const mapGilbertApplicationList = data => {
    const values = [];
    data.data.forEach((item) => {
        values.push(mapGilbertApplicationCafFile(item));
    });
    return values;
}

const mapNbnApplicationList = data => {
    const values = [];
    data.data.forEach((item) => {
        values.push(mapNbnApplicationCafFile(item));
    });
    return values;
}

const mapGilbertApplicationCafFile = data => {
    console.log('mapGilbertApplicationCafFile', data)
    let response = {...data};
    console.log('mapGilbertApplicationCafFile', response)
    response.full_name = mapFullName(response);
    response.connection_date = mapConnectionDate(response.moving_date);
    response.created_at = mapCreatedDate(response.created_at);
    response.date_of_birth = mapDateOfBirth(response.date_of_birth);
    response.occupancy_type = mapOccupancyType(response.tenancy_type);
    response.billing = mapBilling(response.is_email_billing);
    response.status = mapStatus(response.status, response.is_generated_caf);
    response.supplier = mapSupplier(response.connection_services);
    response.elctricity_plan = mapPlan(response.connection_services, 'power');
    response.gas_plan = mapPlan(response.connection_services, 'gas');
    response.service_type = mapService(response.connection_services);
    response.is_selected = false;
    response.selected_service = mapSelectedService(response.connection_services);
    return response;
}

const mapNbnApplicationCafFile = data => {
    console.log('mapNbnApplicationCafFile', data)
    let response = {...data};
    console.log('mapNbnApplicationCafFile', response)
    response.full_name = mapFullName(response);
    response.connection_date = mapConnectionDate(response.moving_date);
    response.created_at = mapCreatedDate(response.created_at);
    response.date_of_birth = mapDateOfBirth(response.date_of_birth);
    response.occupancy_type = mapOccupancyType(response.tenancy_type);
    response.billing = mapBilling(response.is_email_billing);
    response.status = mapNbnStatus(response.status, response.internet_service_info.is_caf_generated);
    response.supplier = mapNbnSupplier(response.connection_services);
    response.internet_plan = mapNbnPlan(response.connection_services, 'internet');
    response.service_type = 'Internet';
    response.is_selected = false;
    response.selected_service = mapSelectedService(response.connection_services);
    return response;
}

const mapFullName = data => {
    return !isNull(data.middle_name) ?
        data.first_name + ' ' + data.middle_name + ' ' + data.last_name :
        data.first_name + ' ' + data.last_name;
}

const mapConnectionDate = data => {
    return !isNull(data) ? new DayJS(data).format(DATE_FORMAT.DB_DATE) : '';
}

const mapCreatedDate = data => {
    return !isNull(data) ? dayjs(data, 'DD/MM/YYYY').format(DATE_FORMAT.DB_DATE) : '';
}

const mapDateOfBirth = data => {
    return !isNull(data) ? new DayJS(data).format(DATE_FORMAT.DB_DATE) : '';
}

const mapOccupancyType = data => {
    return data === 1 ? 'Renter' : 'Home Owner';
}

const mapBilling = data => {
    return data === 1 ? 'Email' : 'Post';
}

const mapStatus = (status, is_generated_caf = false) => {
    if (is_generated_caf == true) return 'CAF Submitted';
    return getApplicationStatusText(status);
}

const mapNbnStatus = (status, is_generated_caf = false) => {
    if (is_generated_caf == true) return 'CAF Submitted';
    return getApplicationStatusText(status);
}

const mapSupplier = services => {
    let service = services.find(svc => ((svc.service_type === 'power' || svc.service_type === 'gas') && !!svc.provider_name));
    return service ? service.provider_name.charAt(0).toUpperCase() + service.provider_name.slice(1) : "";
}

const mapNbnSupplier = services => {
    console.log(services)
    let service = services.find(svc => ((svc.service_type === 'internet') && !!svc.provider_name));
    return service ? service.provider_name.charAt(0).toUpperCase() + service.provider_name.slice(1) : "";
}

const mapPlan = (services, serviceType) => {

    const activeService = services?.find(svc => svc.service_type === serviceType);
    switch (activeService?.plan_type) {
        case 'total_plan':
            return 'Total Plan';
        case 'flexi_plan':
            return 'Flexi Plan';
        case 'powershop_100%_carbon_neutral':
            return 'Power Shop 100% Carbon Neutral';
        case 'switch_saver':
            return 'Switch Saver';
        case 'origin_basic':
            return 'Origin Basic';
        case 'origin_home_support':
            return 'Origin Home Support';
        default:
            return '';
    }
}

const mapNbnPlan = (services, serviceType) => {

    const activeService = services?.find(svc => svc.service_type === serviceType);
    switch (activeService?.plan_type) {
        case 'casual_nbn25':
            return 'Casual- nbn 25/10Mbps';
        case 'family_nbn50':
            return 'Family-nbn 50/20Mbps';
        case 'superfast_nbn100':
            return 'Superfast- nbn 100/25Mbps';
        case 'blazing_nbn250':
            return 'Blazing- nbn 250/25Mbps';
        default:
            return '';
    }
}

const mapService = service => {
    let service_types = [];

    service.map(svc => {
        if ((svc.provider_name === 'powershop' || svc.provider_name === 'origin') &&
            (svc.service_type === 'power' || svc.service_type === 'gas')) {
            service_types.push(svc.service_type);
        }
    });

    if (service_types.includes('gas') && service_types.includes('power')) {
        return 'Electricity & Gas';
    }
    if (service_types.includes('gas')) {
        return 'Gas Only';
    }
    if (service_types.includes('power')) {
        return 'Electricity Only';
    }
}

const mapSelectedService = (services) => {
    let filterServices = services.filter(svc => {
        return (svc.service_type === 'gas')
            || (svc.service_type === 'power');
    })
    if (filterServices.length === 2) {
        return 'both'
    } else if (filterServices.length === 1) {
        return filterServices[0].service_type;
    }
    return '';
}

export default {
    mapGilbertApplicationList,
    mapNbnApplicationList
}
