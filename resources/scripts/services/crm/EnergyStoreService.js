import Store from '@scripts/store/index';

export default {
    getEnergyService: () => Store.getters.energyService,
    setEnergyService: service => Store.commit("setEnergyService", service)
};
