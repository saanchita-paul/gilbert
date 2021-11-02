import ProviderPlan from "@scripts/models/crm/ProviderPlan";

export default [
    {
        name: 'origin',
        formatted_name: 'Origin',
        service_type: 'energy',
        default_plant: 'origin_go',
        plans: [
            new ProviderPlan({
                title:'Origin Go',
                name: 'origin_go',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Origin Go Variable',
                name: 'origin_go_variable',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Origin Basic',
                name: 'origin_basic',
                bgColor: 'red',
            }),
        ],
        logo: '/bla/bla'
    },
    {
        name: 'sumo',
        formatted_name: 'Sumo',
        service_type: 'energy',
        default_plant: 'sumo_saver',
        plans: [
            new ProviderPlan({
                title:'Sumo Saver',
                name: 'sumo_saver',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Sumo ASSURE',
                name: 'sumo_assure',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Sumo SELECT',
                name: 'sumo_assure',
                bgColor: 'red',
            }),
        ],
        logo: '/bla/bla'
    },
    {
        name: 'Telstra',
        service_type: 'internet',
        plans: [
            new ProviderPlan({
                title:'Starter',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Standard Plus',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Premium',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'A',
                bgColor: 'red',
            }),
        ],
        logo: '/bla/bla'
    },
    {
        name: 'Belong',
        service_type: 'internet',
        plans: [
            new ProviderPlan({
                title:'Starter',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Standard Plus',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Premium',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'A',
                bgColor: 'red',
            }),
        ],
        logo: '/bla/bla'
    },
    {
        name: 'Goodtel',
        service_type: 'internet',
        plans: [
            new ProviderPlan({
                title:'Starter',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Standard Plus',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Premium',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'A',
                bgColor: 'red',
            }),
        ],
        logo: '/bla/bla'
    },

]
