class DashboardSourceModel{
    constructor({ 
        foxie,
        hood,
        hood_ai,
        ignite,
        our_property, 
        property_me, 
        total }={}){
            this.foxie = foxie
            this.hood = hood
            this.hood_ai = hood_ai
            this.ignite = ignite
            this.our_property = our_property
            this.property_me = property_me
            this.total = total
    }
}

export { DashboardSourceModel }