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

const mapApplicationCafFile = data => {
    let model = new ApplicationCafFile({...data });
    model.service_type = mapService(model.service);
    model.supplier = mapProvider(model.service);
    model.connection_date = mapConnDate(model.service);
    model.plan = mapPlan(model.service);
    model.selected_service = getSelectedService(model.service);
    model.selected_plan = getSelectedPlan(model.service);
    return model;
}

const mapService = services => {
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

const mapProvider = services => {
    return services.length != 0 ? capitalize(services[0]['provider_name']) : '';
}

const mapConnDate = services => {
    return services.length != 0 ? new DayJS(services[0]['connection_date']).format(DATE_FORMAT.DB_DATE) : '';
}

const mapPlan = services => {
    return services.length != 0 ? capitalize(services[0]['plan_type']) : '';
}

const getSelectedService = (service) => {
    let filterServices = service.filter(svc => {
        return svc.service_type === 'gas' || svc.service_type === 'electricity';
    })
    if(filterServices.length === 2) {
        return 'both'
    } else if(filterServices.length === 1) {
        return  filterServices[0].service_type;
    }
    return '';
}

const getSelectedPlan = (service) => {
    let filterPlans = service.filter(svc => {
        return svc.plan_type;
    })
    if (filterPlans.length !== 0) {
        return filterPlans[0].plan_type;
    }
    return '';
}


export default {
    mapApplicationCafFileList
}
