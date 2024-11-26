var ctx1 = document.getElementById('lineChart').getContext('2d');
var lineChart = new Chart(ctx1, {
    type: 'line',
    data: {
        labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو'],
        datasets: [{
            label: 'طلبات التواصل',
            data: [12, 19, 3, 5, 2, 3, 7],
            borderColor: 'rgba(0, 166, 255, 1)',
            backgroundColor: 'rgba(0, 166, 255, 0.3)',
            borderWidth: 3,
            pointRadius: 5,
            pointBackgroundColor: '#fff',
            pointBorderColor: 'rgba(0, 166, 255, 1)',
            pointHoverRadius: 8,
            fill: true
        }]
    },
    options: {
        responsive: true,  // تأكد من أن الرسم البياني متجاوب
        maintainAspectRatio: false,  // تعطيل الحفاظ على نسبة العرض/الارتفاع
        aspectRatio: 2,  // تحديد نسبة عرض/ارتفاع مناسبة
        animation: {
            duration: 2000,
            easing: 'easeInOutBounce'
        }
    }
});

var ctx2 = document.getElementById('pieChart').getContext('2d');
var pieChart = new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: ['مكتمل', 'قيد التنفيذ', 'ملغي'],
        datasets: [{
            data: [30, 50, 20],
            backgroundColor: ['#007bff', '#28a745', '#ffc107']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        aspectRatio: 2,
        animation: {
            duration: 2000,
            easing: 'easeInOutBounce'
        }
    }
});

