
//  Change color of active nav link
let navasst = document.getElementById('reports');
navasst.className = 'w3-bar-item w3-padding w3-active-blue';


// Gross Income/Expense/Cash Chart

// Buggy shadow line hack
// class Custom extends Chart.LineController {
//   draw() {
//     // Call the line controler method to draw points
//     super.draw(arguments);
//
//     const ctx = this.chart.ctx;
//     const _stroke = ctx.stroke;
//     ctx.stroke = function() {
//       ctx.save();
//       ctx.shadowColor = 'black';
//       ctx.shadowBlur = 10;
//       ctx.shadowOffsetX = 0;
//       ctx.shadowOffsetY = 4;
//       _stroke.apply(this, arguments);
//       ctx.restore();
//     }
//   }
// };
//
// Custom.id = 'shadowLine';
// Custom.defaults = Chart.LineController.defaults;
//
// Chart.register(Custom);

const chart1 = document.getElementById("netIncome");
Chart.defaults.font.family = "sans-serif";
Chart.defaults.font.size = 13;
Chart.defaults.color = "white";

// Construct empty year labels array variable
let yearlbls = [];

// Copy properties of all arrays into yearslbls
yearlbls = [].concat(grossinc, grossexp, repay);

//
// *********** KEEP the code below this line in place for correct operation *************
// Remove object grossinc, grossexp, and repay from yearlbls
let years = yearlbls.map(({grossinc, ...item}) => item);
let years2 = years.map(({grossexp, ...item}) => item);
let years3 = years2.map(({repay, ...item}) => item);
// Remove duplicate years from yearlbls
function getUniqueListBy(years3, key) {
      return [...new Map(years3.map(item => [item[key], item])).values()]
  }
  // Sort years
  years3.sort((a, b) => a.year - b.year);

// Insert new object keys
const incnewyrslbl = years3.map(v => ({...v, grossinc: "0"}));
const expnewyrslbl = years3.map(v => ({...v, grossexp: "0"}));
const repnewyrslbl = years3.map(v => ({...v, repay: "0"}));

// Copy and merge years3 and create new arrays
grossinclblsNew = incnewyrslbl.concat(grossinc);
grossexplblsNew = expnewyrslbl.concat(grossexp);
repaylblsNew = repnewyrslbl.concat(repay);


const yearsRev = getUniqueListBy(years3, 'year');

// Remove key "year:" from yearsFin but leave value
const yearsFin = [];
  for (i of yearsRev) {
    yearsFin.push(...Object.values(i))
  }

// Remove duplicate years from grossinclblsNew
let grossinclbls = getUniqueListBy(grossinclblsNew, 'year');

// Remove key "year:" from grossinclbls
for (var i = 0, len = grossinclbls.length; i < len; i++) {
    delete grossinclbls[i].year;
}
// Remove key "grossinc:" from grossinclbls but leave values
// Dump object array into grossinc array and feed it to chartjs
const grossincFin = [];
  for (i of grossinclbls) {
    grossincFin.push(...Object.values(i))
  }
// Remove duplicate years from grossexplblsNew
let grossexplbls = getUniqueListBy(grossexplblsNew, 'year');

// Remove key "year:" from grossexplbls
for (var i = 0, len = grossexplbls.length; i < len; i++) {
    delete grossexplbls[i].year;
}
// Remove key "grossexp:" from grossexplbls but leave values
// Dump object array into grossinc array and feed it to chartjs
const grossexpFin = [];
  for (i of grossexplbls) {
    grossexpFin.push(...Object.values(i))
  }

// Remove duplicate years from replaylblsNew
let repaylbls = getUniqueListBy(repaylblsNew, 'year');

// Remove key "year:" from repaylbls
for (var i = 0, len = repaylbls.length; i < len; i++) {
    delete repaylbls[i].year;
}
// Remove key "repay:" from repaylbls but leave values
// Dump object array into grossinc array and feed it to chartjs
const repayFin = [];
  for (i of repaylbls) {
    repayFin.push(...Object.values(i))
  }

  //
  // console.log(yearsFin);
  // console.log(grossincFin);
  // console.log(grossexplbls);
  // console.log(repaylbls);

const netIncome = new Chart(chart1, {
  type: "bar",
  data: {
    labels: yearsFin,
    datasets: [
      {
        label: "Gross Income",
        data: grossincFin,
        backgroundColor: [
        'rgba(100, 194, 166, 0.3)'
         ],
         borderColor: [
         'rgb(100, 194, 166)'
        ],
        borderWidth: 1,
        stack: 'Stack 0',
      },
        {
          label: "Gross Expense",
          data: grossexpFin,
          backgroundColor: [
            'rgba(246,109, 68, 0.3)'
   	      ],
          borderColor: [
            'rgb(246,109, 68)'
          ],
          borderWidth: 1,
          stack: 'Stack 1',
      },
        {
          type: "line",
          pointStyle: 'cross',
          tension: 0.3,
          label: "Repayments",
          data: repayFin,
          backgroundColor: [
            'rgba(45, 135, 187, 0.3)'
   	      ],
          borderColor: [
            'rgb(45, 135, 187)'
          ],
          borderWidth: 2,
          stack: 'Stack 2',
        },
    ],
  },
  options: {
    responsive: true,
    layout: {
      padding: {
        bottom: 5,
        top: 30,
        left: 5,
        right: 5,
      },
    },
    scales: {
      y: {
        position: 'right',
        stacked: true,
        border: {
          display: false
        },
        grid: {
          color: "rgba(128,128,128,0.4)"

          }
        },
        x: {
          stacked: true,
          border: {
            display: false
          },
          grid: {
            display: false,
            color: ""

            }
          }
    },
    plugins: {
      legend: {
        display: false,
      },
    },
  },
});

// Net Income line chart
const chart2 = document.getElementById("netIncome2");
Chart.defaults.elements.point.hoverRadius = 13;
Chart.defaults.elements.point.radius = 7;

// Calculate Net Income
const anetinc = (netinc, netexp) => {
   const res = [];
   for(let i = 0; i < netinc.length; i++){
      const el = ((netinc[i] || 0) - (netexp[i] || 0));
      res[i] = el;
   };
   return res;
};

const netincamt = anetinc(netinc, netexp);
const netIncome2 = new Chart(chart2, {
  type: "line",
  data: {
    labels: netincYr,
    datasets: [
      {
        label: "Net Income",
        data: netincamt,
        backgroundColor: "rgba(25, 99, 132, 0.3)",
        borderColor: "rgba(16, 177, 7, 0.8)",
        tension: 0.3,
        fill: 'origin'
      },
    ],
  },
  options: {
    responsive: true,
    layout: {
      padding: {
        bottom: 5,
        top: 30,
        left: 5,
        right: 5,
      },
    },
    scales: {
      y: {
        position: 'right',
        border: {
          display: false
        },
        grid: {
          color: "rgba(128,128,128,0.4)"

          }
        },
        x: {
          border: {
            display: false
          },
          grid: {
            color: "rgba(128,128,128,0.4)"

            }
          }
    },
    plugins: {
      legend: {
        display: false,
      },
    },
  },
});

// Onclick show/hide tables
let btn = document.getElementById("showhide");
btn.onclick = function myFunction() {
  let t = document.getElementById("show");
  -1 == t.className.indexOf("w3-show")
    ? (t.className += " w3-show")
    : (t.className = t.className.replace(" w3-show", ""));
}
// Onclick show/hide tables - 2nd Occurrence on same page
let btn2 = document.getElementById("showhide2");
btn2.onclick = function myFunction() {
  let t = document.getElementById("show2");
  -1 == t.className.indexOf("w3-show")
    ? (t.className += " w3-show")
    : (t.className = t.className.replace(" w3-show", ""));
}
// Onclick show/hide tables
let btn3 = document.getElementById("showhide3");
btn3.onclick = function myFunction() {
  let t = document.getElementById("show3");
  -1 == t.className.indexOf("w3-show")
    ? (t.className += " w3-show")
    : (t.className = t.className.replace(" w3-show", ""));
}
// Onclick show/hide tables
let btn4 = document.getElementById("showhide4");
btn4.onclick = function myFunction() {
  let t = document.getElementById("show4");
  -1 == t.className.indexOf("w3-show")
    ? (t.className += " w3-show")
    : (t.className = t.className.replace(" w3-show", ""));
}

// Year selector
document.getElementById("selectyear").addEventListener('change', function() {
  // console.log('Year is: ', this.value);
  let selectYr = this.value;
    $.ajax({
      url:"./action/reportYr.php",
      method: "POST",
      data:{
        yr : selectYr
      },
      success:function(data){
        $("#incExp").html(data)
      }
    })
  })
  // Year selector - 2nd Occurrence for Cash Flow
document.getElementById("selectyear2").addEventListener('change', function() {
  // console.log('Year is: ', this.value);
  let selectYr2 = this.value;
    $.ajax({
      url:"./action/reportYr2.php",
      method: "POST",
      data:{
        yr : selectYr2
      },
      success:function(data){
        $("#cashFl").html(data)
      }
    })
  })
  // Year month dependant selector
  document.getElementById("selyrs").addEventListener('change', function() {
    // console.log('Year is: ', this.value);
    let selectdYr = this.value;
      $.ajax({
        url:"./action/reportYrMn.php",
        method: "POST",
        data:{
          yr : selectdYr
        },
        success:function(data){
          $("#selectMnth").html(data)
        }
      })
    })
    // Year month dependant selector 2
    document.getElementById("selyrs2").addEventListener('change', function() {
      // console.log('Year is: ', this.value);
      let selectdYr2 = this.value;
        $.ajax({
          url:"./action/reportYrMn2.php",
          method: "POST",
          data:{
            yr : selectdYr2
          },
          success:function(data){
            $("#selectMnth2").html(data)
          }
        })
      })
  // Month selector
  document.getElementById("selectMnth").addEventListener('change', function() {
    // console.log('Month is: ', this.value);
      let selectYr = document.getElementById("selyrs");
      let text = selectYr.options[selectYr.selectedIndex].text;
      // console.log('Year is: ', text);
    let selectMn = this.value;
      $.ajax({
        url:"./action/reportMn.php",
        method: "POST",
        data:{
          mn : selectMn,
          yr : text
        },
        success:function(data){
          $("#incExpMnth").html(data)
        }
      })
    })
    // Month selector - 2nd Occurrence for Cash Flow
  document.getElementById("selectMnth2").addEventListener('change', function() {
    // console.log('Month is: ', this.value);
      let selectYr2 = document.getElementById("selyrs2");
      let text = selectYr2.options[selectYr2.selectedIndex].text;
      // console.log('Year is: ', text);
    // console.log('Month is: ', this.value);
    let selectMn2 = this.value;
      $.ajax({
        url:"./action/reportMn2.php",
        method: "POST",
        data:{
          mn : selectMn2,
          yr : text
        },
        success:function(data){
          $("#cashFlMnth").html(data)
        }
      })
    })
