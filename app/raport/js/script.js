
// Toggle options on mobile

const mobile = window.outerWidth;
const body = document.querySelector('body');

function slideSettingsToggle(ev) {
    ev.classList.toggle('slide');
}

const notifications = document.querySelector('.notifications');
const user = document.querySelector('.user');

function showMenu(ev) {
    ev.classList.toggle('show');
    body.classList.toggle('hidden');
}

document.addEventListener('click', function (event) {
    let isClickInside;
    if (notifications) {
        isClickInside = notifications.contains(event.target);
    }
    else if (user) {
        isClickInside = user.contains(event.target);
    }


    if (!isClickInside) {
        if (notifications) {
            notifications.classList.remove('show');
        }
        else if (user) {
            user.classList.remove('show');
        }
        body.classList.remove('hidden');
    }
});


if (mobile < 600) {
    if (notifications) {
        notifications.setAttribute("onclick", "showMenu(this)");
    }
    if (user) {
        user.setAttribute("onclick", "showMenu(this)");
    }
}

const navToggle = document.querySelector('.gateway-inner');
const settingsButton = document.querySelector('.gateway-inner-collapse');

if (navToggle) {
    function mainNavToggle() {
        navToggle.classList.toggle('collapsed');
    }

    settingsButton.addEventListener('click', mainNavToggle);
}


// Toggle alert

const alert = document.querySelector('.alert');
const close = document.querySelector('.close');

function alertToggle() {
    alert.classList.toggle('on');
}

if (close) {
    close.addEventListener('click', alertToggle);
}


// Options

const dayCanvas = document.querySelector("#dayCanvas");
const weekCanvas = document.querySelector("#weekCanvas");
const monthCanvas = document.querySelector("#monthCanvas");
const canvas = document.querySelectorAll('canvas');

// Apply multiply blend when drawing datasets
const multiply = {
    beforeDatasetsDraw: function (chart, options, el) {
        chart.ctx.globalCompositeOperation = 'multiply';
        chart.canvas.parentNode.style.height = '237px';

    },
    afterDatasetsDraw: function (chart, options) {
        chart.ctx.globalCompositeOperation = 'source-over';
        chart.canvas.parentNode.style.height = '237px';

    },
};

moment.locale('ro');
const daysContainer = document.querySelectorAll('.week-number');
const weekDaysContainer = document.querySelectorAll('.week-name');
const tempContainer = document.querySelectorAll('.week-value--this');

const hourContainer = document.querySelectorAll('.day-number');

const hoursDayContainer = document.querySelectorAll('.day-name');
const hourTempContainer = document.querySelectorAll('.day-value--this');

const monthDayContainer = document.querySelectorAll('.month-name');
const monthNumberContainer = document.querySelectorAll('.month-number');
const monthTempContainer = document.querySelectorAll('.month-value--this');

const currentDate = moment();
// const weekStart = currentDate.clone().startOf('week');
const weekStart = document.querySelector('.week-start');
const weekEnd = currentDate.clone().endOf('week');
const dayStart = currentDate.clone().startOf('day');
const datEnd = currentDate.clone().endOf('day');

let days = [];
let weekDays = [];
let weekTemp = [];
let hour = [];
let hoursDay = [];
let hourTemp = [];
let month = [];
let monthDay = [];
let monthTemp = [];
let dayColor = [];
let dayHoverColor = [];
let monthColor = [];
let monthHoverColor = [];
let weekColor = [];
let weekHoverColor = [];
let hoverColor = [];
let graphLine = [];
let graphBorderLine = [];
let monthData = [24, 22, 21, 25, 26, 27, 29, 30, 22, , 26, 24, 29, 30, , 24, 24, , 25, 26, 26, 26, 27, 28];

const tempMax = document.querySelector('#tempMax');


if (tempMax) {
    tempMax.value;
}
const tempMin = document.querySelector('#tempMin');
if (tempMin) {
    tempMin.value;
}
const green = 'rgba(39, 127, 49, 0.6)';
const red = 'rgba(229, 57, 53, 1)';
const hoverRed = 'rgba(229, 57, 53, 0.6)';
const transparent = 'rgba(255,255,255, 0)';

function getWeekData() {
    const url = 'http://tempmon.ro/monitor/api/week_graphic.php';
    const graphLastWeek = document.querySelector('.week-axis');
    const checkForElement = document.querySelectorAll('.week-axis .tick');
    const sensorid = document.getElementById('sensorid').innerText;
    const storeid = document.getElementById('storeid').innerText;
    if (checkForElement) {
        checkForElement.forEach(e => e.parentNode.removeChild(e));
    }

    let weekDays = [];
    let lastWeekNumberArr = [...new Array(7)].map((i, idx) => {
        return moment().startOf("day").subtract(idx, "days").format("D");
    })
    lastWeekNumberArr = [...lastWeekNumberArr].map((val, index) => {
        return lastWeekNumberArr[lastWeekNumberArr.length - index - 1];
    }).map((val, idx) => {
        weekDays[idx] = { current_temp: null, day: val, offline: 1 };
        return val;
    });
    // for (let i = 0; i < 7; i++) {

    //     weekDaysArr['length'] = 7;
    // }
    fetch(url, {
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            "sensor_id": parseInt(sensorid),
            "location_id": parseInt(storeid)
        })
    })
    .then(response => {
        return response.json();
    })
    .then(data => {
        // console.log(data.body)
        if (data.count != 0) {
        //     return weekDays;
        // } else {

                // data.body.map((v, index) => {
                //     weekDays[v.day].current_temp = parseInt(v.current_temp);
                //     weekDays[v.day].offline = 0;
                //     return weekDays;
                // });
                // let tempArr = data.body.map((i, idx) => {
                //     return data.body[idx].day;
                // })

                // let weekDaysArr = weekDays.map((i, idx) => {
                //     return weekDays[idx].day;
                // })

                let tempArr = data.body.map((i, idx) => {
                    return data.body[idx].day;
                })
                let current_temp = data.body.map((i, idx) => {
                    return data.body[idx].current_temp;
                })
                let weekDaysArr = weekDays.map((i, idx) => {
                    return weekDays[idx].day;
                })


                weekDaysArr.filter((day, idx) => {
                    if (tempArr.includes(day)) {
                        if (weekDays[idx].day == day) {
                            weekDays[idx].offline = 0;
                            weekDays[idx].current_temp = [...current_temp];
                            return weekDays;
                        }
                    }
                });
                let x = weekDaysArr.map((day, idx) => {
                    let tempArrIdx = tempArr.indexOf(day);
                    if (tempArrIdx < 0) return day;
                    let y = weekDays[idx];
                    y.offline = 0;
                    y.current_temp = current_temp[tempArrIdx];
                    return y;
                });

                // weekDaysArr.filter((day, idx) => {
                //     if (!tempArr.includes(day)) {
                //         if (weekDays[idx].day == day) {
                //             weekDays[idx].offline = 0;
                //             weekDays[idx].current_temp = null;
                //             return weekDays;
                //         } else {
                //             weekDays[idx].offline = 1;
                //             weekDays[idx].current_temp = data.body[idx].current_temp;
                //             return weekDays;
                //         }
                //     }
                // });
            }
            // Array of values
            let weekArr = weekDays.map((i, idx) => {
                return weekDays[idx].current_temp;
            })
                // weekArr = [...weekArr].map((val, index) => {
                //     return weekArr[weekArr.length - index - 1];
                // })
                .map((val, idx) => {
                    // Create elements for values

                    let tick = document.createElement('div');
                    tick.className = 'tick'
                    let weekValues = document.createElement('span');
                    weekValues.classList.add('value', 'week-value--this');
                    weekValues.innerHTML = val;
                    tick.appendChild(weekValues);
                    graphLastWeek.appendChild(tick);
                    // Color values

                    if (parseInt(val) > parseInt(tempMax.value) || parseInt(val) < parseInt(tempMin.value)) {
                        weekColor[idx] = red;
                        weekHoverColor[idx] = hoverRed;
                    } else {
                        weekColor[idx] = green;
                        weekHoverColor[idx] = green;
                    }
                    return val;
                });
            // Week days
            let lastWeek = [...new Array(weekArr.length)].map((i, idx) => {
                return moment().startOf("day").subtract(idx, "days").format("ddd");
            })
            lastWeek = [...lastWeek].map((val, index) => {
                return lastWeek[lastWeek.length - index - 1];
            }).map((val, idx) => {
                // Create elements for days
                let tick = document.querySelectorAll('.week-axis .tick');
                let weekName = document.createElement('span');
                weekName.className = 'week-name';
                weekName.innerHTML = val;
                tick[idx].appendChild(weekName);
                return val;
            });

            // Week days number
            let lastWeekNumber = [...new Array(weekArr.length)].map((i, idx) => {
                return moment().startOf("day").subtract(idx, "days").format("D");
            })
            lastWeekNumber = [...lastWeekNumber].map((val, index) => {
                return lastWeekNumber[lastWeekNumber.length - index - 1];
            }).map((val, idx) => {
                // Create elements for days
                let tick = document.querySelectorAll('.week-axis .tick');
                let weekNumber = document.createElement('span');
                weekNumber.className = 'week-number';
                weekNumber.innerHTML = val;
                tick[idx].appendChild(weekNumber);
                return val;
            });

            let weekConfig = {
                type: 'line',
                data: {
                    labels: lastWeek,
                    datasets: [{
                        label: 'Temperature',
                        data: weekArr,
                        fill: false,
                        borderColor: green,
                        borderWidth: 2,
                        pointBackgroundColor: weekColor,
                        pointBorderColor: '#eee',
                        pointBorderWidth: 3,
                        pointHoverBorderColor: weekHoverColor,
                        pointHoverBorderWidth: 10,
                        lineTension: 0,
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    layout: {
                        padding: {
                            left: 15,
                            right: 15,
                            top: 40,
                            bottom: 15
                        }
                    },
                    elements: {
                        point: {
                            radius: 6,
                            hitRadius: 6,
                            hoverRadius: 6
                        }
                    },
                    legend: {
                        display: false,
                    },
                    tooltips: {
                        backgroundColor: green,
                        displayColors: true,
                        bodyFontSize: 14,
                        callbacks: {
                            label: function (tooltipItems, data) {
                                return tooltipItems.yLabel + '°C';
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            display: false,
                        }],
                        yAxes: [{
                            display: false,
                            ticks: {
                                suggestedMax: tempMax.value,
                                suggestedMin: -15,
                                stepSize: 0.5,
                                precision: 1,
                                beginAtZero: false
                            },
                        }]
                    }
                },
                plugins: [multiply],
            };

            window.chart = new Chart(weekCanvas, weekConfig);
        })
    // .catch(function (err) {
    //     console.log(err);
    // });
}

function getTodayData() {
    const url = 'http://tempmon.ro/monitor/api/today_graphic.php';
    const graphToday = document.querySelector('.day-axis');
    const checkForElement = document.querySelectorAll('.day-axis .tick');
    const currentHour = moment().format('H');
    const sensorid = document.getElementById('sensorid').innerText;
    const storeid = document.getElementById('storeid').innerText;

    if (checkForElement) {
        checkForElement.forEach(e => e.parentNode.removeChild(e));
    }

    let hours = [];
    for (let i = 0; i < 24; i++) {
        hours[i] = { current_temp: null, hour: `${i}`, offline: 1 };
        hours['length'] = 24;
    }

    fetch(url, {
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            "sensor_id": parseInt(sensorid),
            "location_id": parseInt(storeid)
        })
    })
    .then(response => {
        return response.json();
    })
    .then(data => {
       
                if (data.count != 0) {
                

                let tempArr = data.body.map((i, idx) => {
                    return data.body[idx].hour;
                })
                let current_temp = data.body.map((i, idx) => {
                    return data.body[idx].current_temp;
                })
                let hoursArr = hours.map((i, idx) => {
                    return hours[idx].hour;
                })
                
                hoursArr.filter((hour, idx) => {
                    if (tempArr.includes(hour)) {
                        if (hours[idx].hour == hour) {
                            hours[idx].offline = 0;
                            hours[idx].current_temp = [...current_temp];
                            return hours;
                        }
                    }
                });
                

                let x = hoursArr.map((hour, idx) => {
                    let tempArrIdx = tempArr.indexOf(hour);
                    if (tempArrIdx < 0) return hour;
                    let y = hours[idx];
                    y.offline = 0;
                    y.current_temp = current_temp[tempArrIdx];
                    return y;
                });

            }

            // populezi graficul cu datele disponibile din obiectul de hours
            let todayArr = hours.map((i, idx) => {
                
                return hours[idx].current_temp;
            })
            .map((val, idx) => {
                    // Create elements for values
                    let tick = document.createElement('div');
                    tick.className = 'tick'
                    let todayValues = document.createElement('span');
                    // todayValues.classList.add('value', 'day-value--this');
                    todayValues.classList.add('value', 'day-this--value');
                    todayValues.innerHTML = val;
                    tick.appendChild(todayValues);
                    graphToday.appendChild(tick);
                    // Color values

                    if (parseInt(val) > parseInt(tempMax.value) || parseInt(val) < parseInt(tempMin.value)) {
                        dayColor[idx] = red;
                        dayHoverColor[idx] = hoverRed;
                        graphLine[idx] = red;
                        graphBorderLine[idx] = red;
                    } else {
                        dayColor[idx] = green;
                        dayHoverColor[idx] = green;
                        graphLine[idx] = green;
                        graphBorderLine[idx] = green;
                    }
                    return val;
                });

            // Today hours

            let today = [...new Array(todayArr.length)].map((i, idx) => {
                let items = [];
                new Array(24).fill().forEach((val, index) => {
                    items.push(moment({ hour: index }).format('HH'));
                })
                return items;
            }).map((val, idx) => {
                // Create elements for days
                let tick = document.querySelectorAll('.day-axis .tick');
                let todayName = document.createElement('span');
                todayName.className = 'day-number';
                todayName.innerHTML = val[idx];
                tick[idx].appendChild(todayName);
                return val[idx] + ':00';
            });
            // Today minutes
            let todayNumber = [...new Array(hours.length)].map((i, idx) => {
                return moment().startOf("day").subtract(idx, "days").format("mm");
            })
            todayNumber = [...todayNumber].map((val, index) => {
                return todayNumber[todayNumber.length - index - 1];
            }).map((val, idx) => {
                // Create elements for days
                let tick = document.querySelectorAll('.day-axis .tick');
                let todayNumber = document.createElement('span');
                todayNumber.className = 'day-name';
                todayNumber.innerHTML = val;
                tick[idx].appendChild(todayNumber);
                return val;
            });

            var hourConfig = {
                type: 'line',
                data: {
                    labels: today,
                    datasets: [{
                        label: 'Temperature',
                        data: todayArr,
                        fill: false,
                        borderColor: green,
                        borderWidth: 2,
                        pointBackgroundColor: dayColor,
                        pointBorderColor: '#eee',
                        pointBorderWidth: 3,
                        pointHoverBorderColor: dayHoverColor,
                        pointHoverBorderWidth: 10,
                        lineTension: 0,
                        spanGaps: false,
                    }]
                },
                options: {
                    spanGaps: true,
                    maintainAspectRatio: false,
                    responsive: true,
                    layout: {
                        padding: {
                            left: 15,
                            right: 15,
                            top: 5,
                            bottom: 50
                        }
                    },
                    elements: {
                        point: {
                            radius: 6,
                            hitRadius: 6,
                            hoverRadius: 6
                        }
                    },
                    legend: {
                        display: false,
                    },
                    tooltips: {
                        backgroundColor: green,
                        displayColors: true,
                        bodyFontSize: 14,
                        callbacks: {
                            label: function (tooltipItems, data) {
                                return tooltipItems.yLabel + '°C';
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            display: false,
                        }],
                        yAxes: [{
                            display: false,
                            ticks: {
                                suggestedMax: parseInt(tempMax.value) + 20,
                                suggestedMin: -15,
                                stepSize: 0.5,
                                precision: 1,
                                beginAtZero: false
                            },
                        }]
                    }
                },
                plugins: [multiply],
            };

            window.chart = new Chart(dayCanvas, hourConfig);

        })
    // console.log(data.count)
    // let todayArr;
    // if(data.count == 0) {
    //     data.count = 24;
    //     var objects = [];

    //     for (var x = 0; x < 24; x++) {
    //         objects.push({
    //             current_temp: 0,
    //             hour: x,
    //             offline: true
    //         });
    //     }
    //     todayArr = objects.map((i, idx) => {
    //         return objects[idx].current_temp;
    //     })
    // }else {
    //      todayArr = data.body.map((i, idx) => {
    //         return data.body[idx].current_temp;
    //     })
    // }
    // Array of values

    //     
}

function getMonthData() {
    const url = 'http://tempmon.ro/monitor/api/month_graphic.php';
    const graphLastMonth = document.querySelector('.month-axis');
    const checkForElement = document.querySelectorAll('.month-axis .tick');
    const sensorid = document.getElementById('sensorid').innerText;
    const storeid = document.getElementById('storeid').innerText;
    if (checkForElement) {
        checkForElement.forEach(e => e.parentNode.removeChild(e));
    }

    let monthDays = [];
    let lastThirtyDays = [...new Array(30)].map((i, idx) => {
        return moment().startOf("day").subtract(idx, "days").format("D");
    })
    lastThirtyDays = [...lastThirtyDays].map((val, index) => {
        return lastThirtyDays[lastThirtyDays.length - index - 1];
    }).map((val, idx) => {
        monthDays[idx] = { current_temp: null, day: val, offline: 1 };
        return val;
    });


    fetch(url, {
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
         "sensor_id": parseInt(sensorid),
         "location_id": parseInt(storeid)
     })
    })
    .then(response => {
        return response.json();
    })
    .then(data => {
        // console.log(data.body)

        if (data.count != 0) {
        //     return monthDays;
        // } else {
            let tempArr = data.body.map((i, idx) => {
                return data.body[idx].day;
            })
            let current_temp = data.body.map((i, idx) => {
                return data.body[idx].current_temp;
            })
            let monthDaysArr = monthDays.map((i, idx) => {
                return monthDays[idx].day;
            })
            monthDaysArr.filter((day, idx) => {
                if (tempArr.includes(day)) {
                    if (monthDays[idx].day == day) {
                        monthDays[idx].offline = 0;
                        monthDays[idx].current_temp = [...current_temp];
                        return monthDays;
                    }
                }
            });
            let x = monthDaysArr.map((day, idx) => {
                let tempArrIdx = tempArr.indexOf(day);
                if (tempArrIdx < 0) return day;
                let y = monthDays[idx];
                y.offline = 0;
                y.current_temp = current_temp[tempArrIdx];
                return y;
            });
        }
            // Array of values
            let monthArr = monthDays.map((i, idx) => {
                return monthDays[idx].current_temp;
            })
            .map((val, idx) => {
                    // Create elements for values

                    let tick = document.createElement('div');
                    tick.className = 'tick'
                    let monthValues = document.createElement('span');
                    monthValues.classList.add('value', 'month-value--this');
                    monthValues.innerHTML = val;
                    tick.appendChild(monthValues);
                    graphLastMonth.appendChild(tick);
                    // Color values

                    if (parseInt(val) > parseInt(tempMax.value) || parseInt(val) < parseInt(tempMin.value)) {
                        monthColor[idx] = red;
                        monthHoverColor[idx] = hoverRed;
                    } else {
                        monthColor[idx] = green;
                        monthHoverColor[idx] = green;
                    }
                    return val;
                });
            // Month days
            let lastMonth = [...new Array(monthArr.length)].map((i, idx) => {
                return moment().startOf("day").subtract(idx, "days").format("DD");
            })
            lastMonth = [...lastMonth].map((val, index) => {
                return lastMonth[lastMonth.length - index - 1];
            }).map((val, idx) => {
                // Create elements for days
                let tick = document.querySelectorAll('.month-axis .tick');
                let monthName = document.createElement('span');
                monthName.className = 'month-name';
                monthName.innerHTML = val;
                tick[idx].appendChild(monthName);
                return val;
            });

            // Month days number
            let lastMonthNumber = [...new Array(monthArr.length)].map((i, idx) => {
                return moment().startOf("day").subtract(idx, "days").format("MMM");
            })
            lastMonthNumber = [...lastMonthNumber].map((val, index) => {
                return lastMonthNumber[lastMonthNumber.length - index - 1];
            }).map((val, idx) => {
                // Create elements for days
                let tick = document.querySelectorAll('.month-axis .tick');
                let monthNumber = document.createElement('span');
                monthNumber.className = 'month-number';
                monthNumber.innerHTML = val;
                tick[idx].appendChild(monthNumber);
                return val;
            });

            let monthConfig = {
                type: 'line',
                data: {
                    labels: lastMonth,
                    datasets: [{
                        label: 'Temperature',
                        data: monthArr,
                        fill: false,
                        borderColor: green,
                        borderWidth: 2,
                        pointBackgroundColor: monthColor,
                        pointBorderColor: '#eee',
                        pointBorderWidth: 3,
                        pointHoverBorderColor: monthHoverColor,
                        pointHoverBorderWidth: 10,
                        lineTension: 0,
                        spanGaps: false,
                    }]
                },
                options: {
                    spanGaps: true,
                    maintainAspectRatio: false,
                    responsive: true,
                    layout: {
                        padding: {
                            left: 15,
                            right: 15,
                            top: 5,
                            bottom: 50
                        }
                    },
                    elements: {
                        point: {
                            radius: 6,
                            hitRadius: 6,
                            hoverRadius: 6
                        }
                    },
                    legend: {
                        display: false,
                    },
                    tooltips: {
                        backgroundColor: green,
                        displayColors: true,
                        bodyFontSize: 14,
                        callbacks: {
                            label: function (tooltipItems, data) {
                                return tooltipItems.yLabel + '°C';
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            display: false,
                        }],
                        yAxes: [{
                            display: false,
                            ticks: {
                                suggestedMax: parseInt(tempMax.value) + 10,
                                suggestedMin: -15,
                                stepSize: 0.5,
                                precision: 1,
                                beginAtZero: false
                            },
                        }]
                    }
                },
                plugins: [multiply],
            };

            window.chart = new Chart(monthCanvas, monthConfig);
        })
    // .catch(function (err) {
    //     console.log(err);
    // });
}

window.addEventListener('load', function () {
    if (canvas.length > 0) {
        getTodayData();
        getWeekData();
        getMonthData();
    }
})

setInterval(() => {
    if (canvas.length > 0) {
        getTodayData();
        getWeekData();
        getMonthData();
    }
}, 30000);

// fetch(url, { method: 'POST', sensor_id: 3, location_id: 0 })
// .then(function (response) {
//     return response.text();
// })
// .then(function (body) {
//     console.log(body);
// });

if (dayCanvas) {
    // for (i = 0; i < 7; i++) {

    //     days.push(moment(weekStart.textContent).add(i, 'days').format("D"));
    //     weekDays.push(moment(weekStart.textContent).add(i, 'days').format("ddd"));
    //     daysContainer[i].textContent = days[i];
    //     weekDaysContainer[i].textContent = weekDays[i];
    //     weekTemp[i] = tempContainer[i].textContent;
    //     if(parseInt(weekTemp[i]) > parseInt(tempMax.value) || parseInt(weekTemp[i]) < parseInt(tempMin.value)) {

    //         weekColor[i] = red;
    //         weekHoverColor[i] = hoverRed;
    //     }else {
    //         weekColor[i] = green;
    //         weekHoverColor[i] = green;
    //     }
    // };

    // for (let i = 0; i < 24; i++) {

    //     hour.push(moment(dayStart).add(i, 'hours').format("HH"));
    //     hoursDay.push(moment(dayStart).add(i, 'hours').format("HH"));
    //     hourContainer[i].textContent = hour[i];
    //     hoursDayContainer[i].textContent = hoursDay[i];
    //     hourTemp[i] = hourTempContainer[i].textContent;

    //     if(parseInt(hourTemp[i]) > parseInt(tempMax.value) || parseInt(hourTemp[i]) < parseInt(tempMin.value)) {
    //         dayColor[i] = red;
    //         dayHoverColor[i] = hoverRed;
    //     }else {
    //         dayColor[i] = green;
    //         dayHoverColor[i] = green;
    //     }

    // };

    // let lastThirtyDays = [...new Array(30)].map((i, idx) => {
    //     return moment().startOf("day").subtract(idx, "days").format("DD");
    // })
    // lastThirtyDays = [...lastThirtyDays].map((val, index) => {  
    //     return lastThirtyDays[lastThirtyDays.length - index - 1];
    // }).map((val, idx) => {
    //     monthNumberContainer[idx].innerHTML = val;
    //     return val;
    // });
    // let monthNumber = [...new Array(30)].map((i, idx) => {
    //     return moment().startOf("day").subtract(idx, "days").format("MMM");
    // })
    // monthNumber = [...monthNumber].map((val, index) => {
    //     return monthNumber[monthNumber.length - index - 1];
    // }).map((val, idx) => {
    //     month[idx] = val;
    //     monthDayContainer[idx].innerHTML = val;
    //     return val;
    // });



    // let monthNumberTemp = [...monthTempContainer].map(e=>e.innerText);

    // monthNumberTemp = [...monthNumberTemp].map((val, index) => {
    //     return monthNumberTemp[monthNumberTemp.length - index - 1];
    // }).map((val, idx) => {

    //     monthTemp[idx] = val;
    //     console.log(monthTemp[idx]);
    //     if(parseInt(monthTemp[idx]) > parseInt(tempMax.value) || parseInt(monthTemp[idx]) < parseInt(tempMin.value)) {
    //         monthColor[idx] = red;
    //         monthHoverColor[idx] = hoverRed;
    //     }else {
    //         monthColor[idx] = green;
    //         monthHoverColor[idx] = green;
    //     }

    //     return val;
    // });


    // function getDaysArrayByMonth() {

    //     var daysInMonth = moment().daysInMonth();
    //     var arrDays = [];

    //     while(daysInMonth) {
    //         var current = moment().date(daysInMonth);
    //         arrDays.push(current);
    //         daysInMonth--;
    //     }

    //   return arrDays;
    // }
    // var schedule = getDaysArrayByMonth();


    // schedule.forEach(function(item) {
    //     month.push(item.format("DD"));
    //     monthDay.push(item.format("MMM"));
    //     // monthDayContainer[i].textContent = item.format("DD");
    //     // monthNumberArray[i].textContent = item.format("MMM")
    //     //console.log(month[i]);
    // });
    // month.reverse();
    // for (var i = month.length - 1, j = 0; j < month.length; i--, j++) {
    //     monthDayContainer[i].textContent = month[i];
    //     monthNumberContainer[i].textContent = monthDay[i];
    //     monthTemp[i] = monthTempContainer[i].textContent;
    //     if(parseInt(monthTemp[i]) > parseInt(tempMax.value) || parseInt(monthTemp[i]) < parseInt(tempMin.value)) {
    //         monthColor[i] = red;
    //         monthHoverColor[i] = hoverRed;
    //     }else {
    //         monthColor[i] = green;
    //         monthHoverColor[i] = green;
    //     }
    // }



    // var weekConfig = {
    //     type: 'line',
    //     data: {
    //         labels: weekDays,
    //         datasets: [{
    //             label: 'Temperature',
    //             data: weekTemp,
    //             fill: false,
    //             borderColor: green,
    //             borderWidth: 2,
    //             pointBackgroundColor: weekColor,
    //             pointBorderColor: '#eee',
    //             pointBorderWidth: 3,
    //             pointHoverBorderColor: weekHoverColor,
    //             pointHoverBorderWidth: 10,
    //             lineTension: 0,
    //         }]
    //     },
    //     options: {
    //         maintainAspectRatio: false,
    //         responsive: true,
    //         layout: {
    //             padding: {
    //                 left: 15,
    //                 right: 15,
    //                 top: 40,
    //                 bottom: 15
    //             }
    //         },
    //         elements: {
    //             point: {
    //                 radius: 6,
    //                 hitRadius: 6,
    //                 hoverRadius: 6
    //             }
    //         },
    //         legend: {
    //             display: false,
    //         },
    //         tooltips: {
    //             backgroundColor: green,
    //             displayColors: true,
    //             bodyFontSize: 14,
    //             callbacks: {
    //                 label: function(tooltipItems, data) {
    //                     return tooltipItems.yLabel + '°C';
    //                 }
    //             }
    //         },
    //         scales: {
    //             xAxes: [{
    //                 display: false,
    //             }],
    //             yAxes: [{
    //                 display: false,
    //                 ticks: {
    //                     suggestedMax: tempMax.value,
    //                     suggestedMin: -15,
    //                     stepSize: 0.5,
    //                     precision: 1,
    //                     beginAtZero: false
    //                 },
    //             }]
    //         }
    //     },
    //     plugins: [multiply],
    // };
    // var hourConfig = {
    //     type: 'line',
    //     data: {
    //         labels: hoursDay,
    //         datasets: [{
    //             label: 'Temperature',
    //             data: hourTemp,

    //             fill: false,
    //             borderColor: green,
    //             borderWidth: 2,
    //             pointBackgroundColor: dayColor,
    //             pointBorderColor: '#eee',
    //             pointBorderWidth: 3,
    //             pointHoverBorderColor: dayHoverColor,
    //             pointHoverBorderWidth: 10,
    //             lineTension: 0,
    //         }]
    //     },
    //     options: {
    //         spanGaps: true,
    //         maintainAspectRatio: false,
    //         responsive: true,
    //         layout: {
    //             padding: {
    //                 left: 15,
    //                 right: 15,
    //                 top: 5,
    //                 bottom: 50
    //             }
    //         },
    //         elements: {
    //             point: {
    //                 radius: 6,
    //                 hitRadius: 6,
    //                 hoverRadius: 6
    //             }
    //         },
    //         legend: {
    //             display: false,
    //         },
    //         tooltips: {
    //             backgroundColor: green,
    //             displayColors: true,
    //             bodyFontSize: 14,
    //             callbacks: {
    //                 label: function(tooltipItems, data) {
    //                     return tooltipItems.yLabel + '°C';
    //                 }
    //             }
    //         },
    //         scales: {
    //             xAxes: [{
    //                 display: false,
    //             }],
    //             yAxes: [{
    //                 display: false,
    //                 ticks: {
    //                     suggestedMax: tempMax.value,
    //                     suggestedMin: -15,
    //                     stepSize: 0.5,
    //                     precision: 1,
    //                     beginAtZero: false
    //                 },
    //             }]
    //         }
    //     },
    //     plugins: [multiply],
    // };
    // var monthConfig = {
    //     type: 'line',
    //     data: {
    //         labels: month,
    //         datasets: [{
    //             label: 'Temperature',
    //             data: monthNumberTemp,
    //             fill: false,
    //             borderColor: green,
    //             borderWidth: 2,
    //             pointBackgroundColor: monthColor,
    //             pointBorderColor: '#eee',
    //             pointBorderWidth: 3,
    //             pointHoverBorderColor: monthHoverColor,
    //             pointHoverBorderWidth: 10,
    //             lineTension: 0,
    //         }]
    //     },
    //     options: {
    //         spanGaps: true,
    //         maintainAspectRatio: false,
    //         responsive: true,
    //         layout: {
    //             padding: {
    //                 left: 15,
    //                 right: 15,
    //                 top: 5,
    //                 bottom: 50
    //             }
    //         },
    //         elements: {
    //             point: {
    //                 radius: 6,
    //                 hitRadius: 6,
    //                 hoverRadius: 6
    //             }
    //         },
    //         legend: {
    //             display: false,
    //         },
    //         tooltips: {
    //             backgroundColor: green,
    //             displayColors: true,
    //             bodyFontSize: 14,
    //             callbacks: {
    //                 label: function(tooltipItems, data) {
    //                     return tooltipItems.yLabel + '°C';
    //                 }
    //             }
    //         },
    //         scales: {
    //             xAxes: [{
    //                 display: false,
    //             }],
    //             yAxes: [{
    //                 display: false,
    //                 ticks: {
    //                     suggestedMax: parseInt(tempMax.value) + 10,
    //                     suggestedMin: -15,
    //                     stepSize: 0.5,
    //                     precision: 1,
    //                     beginAtZero: false
    //                 },
    //             }]
    //         }
    //     },
    //     plugins: [multiply],
    // };

    // 

    // 
}
