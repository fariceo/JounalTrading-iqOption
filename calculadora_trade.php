<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trading PRO</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: Arial;
            background: #0f172a;
            color: white;
            margin: 0;
        }

        /* CONTENEDOR */
        .contenedor {
            max-width: 1100px;
            margin: auto;
            background: #1e293b;
            padding: 20px;
            border-radius: 15px;
        }

        /* GRID FORM */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }

        /* INPUTS */
        input,
        select,
        button {
            padding: 10px;
            border-radius: 8px;
            border: none;
            outline: none;
        }

        /* CARDS */
        .card {
            background: #334155;
            padding: 10px;
            margin: 5px;
            border-radius: 10px;
        }

        /* COLORES */
        .ganancia {
            color: #22c55e;
        }

        .perdida {
            color: #ef4444;
        }

        /* RESULTADO INPUT */
        .resultado-positivo {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid #22c55e;
        }

        .resultado-negativo {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid #ef4444;
        }

        .resultado-neutral {
            background: #334155;
            color: white;
        }

        /* TABLA CONTENEDOR */
        .tabla-container {
            width: 100%;
            overflow-x: auto;
        }

        /* TABLA DESKTOP */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
            white-space: nowrap;
            /* evita que rompa diseño */
        }

        /* FILAS */
        tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.03);
        }

        /* INPUTS DENTRO DE TABLA */
        td input,
        td select {
            max-width: 100px;
            width: 100%;
            box-sizing: border-box;
        }

        /* BOTÓN */
        td button {
            padding: 6px 8px;
            cursor: pointer;
        }

        /* 📱 MODO APP (RESPONSIVE REAL) */
        @media (max-width: 768px) {

            .tabla-container {
                overflow-x: hidden;
            }

            table,
            thead,
            tbody,
            tr {
                display: block;
                width: 100%;
            }

            thead {
                display: none;
            }

            tr {
                background: #334155;
                margin-bottom: 12px;
                padding: 12px;
                border-radius: 12px;
            }

            td {
                display: flex;
                flex-direction: column;
                text-align: left;
                padding: 6px 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }

            td:last-child {
                border-bottom: none;
            }

            /* etiquetas */
            td::before {
                content: attr(data-label);
                font-size: 12px;
                color: #94a3b8;
                margin-bottom: 3px;
            }

            /* inputs full width */
            td input,
            td select,
            td button {
                width: 100%;
                max-width: 100%;
                margin-top: 4px;
            }
        }


        .menu-top {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 15px;
        }

        .menu-top button {
            background: #334155;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 10px;
            cursor: pointer;
        }

        .menu-top button:hover {
            background: #475569;
        }
    </style>
</head>

<body>

    <div class="menu-top">
        <button onclick="mostrarSeccion('calc')">📊 Calculadora</button>
        <button onclick="mostrarSeccion('grafica')">📈 Gráfica</button>
        <button onclick="mostrarSeccion('trades')">📋 Trades</button>
    </div>
    <div class="contenedor">

        <h2>📈 Trading PRO</h2>
        <div id="seccion-calc">
            <div class="grid">
                <input id="par" value="EURJPY">
                <input id="entrada" placeholder="Entrada">
                <input id="tp" placeholder="TP">
                <input id="sl" placeholder="SL" oninput="calcular()">



                <input id="riesgoUSD" value="1">
                <input id="volumen" value="3000">

                <select id="apalancamiento">
                    <option value="20">1:20</option>
                    <option value="50">1:50</option>
                    <option value="100">1:100</option>
                    <option value="200">1:200</option>
                    <option value="300">1:300</option>
                    <option value="400">1:400</option>
                    <option value="500">1:500</option>
                    <option value="800">1:800</option>
                    <option value="1000">1:1000</option>
                    <option value="2000">1:2000</option>
                    <option value="3000" selected>1:3000</option>
                    <option value="5000">1:5000</option>
                </select>
            </div>

            <div class="card">TP: <span id="pipsTp">0</span></div>
            <div class="card">SL: <span id="pipsSl">0</span></div>
            <div class="card">Ganancia: <span id="ganancia">$0</span></div>
            <div class="card">Pérdida: <span id="perdida">$0</span></div>

            <hr>

            <select id="tipo">
                <option value="buy">BUY</option>
                <option value="sell">SELL</option>
            </select>

            <select id="estado">
                <option value="pendiente">Pendiente</option>
                <option value="ejecutado">Ejecutado</option>
            </select>

            <input id="cierre" placeholder="Cierre">

            <button onclick="guardarTrade()">💾 Guardar</button>
        </div>


        <div id="seccion-grafica">
            <h3>📊 Dashboard</h3>
            <div class="card">Total: <span id="totalTrades">0</span></div>
            <div class="card">WinRate: <span id="winRate">0%</span></div>
            <div class="card">Profit: <span id="profitTotal">$0</span></div>



            <h3>📈 Gráfica</h3>
            <canvas id="grafica" height="100"></canvas>

        </div>


        <div id="seccion-trades">
            <div style="margin-bottom:10px;">
                <input type="date" id="fechaInicio">
                <input type="date" id="fechaFin">
                <button onclick="cargarTrades()">🔍 Filtrar</button>
            </div>
            <div class="card">
                💰 Total filtrado: <span id="totalFiltrado">$0</span>
            </div>
            <h3>📋 Trades</h3>
            <div class="tabla-container">
                <table>

                    <thead>
                        <tr>
                            <th>Par</th>
                            <th>Entrada</th>
                            <th>Cierre</th>
                            <th>SL</th>
                            <th>TP</th>

                            <th>Resultado</th>
                            <th>Estado</th>
                            <th>Acción</th>

                        </tr>
                    </thead>
                    <tbody id="tablaTrades"></tbody>
                </table>
            </div>

        </div>



        <script>
            let graficaChart = null;

            const par = document.getElementById("par");
            const entrada = document.getElementById("entrada");
            const tp = document.getElementById("tp");
            const sl = document.getElementById("sl");
            const volumen = document.getElementById("volumen");
            const apalancamiento = document.getElementById("apalancamiento");
            const riesgoUSD = document.getElementById("riesgoUSD");
            const modoVolumen = document.getElementById("modoVolumen");

            function obtenerTamanoPip(p) {
                return p.includes("JPY") ? 0.01 : 0.0001;
            }

            function calcular() {
                let p = par.value.toUpperCase();
                let e = parseFloat(entrada.value);
                let t = parseFloat(tp.value);
                let s = parseFloat(sl.value);

                let riesgo = parseFloat(riesgoUSD.value); //


                if (!e || !t || !s) return;

                let pip = obtenerTamanoPip(p);

                let pipsTP = Math.abs(t - e) / pip;
                let pipsSL = Math.abs(e - s) / pip;

                document.getElementById("pipsTp").innerText = pipsTP.toFixed(2);
                document.getElementById("pipsSl").innerText = pipsSL.toFixed(2);

                let valorPip = (volumen.value * pip) / e;
                /*
                            let gan = pipsTP * valorPip;
                            let per = pipsSL * valorPip;
                
                            document.getElementById("ganancia").innerText = "$" + gan.toFixed(2);
                            document.getElementById("perdida").innerText = "$" + per.toFixed(2);*/
                // ajustamos ganancia y pérdida según el riesgo
                let gan = pipsTP * valorPip * riesgo;
                let per = pipsSL * valorPip * riesgo;

                document.getElementById("ganancia").innerText = "$" + gan.toFixed(2);
                document.getElementById("perdida").innerText = "$" + per.toFixed(2);
            }






            [par, entrada, tp, sl, volumen, riesgoUSD].forEach(e => {
                e.addEventListener("input", () => {
                    guardarValores();
                    calcular();
                });
            });
            riesgoUSD.addEventListener("input", calcular);

            apalancamiento.addEventListener("change", function () {

                let apal = parseFloat(this.value);

                // 🔥 definir volumen en base al apalancamiento
                if (modoVolumen.value === "auto") {
                    volumen.value = apal; // puedes ajustar escala si quieres
                }

                // 🔥 recalcular todo
                calcular();

                guardarValores();
            });

            function guardarTrade() {

                if (!par.value || !entrada.value || !tp.value || !sl.value) {
                    return alert("Completa todos los campos");
                }

                let datos = new FormData();

                datos.append("par", par.value);
                datos.append("tipo", document.getElementById("tipo").value);
                datos.append("entrada", entrada.value);
                datos.append("tp", tp.value);
                datos.append("sl", sl.value);
                datos.append("cierre", document.getElementById("cierre").value || 0);

                datos.append("volumen", volumen.value);
                datos.append("apalancamiento", apalancamiento.value);

                datos.append("pips_tp", document.getElementById("pipsTp").innerText);
                datos.append("pips_sl", document.getElementById("pipsSl").innerText);

                datos.append("ganancia_estimada", document.getElementById("ganancia").innerText.replace("$", ""));

                datos.append("riesgo", riesgoUSD.value);
                datos.append("estado", document.getElementById("estado").value);

                fetch("guardar_trade.php", { method: "POST", body: datos })
                    .then(r => r.text())
                    .then(r => {
                        //alert(r);
                        cargarTrades();
                        cargarDashboard();
                        cargarGrafica();
                    });
            }

            function cargarTrades() {

                let inicio = document.getElementById("fechaInicio").value;
                let fin = document.getElementById("fechaFin").value;

                let url = "obtener_trades.php";

                if (inicio && fin) {
                    url += "?inicio=" + inicio + "&fin=" + fin;
                }

                fetch(url)
                    .then(r => r.json())
                    .then(data => {

                        let html = "";
                        let total = 0; // 🔥 acumulador

                        data.forEach(t => {

                            total += parseFloat(t.resultado_real); // 🔥 SUMA

                            html += `
                            <tr>
                            <td data-label="Par">${t.par}</td>
                            <td data-label="Entrada">${t.entrada}</td>

                            <td data-label="Cierre">
                            <input type="number" value="${t.cierre}" 
                            onchange="actualizarCierre(${t.id}, this.value)">
                            </td>

                            <td data-label="SL">${t.sl}</td>
                            <td data-label="TP">${t.tp}</td>

                            <td data-label="Resultado">
                            <span class="${t.resultado_real >= 0 ? 'ganancia' : 'perdida'}">
                            $${t.resultado_real.toFixed(2)}
                            </span>
                            <br>
                            <small>${t.pips.toFixed(2)} pips</small>
                            </td>

                            <td data-label="Estado">
                            <select onchange="cambiarEstado(${t.id},this.value)">
                            <option value="pendiente" ${t.estado == "pendiente" ? "selected" : ""}>pendiente</option>
                            <option value="cerrado" ${t.estado == "cerrado" ? "selected" : ""}>cerrado</option>
                            </select>
                            </td>

                            <td data-label="Acción">
                            <button onclick="eliminarTrade(${t.id})">❌</button>
                            </td>
                            </tr>`;
                        });

                        document.getElementById("tablaTrades").innerHTML = html;

                        // 🔥 MOSTRAR TOTAL
                        document.getElementById("totalFiltrado").innerText = "$" + total.toFixed(2);
                    });
            }

            function cambiarEstado(id, estado) {
                let d = new FormData();
                d.append("id", id);
                d.append("estado", estado);

                fetch("actualizar_trade.php", { method: "POST", body: d })
                    .then(() => cargarDashboard());
            }

            function eliminarTrade(id) {
                let d = new FormData();
                d.append("id", id);

                fetch("eliminar_trade.php", { method: "POST", body: d })
                    .then(() => {
                        cargarTrades();
                        cargarDashboard();
                        cargarGrafica();
                    });
            }



            function cargarDashboard() {
                fetch("estadisticas.php")
                    .then(r => r.json())
                    .then(d => {

                        document.getElementById("totalTrades").innerText = d.total;
                        document.getElementById("winRate").innerText =
                            d.total ? ((d.wins / d.total) * 100).toFixed(2) + "%" : "0%";

                        document.getElementById("profitTotal").innerText =
                            "$" + (d.profit ?? 0);
                    });
            }

            function cargarGrafica() {

                fetch("grafica.php")
                    .then(r => r.json())
                    .then(data => {

                        if (!data || data.length === 0) return;

                        let labels = data.map(d => d.fecha);
                        let valores = data.map(d => parseFloat(d.balance));

                        let ctx = document.getElementById("grafica").getContext("2d");

                        if (graficaChart) {
                            graficaChart.destroy();
                        }

                        graficaChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Equity',
                                    data: valores,
                                    borderColor: 'lime',
                                    backgroundColor: 'transparent',
                                    tension: 0.3
                                }]
                            }
                        });
                    });
            }

            cargarTrades();
            cargarDashboard();
            cargarGrafica();

        </script>

        <script>

            function guardarValores() {
                localStorage.setItem("par", par.value);
                localStorage.setItem("entrada", entrada.value);
                localStorage.setItem("tp", tp.value);
                localStorage.setItem("sl", sl.value);
                localStorage.setItem("riesgo", riesgoUSD.value);
                localStorage.setItem("volumen", volumen.value);
                localStorage.setItem("apalancamiento", apalancamiento.value);
            }

            function cargarValores() {
                if (localStorage.getItem("par")) par.value = localStorage.getItem("par");
                if (localStorage.getItem("entrada")) entrada.value = localStorage.getItem("entrada");
                if (localStorage.getItem("tp")) tp.value = localStorage.getItem("tp");
                if (localStorage.getItem("sl")) sl.value = localStorage.getItem("sl");
                if (localStorage.getItem("riesgo")) riesgoUSD.value = localStorage.getItem("riesgo");
                if (localStorage.getItem("volumen")) volumen.value = localStorage.getItem("volumen");
                if (localStorage.getItem("apalancamiento")) apalancamiento.value = localStorage.getItem("apalancamiento");
            }

            window.addEventListener("load", () => {
                cargarValores();
                calcular();
            });

            function actualizarCierre(id, valor) {

                let d = new FormData();
                d.append("id", id);
                d.append("cierre", valor);

                fetch("actualizar_trade.php", {
                    method: "POST",
                    body: d
                })
                    .then(r => r.json())
                    .then(r => {
                        console.log("respuesta:", r);

                        // 🔥 ESTO FALTABA
                        cargarTrades();
                        cargarDashboard();
                        cargarGrafica();
                    });
            }


            function mostrarSeccion(seccion) {

                document.getElementById("seccion-calc").style.display = "none";
                document.getElementById("seccion-grafica").style.display = "none";
                document.getElementById("seccion-trades").style.display = "none";

                document.getElementById("seccion-" + seccion).style.display = "block";
            }

            window.addEventListener("load", () => {

                // mostrar sección inicial
                mostrarSeccion("calc");

                // 🔥 poner fecha de hoy automáticamente
                let hoy = new Date().toISOString().split("T")[0];

                document.getElementById("fechaInicio").value = hoy;
                document.getElementById("fechaFin").value = hoy;

                // 🔥 cargar trades con filtro de hoy
                cargarTrades();
            });
        </script>
</body>

</html>