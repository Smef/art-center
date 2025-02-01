const stateList = [
    {
        name: "Alabama",
        abbr: "AL",
    },
    {
        name: "Alaska",
        abbr: "AK",
    },
    {
        name: "Arizona",
        abbr: "AZ",
    },
    {
        name: "Arkansas",
        abbr: "AR",
    },
    {
        name: "California",
        abbr: "CA",
    },
    {
        name: "Colorado",
        abbr: "CO",
    },
    {
        name: "Connecticut",
        abbr: "CT",
    },
    {
        name: "Delaware",
        abbr: "DE",
    },
    {
        name: "District of Columbia",
        abbr: "DC",
    },
    {
        name: "Florida",
        abbr: "FL",
    },
    {
        name: "Georgia",
        abbr: "GA",
    },
    {
        name: "Hawaii",
        abbr: "HI",
    },
    {
        name: "Idaho",
        abbr: "ID",
    },
    {
        name: "Illinois",
        abbr: "IL",
    },
    {
        name: "Indiana",
        abbr: "IN",
    },
    {
        name: "Iowa",
        abbr: "IA",
    },
    {
        name: "Kansas",
        abbr: "KS",
    },
    {
        name: "Kentucky",
        abbr: "KY",
    },
    {
        name: "Louisiana",
        abbr: "LA",
    },
    {
        name: "Maine",
        abbr: "ME",
    },
    {
        name: "Maryland",
        abbr: "MD",
    },
    {
        name: "Massachusetts",
        abbr: "MA",
    },
    {
        name: "Michigan",
        abbr: "MI",
    },
    {
        name: "Minnesota",
        abbr: "MN",
    },
    {
        name: "Mississippi",
        abbr: "MS",
    },
    {
        name: "Missouri",
        abbr: "MO",
    },
    {
        name: "Montana",
        abbr: "MT",
    },
    {
        name: "Nebraska",
        abbr: "NE",
    },
    {
        name: "Nevada",
        abbr: "NV",
    },
    {
        name: "Nevada",
        abbr: "NV",
    },
    {
        name: "New Hampshire",
        abbr: "NH",
    },
    {
        name: "New Jersey",
        abbr: "NJ",
    },
    {
        name: "New Mexico",
        abbr: "NM",
    },
    {
        name: "New York",
        abbr: "NY",
    },
    {
        name: "North Carolina",
        abbr: "NC",
    },
    {
        name: "North Dakota",
        abbr: "ND",
    },
    {
        name: "Ohio",
        abbr: "OH",
    },
    {
        name: "Oklahoma",
        abbr: "OK",
    },
    {
        name: "Oregon",
        abbr: "OR",
    },
    {
        name: "Pennsylvania",
        abbr: "PA",
    },
    {
        name: "Rhode Island",
        abbr: "RI",
    },
    {
        name: "South Carolina",
        abbr: "SC",
    },
    {
        name: "South Dakota",
        abbr: "SD",
    },
    {
        name: "Tennessee",
        abbr: "TN",
    },
    {
        name: "Texas",
        abbr: "TX",
    },
    {
        name: "Utah",
        abbr: "UT",
    },
    {
        name: "Vermont",
        abbr: "VT",
    },
    {
        name: "Virginia",
        abbr: "VA",
    },
    {
        name: "Washington",
        abbr: "WA",
    },
    {
        name: "West Virginia",
        abbr: "WV",
    },
    {
        name: "Wisconsin",
        abbr: "WI",
    },
    {
        name: "Wyoming",
        abbr: "WY",
    },
    {
        name: "American Samoa",
        abbr: "AS",
    },
    {
        name: "Guam",
        abbr: "GU",
    },
    {
        name: "Northern Mariana Islands",
        abbr: "MP",
    },
    {
        name: "Puerto Rico",
        abbr: "PR",
    },
    {
        name: "US Virgin Islands",
        abbr: "VI",
    },
    {
        name: "US Minor Outlying Islands",
        abbr: "UM",
    },
];

const helper = (state: string) => {
    if (!state) return stateList;
    const stateSplit = state.split(".").join("");
    const found = stateList.find(
        (item) =>
            item.name.toUpperCase() === stateSplit.toUpperCase() ||
            item.abbr.toUpperCase() === stateSplit.toUpperCase(),
    );
    if (found) return found;

    return "No state found!";
};

helper.abbr = (state: string) => {
    if (!state) return "Please pass a full state name as your argument";
    const found = stateList.find((item) => item.name.toUpperCase() === state.toUpperCase());
    if (found) return found.abbr;
    return "No abbreviation found with that state name";
};

helper.fullName = (abbr: string) => {
    if (!abbr) return "Please pass an abbreviation as your argument";
    const found = stateList.find((item) => item.abbr.toUpperCase() === abbr.toUpperCase());
    if (found) return found.name;
    return "No state found with that abbreviation";
};

helper.only50 = () => {
    const notStates = ["DC", "AS", "GU", "MP", "PR", "VI", "UM"];
    return stateList.filter((item) => !notStates.includes(item.abbr));
};

helper.stateList = stateList;

export default helper;
