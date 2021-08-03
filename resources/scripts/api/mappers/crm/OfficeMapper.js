import Office from "@scripts/models/crm/Office";

function mapOffice(office) {
    console.log('aaa',office);
    return new Office({...office});
}

export default {
    mapOfficeList: (officeList)=> {
        return officeList.map(office=> {
            return mapOffice(office);
        })
    },
    mapOffice: (office) => {
        return mapOffice(office.office);
    }
}
