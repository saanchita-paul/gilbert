import AppMetric from "@scripts/models/crm/AppMetric";


function mapAppMetric(appMetric) {
    const temAppMetric =  new AppMetric({...appMetric})
    switch (temAppMetric.title)
    {
        case 'Applications':
            temAppMetric.color = 'yellow';
            temAppMetric.icon = 'mdi-flash';
            break;

        case 'Non-payable':
            temAppMetric.color = 'red';
            temAppMetric.icon = 'mdi-link';
            break;

        case 'Power':
            temAppMetric.color = 'yellow';
            temAppMetric.icon = 'mdi-flash';
            break;

        case 'Gas':
            temAppMetric.color = 'red';
            temAppMetric.icon = 'mdi-fire';
            break;

        case 'Internet':
            temAppMetric.color = 'grey lighten-1';
            temAppMetric.icon = 'mdi-wifi';
            break;

        case 'Water':
            temAppMetric.color = 'grey lighten-1';
            temAppMetric.icon = 'mdi-water';
            break;
        default:
            temAppMetric.color = 'red';
            temAppMetric.icon = 'mdi-water';
            break;
    }
    return temAppMetric;
}

export default {
    mapAppMetricList: (appMetrics)=> {
        return appMetrics.map(appMetric=> {
            return mapAppMetric(appMetric);
        })
    },

    mapAppMetric: (appMetric) => {
        return mapAppMetric(appMetric);
    }
}
