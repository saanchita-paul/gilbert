export class Plan{
    constructor({title, key, provider, color} = {}) {
        this.title = title;
        this.key = key;
        this.provider = provider;
        this.color = color;
    }
}

export const PLAN = [
   new Plan( {title: 'A', key : 'a', provider: 'ea', color: 'red'}),
   new Plan( {title: 'B', key : 'b', provider: 'origin', color: 'black'}),
   new Plan( {title: 'D', key : 'c', provider: 'sumo', color: 'blue'}),
   new Plan( {title: 'E', key : 'd', provider: 'powershop', color: 'white'}),
];

export function getProviderBackgound(data) {

    return data.map((dt) => {
        return dt.color;
    });

}



