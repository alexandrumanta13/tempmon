let values = [
    { day: "28", month: "5", current_temp: "29", interval_min: "13", interval_max: "42" },
    { day: "29", month: "5", current_temp: "28", interval_min: "13", interval_max: "42" },
    { day: "30", month: "5", current_temp: "27", interval_min: "13", interval_max: "42" },
    { day: "31", month: "5", current_temp: "24", interval_min: "13", interval_max: "42" },
    { day: "1", month: "6", current_temp: "26", interval_min: "13", interval_max: "42" },
    { day: "2", month: "6", current_temp: "26", interval_min: "13", interval_max: "42" },
    { day: "3", month: "6", current_temp: "25", interval_min: "13", interval_max: "42" },
    { day: "4", month: "6", current_temp: "26", interval_min: "13", interval_max: "42" },
    { day: "5", month: "6", current_temp: "29", interval_min: "13", interval_max: "42" },
    { day: "6", month: "6", current_temp: "29", interval_min: "13", interval_max: "42" },
]


let hours = [];
for (let i = 0; i < 24; i++) {
    hours[i] = { current_temp: 0, hour: i, offline: 1 };
    hours['length'] = 24;
}

let tempArr = values.map((i, idx) => {
    return parseInt(values[idx].hour);
})

let hoursArr = hours.map((i, idx) => {
    return hours[idx].hour;
})

hoursArr.filter((hour, idx) => {
    if (!tempArr.includes(hour)) {
        if (hours[idx].hour == hour) {
            hours[idx].offline = 0;
            hours[idx].current_temp = null;
        }
    } else {
        console.log(values[idx].current_temp)
        hours[idx].offline = 1;
        hours[idx].current_temp = values[idx].current_temp;
    }
});

console.log(hours)