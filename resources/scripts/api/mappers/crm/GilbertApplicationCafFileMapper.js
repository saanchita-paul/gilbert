import DayJS from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import {capitalize, forEach, isNull} from "lodash-es";
import {getApplicationStatusText} from "@scripts/data/ConnectionApplicationStatuses";

const mapGilbertApplicationList =  data => {
    const values = [];
    data.data.forEach((item) => {
        values.push(mapGilbertApplicationCafFile(item));
    });
    return values;
}

const mapGilbertApplicationCafFile = data => {
    let response = {...data};
    response.full_name = mapFullName(response);
    response.connection_date = mapConnectionDate(response.moving_date);
    response.created_at = mapCreatedDate(response.created_at);
    response.date_of_birth = mapDateOfBirth(response.date_of_birth);
    response.occupancy_type = mapOccupancyType(response.tenancy_type);
    response.billing = mapBilling(response.is_email_billing);
    response.status = mapStatus(response.status);
    response.supplier = mapSupplier(response.connection_services);
    response.elctricity_plan = mapPlan(response.connection_services, 'power');
    response.gas_plan = mapPlan(response.connection_services, 'gas');
    response.service_type = mapService(response.connection_services);
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
    return !isNull(data) ? new DayJS(data).format(DATE_FORMAT.DB_DATE) : '';
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

const mapStatus = status => {
    return getApplicationStatusText(status);
}

const mapSupplier = provider => {
    return 'PowerShop';
}

const mapPlan = (services , serviceType)=> {

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
        default:
            return '';
    }
}

const mapService = service => {

    let service_types = [];

    service.map(svc => {
        service_types.push(svc['service_type']);
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
    if(filterServices.length === 2) {
        return 'both'
    } else if(filterServices.length === 1) {
        return  filterServices[0].service_type;
    }
    return '';
}

export default {
    mapGilbertApplicationList,
}
