
//  Change color of active nav link
let navinc = document.getElementById('overview');
navinc.className = 'w3-bar-item w3-padding w3-active-blue';


// Asset Donut chart

let chrt = document.getElementById("donut").getContext("2d");
Chart.defaults.font.family = "sans-serif";
Chart.defaults.font.size = 13;
Chart.defaults.color = "white";

let chartId = new Chart(chrt, {
  type: 'doughnut',
  data: {
    labels: catname,
    datasets: [{
      label: '',
      data: assetamt,
      backgroundColor: [
        'rgba(60, 157, 78, 0.2)',
        'rgba(65, 116, 201, 0.2)',
        'rgba(254, 174, 101, 0.2)',
        'rgba(230, 246, 157, 0.2)',
        'rgba(170, 222, 167, 0.2)',
        'rgba(100, 194, 166, 0.2)',
        'rgba(45, 135, 187, 0.2)',
        'rgba(255, 236, 33, 0.2)',
        'rgba(55, 138, 255, 0.2)',
        'rgba(255, 163, 47, 0.2)',
        'rgba(245, 79, 82, 0.2)',
        'rgba(147, 240, 59, 0.2)',
        'rgba(149, 82, 234, 0.2)',
        'rgba(82, 215, 38, 0.2)',
        'rgba(255, 236, 0, 0.2)',
        'rgba(255, 115, 0, 0.2)',
        'rgba(255, 0, 0, 0.2)',
        'rgba(0, 126, 214, 0.2)',
        'rgba(124, 221, 221, 0.2)'
      ],
      borderColor: [
        'rgb(60, 157, 78)',
        'rgb(65, 116, 201)',
        'rgb(254, 174, 101)',
        'rgb(230, 246, 157)',
        'rgb(170, 222, 167)',
        'rgb(100, 194, 166)',
        'rgb(45, 135, 187)',
        'rgb(255, 236, 33)',
        'rgb(55, 138, 255)',
        'rgb(255, 163, 47)',
        'rgb(245, 79, 82)',
        'rgb(147, 240, 59)',
        'rgb(149, 82, 234)',
        'rgb(82, 215, 38)',
        'rgb(255, 236, 0)',
        'rgb(255, 115, 0)',
        'rgb(255, 0, 0)',
        'rgb(0, 126, 214)',
        'rgb(124, 221, 221)'
      ],
      borderWidth: 1,
      hoverOffset: 5,
    }],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    layout: {
      padding: {
        bottom: 10,
        top: 10,
        left: 5,
        right: 5,
      }
    },
    plugins: {
      legend: {
        fullSize: true,
        position: 'left',
        textAlign: 'left'
      },
      title: {
        display: false,
        text: 'Monthly Expenses',
        position: 'top',
        align: 'end',
        padding: {
          top: 5,
          bottom: 5,
        },
        fullSize: true,
        font: {
          weight: 'bold',
          size: 15
        },

      }
    }
  },
});


// Income Expense Chart

// Copy properties of all arrays into labelsAll
let labelsAll = [];
labelsAll = [].concat(incamount, expamount, expcredit);

// *********** KEEP the code below this line in place for correct operation *************
// Remove object incamt, cashflow, and creditxp from labelsAll
let labels = labelsAll.map(({ incamt, ...item }) => item);
let labels2 = labels.map(({ cashflow, ...item }) => item);
let labels3 = labels2.map(({ creditexp, ...item }) => item);
// Remove duplicate values
function getUniqueListBy(item, key) {
  return [...new Map(item.map(item => [item[key], item])).values()]
}

// Sort years
labels3.sort(function(a, b) {
  return new Date(a.year) - new Date(b.year);
});
// Remove duplicate months
let labels4 = getUniqueListBy(labels3, 'monthlist');

// Remove years key and values
const labelsRev = labels4.map(({ year, ...item }) => item);
// Remove monthlist key but leave values and create final labels
const labelsFin = [];
for (i of labelsRev) {
  labelsFin.push(...Object.values(i))
}

// Insert new object keys
const incamtlbls = labels3.map(v => ({ ...v, incamt: "0" }));
const expamtlbls = labels3.map(v => ({ ...v, cashflow: "0" }));
const expcredlbls = labels3.map(v => ({ ...v, creditexp: "0" }));

// INCOME --------------------------------------------
// Sort by years
incamtlbls.sort((a, b) => a.year - b.year);
// Copy and merge incamtlbls and create new array
let incamtlblsNew = incamtlbls.concat(incamount);
// Remove duplicate months from incamtlblsNew
let inclblsRev = getUniqueListBy(incamtlblsNew, 'monthlist');
// Sort by year date
inclblsRev.sort(function(a, b) {
  return new Date(a.year) - new Date(b.year);
});
// Remove year from inclbls
const inclbls = inclblsRev.map(({ year, ...item }) => item);
// Remove key "monthlist:" from inclbls
for (var i = 0, len = inclbls.length; i < len; i++) {
  delete inclbls[i].monthlist;
}
// Remove key "incamt:" from inclbls but leave values
// Dump object array into incAmtsFin array and feed it to chartjs
const incAmtsFin = [];
for (i of inclbls) {
  incAmtsFin.push(...Object.values(i))
}

// EXPENSE ------------------------------------------
// Sort by years
expamtlbls.sort((a, b) => a.year - b.year);
// Copy and merge expamtlbls and create new array
let expamtlblsNew = expamtlbls.concat(expamount);
// Remove duplicate months from expamtlblsNew
let explblsRev = getUniqueListBy(expamtlblsNew, 'monthlist');
// Sort by year date
explblsRev.sort(function(a, b) {
  return new Date(a.year) - new Date(b.year);
});
// Remove year from explbls
const explbls = explblsRev.map(({ year, ...item }) => item);
// Remove key "monthlist:" from explbls
for (var i = 0, len = explbls.length; i < len; i++) {
  delete explbls[i].monthlist;
}
// Remove key "cashflow:" from explbls but leave values
// Dump object array into incAmtsFin array and feed it to chartjs
const expAmtsFin = [];
for (i of explbls) {
  expAmtsFin.push(...Object.values(i))
}

// CREDIT --------------------------------------------
// Sort by years
expcredlbls.sort((a, b) => a.year - b.year);
let expcredlblsNew = expcredlbls.concat(expcredit);
// Remove duplicate months from expcredlblsNew
let crdlblsRev = getUniqueListBy(expcredlblsNew, 'monthlist');
// Sort by year date
crdlblsRev.sort(function(a, b) {
  return new Date(a.year) - new Date(b.year);
});
// Remove year from crdlbls
const crdlbls = crdlblsRev.map(({ year, ...item }) => item);
// Remove key "monthlist:" from crdlbls
for (var i = 0, len = crdlbls.length; i < len; i++) {
  delete crdlbls[i].monthlist;
}
// Remove key "creditexp:" from crdlbls but leave values
// Dump object array into incAmtsFin array and feed it to chartjs
const crdAmtsFin = [];
for (i of crdlbls) {
  crdAmtsFin.push(...Object.values(i))
}

Chart.defaults.font.family = "sans-serif";
Chart.defaults.font.size = 13;
Chart.defaults.color = "white";
const data = {
  labels: labelsFin,
  datasets: [{
    label: 'Inflow',
    data: incAmtsFin,
    backgroundColor: [
      'rgba(100, 194, 166, 0.3)'
    ],
    borderColor: [
      'rgb(100, 194, 166)'
    ],
    borderWidth: 1,
  },
  {
    label: 'Outflow',
    data: expAmtsFin,
    backgroundColor: [
      'rgba(246,109, 68, 0.3)'
    ],
    borderColor: [
      'rgb(246,109, 68)'
    ],
    borderWidth: 1
  },
  {
    label: 'Credit',
    data: crdAmtsFin,
    backgroundColor: [
      'rgba(45, 135, 187, 0.3)'
    ],
    borderColor: [
      'rgb(45, 135, 187)'
    ],
    borderWidth: 1,
  }
  ]
};
const config = {
  type: 'bar',
  data: data,
  options: {
    plugins: {
      legend: {
        display: true,
        position: 'top',
      },
    },
    scales: {
      y: {
        position: 'right',
        grid: {
          display: true,
          color: "rgba(128,128,128,0.4)"

        }
      },
      x: {
        grid: {
          display: false,
          color: "rgba(11, 11, 11, 0.3)"

        }
      }
    },
    responsive: true,
    layout: {
      padding: {
        bottom: 5,
        top: 5,
        left: 5,
        right: 5,
      }
    },
  }
};
let myChart = new Chart(
  document.getElementById('incChart'),
  config
);
