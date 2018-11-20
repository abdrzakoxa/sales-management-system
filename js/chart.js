function rnd(ran, ins) {
    var colors_best = [] ;
    var rand;
    var value;
    var i = 0;
    for (; i < ins.length;) {

        rand = Math.floor(Math.random() * ran.length);
        value = ran[rand];

        colors_best.push(value);

        ran = $.grep(ran, function (val) {
            return value != val;
        });


        i++;
    }
    return colors_best;
}

// end function cookies

(function () {

    var xhttp;
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest();
    } else {
        // code for old IE browsers
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xhttp.onreadystatechange = function () {
        if (this.readyState == 4 & this.status == 200) {
            var res = xhttp.responseText;
            window.InfoDashboard = JSON.parse(res);
        }

    }
    xhttp.open("GET", '/json/InformationDashboard', false);
    xhttp.setRequestHeader("Content-type", "application/json; charset=utf-8");
    xhttp.send();

}());







function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}







var lang = getCookie('lang');

if(lang == 'ar') {

    var dictionary = {
        'number_of_product' : 'Number Of Products',
        'unpaid' : 'unpaid',
        'paid' : 'paid',
        'count_sales' : 'عدد المبيعات',
        'expanses' : 'المصروفات',
        'profits' : 'الأرباح',
        'January' : 'يناير',
        'February' : 'فبراير',
        'March' : 'مارس',
        'April' : 'أبرير',
        'May' : 'مايو',
        'June' : 'يونيو',
        'July' : 'يوليو',
        'August' : 'أغسطس',
        'September' : 'سبتمبر',
        'October' : 'أكتوبر',
        'November' : 'نوفمبر',
        'December' : 'ديسمبر',
    }

}
else if (lang == 'en') {
    var dictionary = {
        'number_of_product' : 'Number Of Products',
        'title_pur_sell' : 'Number Of Products',
        'unpaid' : 'غير مدفوعة',
        'paid' : 'مدفوعة',
        'expanses' : 'Expanses',
        'profits' : 'Profits',
        'count_sales' : 'Number Of Sales',
        'January' : 'January',
        'February' : 'February',
        'March' : 'March',
        'April' : 'April',
        'May' : 'May',
        'June' : 'June',
        'July' : 'July',
        'August' : 'August',
        'September' : 'September',
        'October' : 'October',
        'November' : 'November',
        'December' : 'December',
    }
}

var array_mondays = [dictionary.January,dictionary.February,dictionary.March,dictionary.April,dictionary.May,dictionary.June,dictionary.July,dictionary.August,dictionary.September,dictionary.October,dictionary.November,dictionary.December];

var date = new Date();
var monday = date.getMonth() + 1;
var dataone = InfoDashboard.dataset[0].data;
var datatow = InfoDashboard.dataset[1].data;

var test = false;
var start = 0;

InfoDashboard.dataset[0].data = [];
InfoDashboard.dataset[1].data = [];

for (i = 0;i < dataone.length;i++)
{
    if(dataone[i] != 0 || test || datatow[i] !=0)
    {
        InfoDashboard.dataset[0].data.push(dataone[i]);
        InfoDashboard.dataset[1].data.push(datatow[i]);
        test = true;
    }else{
        start++;
    }
}

var monday_of_now = array_mondays.splice(start,monday - start);




var ctx = document.getElementById("pur-sell").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: monday_of_now,
        datasets: InfoDashboard.dataset
    },
    options: {

    }
});


// Bar chart

var labels_best_sales = InfoDashboard.bestProductsSales.labels;
var colors_best_sales = ["rgba(39, 174, 96,.2)", "rgba(52, 73, 94,.2)", "rgba(41, 128, 185,.2)", "rgba(211, 84, 0,.2)", "rgba(192, 57, 43,0.2)"];
var colors_best = rnd(colors_best_sales,labels_best_sales);

colors_best = colors_best.slice(0,labels_best_sales.length);

new Chart(document.getElementById("best-sales"), {
    type: 'bar',
    data: {
        labels: labels_best_sales,
        datasets: [
            {
                label: dictionary.count_sales,
                backgroundColor: colors_best,
                borderColor: colors_best,
                borderWidth: 1,
                fill: false,
                data: InfoDashboard.bestProductsSales.data
            }
        ]
    },
    options: {

        legend: {display: false},
        scales: {
            xAxes: [{
                ticks: {
                    display: false //this will remove only the label
                }
            }]
        }
    }
});

// pie

var labels_e_p = [1,2];
var colors_e_p = ['rgb(255, 99, 132)',"#8e5ea2","#1ccc63", "#34495e", "#3498db", "#e67e22", "#40407a"];
var colors_exp_prof = rnd(colors_e_p,labels_e_p);



colors_best = colors_best.slice(0,labels_best_sales.length);

new Chart(document.getElementById("expenses-profits"), {
    type: 'doughnut',
    data: {
        labels: InfoDashboard.profit_expanses.label,
        datasets: [{
            backgroundColor: colors_exp_prof,
            data: InfoDashboard.profit_expanses.data
        }]
    },
    options: {

    }
});


// horizontalBar



var labels_e_p = [1,2];
var colors_e_p = ['rgb(255, 99, 132)',"#8e5ea2","#1ccc63", "#34495e", "#3498db", "#e67e22", "#40407a"];
var colors_exp_prof = rnd(colors_e_p,labels_e_p);

colors_best = colors_best.slice(0,labels_best_sales.length);

new Chart(document.getElementById("invoice-sales-status"), {
    type: 'pie',
    data: {
        labels: InfoDashboard.invoice_by_status.label,
        datasets: [{
            backgroundColor: colors_exp_prof,
            data: InfoDashboard.invoice_by_status.data
        }]
    },
    options: {

    }
});



//
$(function() {

    const langList = [
        'en','ko','fr','ch','de','jp','pt','da','pl','es','fa','it','cs','uk','ru'
    ];

    const lang = $.inArray(getCookie('lang'),langList) > -1 ?  getCookie('lang') : 'en';
    const format = Settings['settings-numbers-formatting'].DateFormat != '' ? Settings['settings-numbers-formatting'].DateFormat : 'YYYY-MM-DD';


    $('.calendar').pignoseCalendar({
        lang : lang,
        format : format,
        theme : 'blue'
    });
});

