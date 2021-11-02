import ProviderPlan from "@scripts/models/crm/ProviderPlan";

export default [
    {
        name: 'origin',
        formatted_name: 'Origin',
        service_type: 'energy',
        default_plan: 'origin_go',
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
        logo: '/assets/images/logo/providers/origin.png'
    },
    {
        name: 'sumo',
        formatted_name: 'Sumo',
        service_type: 'energy',
        default_plan: 'sumo_saver',
        plans: [
            new ProviderPlan({
                title:'Sumo Saver',
                name: 'sumo_saver',
                bgColor: '#B18AFF',
            }),
            new ProviderPlan({
                title:'Sumo ASSURE',
                name: 'sumo_assure',
                bgColor: '#B18AFF',
            }),
            new ProviderPlan({
                title:'Sumo SELECT',
                name: 'sumo_select',
                bgColor: '#B18AFF',
            }),
        ],
        logo: '/assets/images/logo/providers/origin.png'
    },
    {
        name: 'Telstra',
        service_type: 'internet',
        default_plan: 'starter_speed',
        formatted_name: 'telstra',
        plans: [
            new ProviderPlan({
                title:'Starter Speed',
                name: 'starter_speed',
                bgColor: '#932988',
            }),
            new ProviderPlan({
                title:'Standard Plus Speed',
                name: 'standard_plus_speed',
                bgColor: '#932988',
            }),
            new ProviderPlan({
                title:'Premium Speed',
                name: 'premium_speed',
                bgColor: '#932988',
            }),
        ],
        logo: '/assets/images/logo/providers/telstra.png'
    },
    {
        name: 'Belong',
        service_type: 'internet',
        default_plan: 'sumo_saver',
        formatted_name: 'telstra',
        plans: [
            new ProviderPlan({
                title:'Starter',
                bgColor: '#932988',
                name: 'starter',
            }),
            new ProviderPlan({
                title:'Standard Plus',
                bgColor: '#932988',
                name: 'standard_plus',
            }),
            new ProviderPlan({
                title:'Premium',
                bgColor: '#932988',
                name: 'premium',
            }),
        ],
        logo: '/assets/images/logo/providers/belong.png'
    },
    {
        name: 'Goodtel',
        service_type: 'internet',
        default_plan: 'sumo_saver',
        formatted_name: 'telstra',
        plans: [
            new ProviderPlan({
                title:'Starter',
                bgColor: '#42B5E8',
                name: 'starter',
            }),
            new ProviderPlan({
                title:'Standard Plus',
                bgColor: '#42B5E8',
                name: 'standard_plus',
            }),
            new ProviderPlan({
                title:'Premium',
                bgColor: '#42B5E8',
                name: 'premium',
            }),
        ],
        logo: '/assets/images/logo/providers/goodtel.png'
    },

]
