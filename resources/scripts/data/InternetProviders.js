import InternetPlan from "@scripts/models/crm/InternetPlan";

export default [
    {
        name: 'goodtel',
        formatted_name: 'Goodtel',
        logo: '/assets/images/logo/providers/goodtel.png',
        plans: [
            new InternetPlan({
                title:'Goodtel',
                logo: '/assets/images/logo/providers/goodtel_logo.png',
                bgColor: '#85639A',
                name: 'Casual nbn™ 25/10Mbps',
                amount: '$[XX]/month',
            }),
            new InternetPlan({
                title:'Goodtel',
                logo: '/assets/images/logo/providers/goodtel_logo.png',
                bgColor: '#85639A',
                name: 'Family nbn™ 50/20Mbps',
                amount: '$[XX]/month',
            }),
            new InternetPlan({
                title:'Goodtel',
                logo: '/assets/images/logo/providers/goodtel_logo.png',
                bgColor: '#85639A',
                name: 'Superfast nbn™ 100/20Mbps',
                amount: '$[XX]/month',
            }),
            new InternetPlan({
                title:'Goodtel',
                logo: '/assets/images/logo/providers/goodtel_logo.png',
                bgColor: '#85639A',
                name: 'Blazing nbn™ 350/25Mbps',
                amount: '$[XX]/month',
            }),
        ]
    }
]
