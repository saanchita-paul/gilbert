import Source from "@scripts/models/crm/Source";

const mapSourceList = sourceList => {
    return sourceList?.data.map(source => {
        return mapSource(source);
    })
}

const mapSource = source => new Source({ ...source });

export default {
    mapSourceList
};
