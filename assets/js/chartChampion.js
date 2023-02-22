import Chart from 'chart.js/auto';

let canvasArmor = document.getElementById('chartArmor')
const dataArmor = JSON.parse(canvasArmor.dataset.chart)
let canvasCrit = document.getElementById('chartCrit')
const dataCrit = JSON.parse(canvasArmor.dataset.chart)
const levelMax = 18

let dataChart = [];
for (let i = 0; i < levelMax; i++){
    dataChart['armor'] =dataArmor.armor + (i * data.armorPerLevel)
    dataChart['crit'] = dataCrit.crit + (i * data.critPerLevel)
}
console.log(dataChart)

let myChart = new Chart(canvasArmor, {
    type: 'bar',
    data: {
        labels: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18],
        datasets: [{
            label: 'My First Dataset',
            data: dataChart,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Armor'
            }
        }
    }
});