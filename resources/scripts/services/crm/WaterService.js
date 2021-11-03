import WaterAPI from "@scripts/api/crm/WaterAPI";


export default {
    saveWater: (water, id) => WaterAPI.saveWater(water, id),
}
