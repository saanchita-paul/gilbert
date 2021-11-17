export default {
    mapAddress :(data)=>{
        return {
            address: '1/309 Cumberland Rd, Pascoe Vale VIC 3044',
            quoteNumber: 'randomQuoteNumberFromOurServer',
        }
    },
    mapProduct :(location)=>{
        return {
            campaign: 'hood',
            channel: 'crm',
            consultantID: 'OurAgentName',
            electricityDistributor: location.electricityDistributors.distributor,
            fuelType: 'D',
            nmi: location.nmi,
            postcode: location.postcode,
            prospectType: 'Residential',
            quoteNumber: 'randomQuoteNumberFromOurServer',
            suburb: location.suburbOrPlaceOrLocality,
        }
    }
};