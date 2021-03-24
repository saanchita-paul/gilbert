export const states = {
    ACT: { simple_map: 'CT' },
    VIC: { simple_map: 'VI' },
    NSW: { simple_map: 'NS' },
    JBT: { simple_map: 'JB' },
    SA: { simple_map: 'SA' },
    NT: { simple_map: 'NT' },
    NS: { simple_map: 'NS' },
    QLD: { simple_map: 'QL' },
    WA: { simple_map: 'WA' },
}

export const mapStateKey = key => states[key.toUpperCase()]?.simple_map
