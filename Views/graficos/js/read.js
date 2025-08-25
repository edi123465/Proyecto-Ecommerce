let salesChart;



const periodoSelect = document.getElementById('periodoSelect');

const mesFiltro = document.getElementById('mesFiltro');



function ajustarInputFiltro() {

    if (periodoSelect.value === 'anio') {

        mesFiltro.type = 'number';

        mesFiltro.min = '2000';

        mesFiltro.max = new Date().getFullYear();

        if (!mesFiltro.value) mesFiltro.value = new Date().getFullYear();

    } else {

        mesFiltro.type = 'month';

        mesFiltro.min = '';

        mesFiltro.max = '';

        mesFiltro.value = '';

    }

}



async function cargarGrafico(periodo = 'mes', mesFiltroVal = '') {

    // Para "anio" mandamos mesFiltroVal como año

    // Para los demás mandamos mesFiltroVal como mes en 'YYYY-MM'

    let mesParam = '';

    if (periodo === 'anio') {

        mesParam = mesFiltroVal; // año: '2023', '2024', etc.

    } else {

        mesParam = mesFiltroVal; // mes: '2023-05', '2023-06', etc.

    }



    console.log(`Cargando gráfico para periodo: ${periodo}, filtro: ${mesParam}`);



    const res = await fetch(`http://localhost:8080/Milogar/Controllers/ReporteController.php?action=obtenerVentas&periodo=${periodo}&mes=${mesParam}`);



    if (!res.ok) {

        console.error('Error en la respuesta:', res.status, res.statusText);

        return;

    }



    const data = await res.json();



    console.log('Datos recibidos:', data);



    let labels = [];

    let valores = [];



    if (periodo === 'semana') {

        labels = data.map(item => `Semana ${item.semana} (${item.anio})`);

        valores = data.map(item => Number(item.total));

    } else {

        labels = data.map(item => item.periodo);

        valores = data.map(item => Number(item.total));

    }



    console.log('Valores calculados para total:', valores);



    if (salesChart) salesChart.destroy();



    const ctx = document.getElementById('salesChart').getContext('2d');

    salesChart = new Chart(ctx, {

        type: 'line',

        data: {

            labels,

            datasets: [{

                label: 'Ventas',

                data: valores,

                backgroundColor: 'rgba(54,162,235,0.4)',

                borderColor: 'rgba(54,162,235,1)',

                borderWidth: 2,

                fill: true,

                tension: 0.3,

                pointRadius: 4

            }]

        },

        options: {

            responsive: true,

            plugins: {

                tooltip: {

                    callbacks: {

                        label: ctx => `Ventas: $${ctx.parsed.y.toLocaleString()}`

                    }

                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {

                        callback: val => '$' + val.toLocaleString()

                    }

                }

            }

        }

    });



    let totalVentas = valores.reduce((acc, val) => acc + val, 0);



    console.log('Total ventas acumuladas:', totalVentas);



    document.getElementById('tituloPeriodo').textContent =

        `${periodo.charAt(0).toUpperCase() + periodo.slice(1)} - Total: $${totalVentas.toLocaleString()}` +

        (mesParam ? ` (${mesParam})` : '');



    document.getElementById('totalVentas').textContent = `$${totalVentas.toLocaleString()} Dólares`;

}



// Ajusta el input al cargar y cuando cambia el periodo

periodoSelect.addEventListener('change', () => {

    ajustarInputFiltro();

    cargarGrafico(periodoSelect.value, mesFiltro.value);

});



mesFiltro.addEventListener('change', () => {

    cargarGrafico(periodoSelect.value, mesFiltro.value);

});



// Carga inicial con valores por defecto (por ejemplo mes actual)

ajustarInputFiltro();

cargarGrafico(periodoSelect.value, mesFiltro.value);





let productosChart;



async function cargarProductosMasVendidos(limit = 10) {

    try {

        const res = await fetch(`http://localhost:8080/Milogar/Controllers/ReporteController.php?action=productosMasVendidos&limit=${limit}`);

        if (!res.ok) throw new Error('Error en la respuesta HTTP: ' + res.status);



        const data = await res.json();

        console.log('Datos recibidos:', data);



        if (!Array.isArray(data) || data.length === 0) {

            console.warn('No se recibieron datos para productos más vendidos');

            return;

        }



        // Usamos total_vendido

        const labels = data.map(item => item.nombreProducto);

        const cantidades = data.map(item => Number(item.total_vendido));



        if (productosChart) productosChart.destroy();



        const ctx = document.getElementById('productosMasVendidosChart').getContext('2d');

        productosChart = new Chart(ctx, {

            type: 'bar',

            data: {

                labels,

                datasets: [{

                    label: 'Cantidad Vendida',

                    data: cantidades,

                    backgroundColor: 'rgba(255, 159, 64, 0.7)',

                    borderColor: 'rgba(255, 159, 64, 1)',

                    borderWidth: 1

                }]

            },

            options: {

                indexAxis: 'y',  // <-- Aquí cambiamos la orientación a horizontal

                responsive: true,

                scales: {

                    x: {

                        beginAtZero: true,

                        ticks: {

                            stepSize: 1

                        }

                    },

                    y: {

                        ticks: {

                            autoSkip: false // Para mostrar todas las etiquetas sin saltos (opcional)

                        }

                    }

                }

            }

        });



    } catch (error) {

        console.error('Error cargando productos más vendidos:', error);

    }

}



// Llama la función

cargarProductosMasVendidos(10);



// Inicializar

ajustarInputFiltro();

cargarGrafico(periodoSelect.value, mesFiltro.value);

