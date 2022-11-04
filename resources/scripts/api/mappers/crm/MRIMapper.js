import MriOffice from "@scripts/models/crm/MriOffice";

const mapMriList = (mriOfficeList) => {
    return mriOfficeList.map(office => {
        return mapOffice(office);
    });
}

const mapOffice = (office) => {
    return new MriOffice({...office});
}

export default {
    mapMriList
}
