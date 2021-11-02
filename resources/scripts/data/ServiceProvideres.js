import ProviderPlan from "@scripts/models/crm/ProviderPlan";

export default [
    {
        name: 'Origin',
        service_type: 'energy',
        plans: [
            new ProviderPlan({
                title:'Origin Go',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Origin Go Variable',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Origin Basic',
                bgColor: 'red',
            }),
        ],
        logo: '/bla/bla'
    },
    {
        name: 'Sumo',
        service_type: 'energy',
        plans: [
            new ProviderPlan({
                title:'Sumo Saver',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Sumo ASSURE',
                bgColor: 'red',
            }),
            new ProviderPlan({
                title:'Sumo SELECT',
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
