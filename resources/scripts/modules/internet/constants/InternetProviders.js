import InternetPlan from "@scripts/modules/internet/models/InternetPlan";

export default [
    {
        name: 'goodtel',
        formatted_name: 'Goodtel',
        logo: '/assets/images/logo/providers/goodtel.png',
        plans: [
            new InternetPlan({
                title:'Goodtel',
                logo: '/assets/images/logo/providers/goodtel_logo.png',
                name: 'Casual nbn™',
                mbps: '25/10Mbps',
                amount: '$10/month',
            }),
            new InternetPlan({
                title:'Goodtel',
                logo: '/assets/images/logo/providers/goodtel_logo.png',
                name: 'Family nbn™',
                mbps: '25/20Mbps',
                amount: '$20/month',
            }),
            new InternetPlan({
                title:'Goodtel',
                logo: '/assets/images/logo/providers/goodtel_logo.png',
                name: 'Superfast nbn™',
                mbps: '100/20Mbps',
                amount: '$30/month',
            }),
            new InternetPlan({
                title:'Goodtel',
                logo: '/assets/images/logo/providers/goodtel_logo.png',
                name: 'Blazing nbn™',
                mbps: '350/20Mbps',
                amount: '$40/month',
            }),
        ]
    }
]
