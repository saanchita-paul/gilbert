import ApplicationCafFile from "@scripts/models/caf/ApplicationCafFile";
import DayJS from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import {capitalize} from "lodash-es";

const mapApplicationCafFileList =  data => {
    const values = [];
    data.data.forEach((item) => {
        values.push(mapApplicationCafFile(item));
    });
    return values;
}

function mapServices(services) {
    let availableServices = services.map(svc => {
        if( (svc.service_type === 'gas' && svc.enable_caf_file )
            || (svc.service_type === 'electricity' && svc.enable_caf_file)) {
            return svc.service_type;
        }
    });

    if(availableServices.length === 2) {
        availableServices.push('both');
    }
    return availableServices;

}

function getSelectedService(service) {
    let filterServices = service.filter(svc => {
        return (svc.service_type === 'gas' && svc.enable_caf_file )
            || (svc.service_type === 'electricity' && svc.enable_caf_file);
    })
    if(filterServices.length === 2) {
        return 'both'
    } else if(filterServices.length === 1) {
       return  filterServices[0].service_type;
    }
    return '';

}

function isPossibleToMakeCaf(service) {
    if(getSelectedService(service) === '') return false;
    return true;
}

const mapApplicationCafFile = data => {
    let model = new ApplicationCafFile({...data });
    model.service_type = mapService(model.service);
    model.supplier = mapProvider(model.service);
    model.connection_date = mapConnDate(model.service);
    model.plan = mapPlan(model.service);
    model.selected_service = getSelectedService(model.service);
    model.service_dropdown = mapServices(model.service);
    model.is_possible_caf_file = isPossibleToMakeCaf(model.service);
    model.is_selected = false
    return model;
}

export const mapService = services => {
    let service_types = [];
    services.map(service => {
        service_types.push(service['service_type']);
    });
    console.log('service type array', services);
    if (service_types.includes('gas') && service_types.includes('electricity')){
        return 'Electricity & Gas';
    }
    if (service_types.includes('gas')) {
        return 'Gas Only';
    }
    if (service_types.includes('electricity')) {
        return 'Electricity Only';
    }
}

export const mapProvider = services => {
    return services.length != 0 ? capitalize(services[0]['provider_name']) : '';
}

export const mapConnDate = services => {
    return services.length != 0 ? new DayJS(services[0]['connection_date']).format(DATE_FORMAT.DB_DATE) : '';
}

export const mapPlan = services => {
    return services.length != 0 ? capitalize(services[0]['plan_type']) : '';
}


export default {
    mapApplicationCafFileList
}
