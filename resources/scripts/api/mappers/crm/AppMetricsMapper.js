import AppMetric from "@scripts/models/crm/AppMetric";
import UserLead from "@scripts/models/crm/UserLead";


function mapAppMetric(appMetric, data) {
    const temAppMetric =  new AppMetric({...appMetric})
    switch (temAppMetric.title)
    {
        case 'Applications':
            temAppMetric.color = 'success';
            temAppMetric.icon = 'mdi-home';
            temAppMetric.lead_count = data.applications;
            break;

        case 'Non-payable':
            temAppMetric.color = 'red';
            temAppMetric.icon = 'mdi-link';
            temAppMetric.lead_count = data.nonpayable;
            break;

        case 'Power':
            temAppMetric.color = 'yellow';
            temAppMetric.icon = 'mdi-flash';
            temAppMetric.lead_count = data.power;
            break;

        case 'Gas':
            temAppMetric.color = 'red';
            temAppMetric.icon = 'mdi-fire';
            temAppMetric.lead_count = data.gas;
            break;

        case 'Internet':
            temAppMetric.color = 'grey lighten-1';
            temAppMetric.icon = 'mdi-wifi';
            temAppMetric.lead_count = data.internet;
            break;

        case 'Water':
            temAppMetric.color = 'grey lighten-1';
            temAppMetric.icon = 'mdi-water';
            temAppMetric.lead_count = data.water;
            break;
        default:
            temAppMetric.color = 'red';
            temAppMetric.icon = 'mdi-water';
            temAppMetric.lead_count = data.water;
            break;
    }

    if(temAppMetric.lead_count == 0) {
        temAppMetric.color = 'grey lighten-1';
    }
    return temAppMetric;
}

function mapUserLead(lead) {
    return new UserLead({...lead})
}

export default {
    mapAppMetricList: (appMetrics,data)=> {
        return {
            mapData: appMetrics.map(appMetric=> {
                return mapAppMetric(appMetric, data);
            }),
            data: data
        }
    },

    mapAppMetric: (appMetric) => {
        return mapAppMetric(appMetric);
    },

    mapUserMetLeads: (userLeads) => {
        return userLeads.map(lead=> {
            return mapUserLead(lead);
        })
    }
}
