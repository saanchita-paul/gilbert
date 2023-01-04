import ApplicationCafFile from "@scripts/models/caf/ApplicationCafFile";
import DayJS from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import {capitalize, forEach, isNull} from "lodash-es";

const mapApplicationCafFileList = data => {
    const values = [];
    data.data.forEach((item) => {
        values.push(mapApplicationCafFile(item));
    });
    return values;
}

function mapServices(services) {
    let availableServices = [];
    services.map(svc => {
        if ((svc.service_type === 'gas' && svc.enable_caf_file)
            || (svc.service_type === 'electricity' && svc.enable_caf_file)) {
            availableServices.push(svc.service_type);
        }
    });


    if (availableServices.length === 2) {
        availableServices.push('both');
    }
    return availableServices;

}

function getSelectedService(service) {
    let filterServices = service.filter(svc => {
        return (svc.service_type === 'gas' && svc.enable_caf_file)
            || (svc.service_type === 'electricity' && svc.enable_caf_file);
    })
    if (filterServices.length === 2) {
        return 'both'
    } else if (filterServices.length === 1) {
        return filterServices[0].service_type;
    }
    return '';

}

function isPossibleToMakeCaf(service) {
    if (getSelectedService(service) === '') return false;
    return true;
}

function mapServiceStatus(service) {
    let status = '';
    service.map(svc => {
        let serStatus = svc.status;
        if (isNull(serStatus)) {
            serStatus = '';
        }
        if (svc.service_type === 'gas') {
            status = status + ' Gas: ' + serStatus
        }

        if (svc.service_type === 'electricity') {
            status = status + ' Electricity: ' + serStatus
        }

    });
    return status;


}
 const mapApplicationCafFile = data => {
    let model = new ApplicationCafFile({...data});
    model.service_type = mapService(model.service);
    model.supplier = mapProvider(model.service);
    model.connection_date = mapConnDate(model.connection_date);
    model.plan = mapPlan(model.service);
    model.selected_service = getSelectedService(model.service);
    model.service_dropdown = mapServices(model.service);
    model.is_possible_caf_file = isPossibleToMakeCaf(model.service);
    model.status = mapServiceStatus(model.service)
    model.is_selected = false;
    model.services = model.service;
    return model;
}

export const mapService = services => {
    let service_types = [];
    services.map(service => {
        service_types.push(service['service_type']);
    });

    if (service_types.includes('gas') && service_types.includes('electricity')) {
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

export const mapConnDate = conn_date => {
    return !isNull(conn_date) ? new DayJS(conn_date).format(DATE_FORMAT.DB_DATE) : '';
}

export const mapPlan = services => {
    return services.length != 0 ? services[0]['plan_type'] : '';
}


export default {
    mapApplicationCafFileList,
    getFullName(response) {
        if (!isNull(response.middle_name)) {
            return response.first_name + ' ' + response.middle_name + ' ' + response.last_name;
        }
        return response.first_name + ' ' + response.last_name;
    },
    mapSingleData(response) {
        return {
            ...response,
            full_name: this.getFullName(response),
            connection_date: this.getConnectionData(response),
            plan: this.getServicePlan(response)
        }
    },

    getConnectionData(response) {
        let service = response.service;
        if (service.length > 0) {
            let activeService = service.find(svc => svc.enable_caf_file);
            if ((activeService)) {
                return DayJS(activeService.connection_date).format(DATE_FORMAT.DB_DATE);
            }
            return null;
        }
    },
    getServicePlan(response) {
        let service = response.service;
        if (service.length > 0) {

            let activeService = service.find(svc => svc.enable_caf_file);
            if ((activeService)) {
                return activeService.plan_type;
            }
            return null;

        }
    },

    mapChatbotSingleApplication(app) {
        return mapApplicationCafFile(app);
    }
}
