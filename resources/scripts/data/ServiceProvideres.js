import ProviderPlan from "@scripts/models/crm/ProviderPlan";

export default [
    {
        name: 'ea',
        formatted_name: 'EA',
        service_type: 'energy',
        default_plan: null,
        plans: [],
        logo: '/assets/images/EA.png'
    },
    {
        name: 'origin',
        formatted_name: 'Origin',
        service_type: 'energy',
        default_plan: 'origin_home_assist',
        plans: [
            new ProviderPlan({
                title:'Origin Home Assist',
                name: 'origin_home_assist',
                bgColor: 'red',
                type: 'power',
            }),
            new ProviderPlan({
                title:'Origin Advantage Variable',
                name: 'origin_advantage_variable',
                bgColor: 'red',
                type: 'gas',
            })
        ],
        logo: '/assets/images/logo/providers/origin_new.png'
    },
    {
        name: 'powershop',
        formatted_name: 'Powershop',
        service_type: 'energy',
        default_plan: 'powershop_saver',
        plans: [
            new ProviderPlan({
                title:'100% Carbon Neutral Plan',
                name: 'carbon_neutral_plan',
                bgColor: 'deeppink',
                type: 'power',
            }),
        ],
        logo: '/assets/images/logo/providers/powershop.png'
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
                bgColor: 'purple',
            }),
        ],
        logo: '/assets/images/logo/providers/sumo.png'
    },
    {
        name: 'telstra',
        service_type: 'internet',
        default_plan: 'starter_speed',
        formatted_name: 'Telstra',
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
        name: 'belong',
        service_type: 'internet',
        default_plan: 'starter',
        formatted_name: 'Belong',
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
        name: 'goodtel',
        service_type: 'internet',
        default_plan: 'starter',
        formatted_name: 'Goodtel',
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
    {
        name: '1st-energy',
        formatted_name: '1st Energy',
        service_type: 'energy',
        default_plan: '1st_super_saver',
        plans: [
            new ProviderPlan({
                title:'1st Super Saver',
                name: '1st_super_saver',
                bgColor: '#42B5E8',
                type: 'power',
            }),
        ],
        logo: '/assets/images/logo/providers/1st_energy.png'
    },

]
