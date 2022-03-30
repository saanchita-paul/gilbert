export const leadSourceMap = {
    SOURCE_ALL: 3,
    SOURCE_HOOD: 0,
    SOURCE_FOXIE: 1,
    SOURCE_IGNITE: 2,
};

export const sources = [
    { text: "All", value: "" },
    { text: "Hood", value: "hood" },
    { text: "Foxie", value: "foxie" },
    { text: "Ignite", value: "ignite" },
    { text: "Our-Property", value: "our-property" },
    { text: "Property_Me", value: "property_me" },
];

export const sourcesNumberToName = {
    0 : 'Hood',
    1 : 'Foxie',
    2 : 'Ignite', 
    3 : 'All',
    4 : 'Our-Property',
    5 : 'Property_Me',
    11 : 'TApp',
};
export const leadSourceMapFromNumber = {
        3 : 'All',
        0 : 'Hood',
        1 : 'Foxie',
        2 : 'Ignite',   
        4 : 'OurProperty',   
        5 : 'PropertyMe',   
        11 : 'TApp',   
}

