setTimeout(function () {
    'use strict';

    console.log('Script block_update.js iniciado (después de 3 segundos)');

    // ======================================
    // 1. Estilos para el overlay
    // ======================================
    const style = document.createElement('style');
    style.textContent = `
        .columna-overlay {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-color: rgba(255, 0, 0, 0.5) !important;
            z-index: 9999 !important;
            pointer-events: auto !important;
            cursor: not-allowed !important;
        }
    `;
    document.head.appendChild(style);
    console.log('Estilos aplicados');

    // ======================================
    // 2. Funciones clave
    // ======================================
    function obtenerNombreUsuario() {
        const enlace = document.querySelector('.navbar-right a:not([onclick])');
        if (!enlace) {
            console.log('No se encontró el enlace del usuario');
            return null;
        }
        const nombre = enlace.textContent
            .replace('Cambiar Contraseña', '')
            .trim()
            .replace(/^Tienda\s+/i, '');
        console.log('Nombre de usuario procesado:', nombre);
        return nombre;
    }

    function obtenerListaTiendas() {
        const options = document.querySelectorAll('#stock_location option');
        const tiendas = Array.from(options).map(opt => opt.textContent.trim());
        console.log('Lista de tiendas:', tiendas);
        return tiendas;
    }

    function obtenerTiendaSeleccionada() {
        const dropdown = document.querySelector('[data-id="stock_location"]');
        if (!dropdown) {
            console.log('No se encontró el dropdown de tienda');
            return null;
        }
        const tienda = dropdown.getAttribute('title');
        console.log('Tienda seleccionada:', tienda);
        return tienda;
    }

    // ======================================
    // 3. Lógica para aplicar el overlay
    // ======================================
    function aplicarOverlayEnColumnas() {
        console.log('Ejecutando aplicarOverlayEnColumnas');
        const nombreUsuario = obtenerNombreUsuario();
        const tiendas = obtenerListaTiendas();
        const tiendaSeleccionada = obtenerTiendaSeleccionada();

        if (!nombreUsuario || !tiendaSeleccionada) {
            console.log('Faltan datos para aplicar el overlay');
            return;
        }

        const esAdmin = !tiendas.some(t => t.toLowerCase() === nombreUsuario.toLowerCase());
        console.log('Es administrador:', esAdmin);

        if (esAdmin || nombreUsuario.toLowerCase() === tiendaSeleccionada.toLowerCase()) {
            console.log('No se aplica overlay (admin o tiendas coinciden)');
            return;
        }

        const filas = document.querySelectorAll('#table tbody tr');
        console.log('Filas encontradas en la tabla:', filas.length);
        filas.forEach(row => {
            const celdas = row.querySelectorAll('td');
            const numCeldas = celdas.length;
            if (numCeldas >= 3) {
                const ultimasTres = [celdas[numCeldas - 3], celdas[numCeldas - 2], celdas[numCeldas - 1]];
                ultimasTres.forEach(celda => {
                    if (celda) {
                        if (getComputedStyle(celda).position === 'static') {
                            celda.style.position = 'relative';
                        }
                        if (!celda.querySelector('.columna-overlay')) {
                            const overlay = document.createElement('div');
                            overlay.className = 'columna-overlay';
                            celda.appendChild(overlay);
                            console.log('Overlay agregado en celda:', celda);
                        }
                    }
                });
            }
        });
    }

    // ======================================
    // 4. Iniciar y observar cambios
    // ======================================
    function iniciar() {
        console.log('Función iniciar ejecutada');
        aplicarOverlayEnColumnas();

        const tabla = document.getElementById('table');
        if (tabla) {
            console.log('Observando cambios en la tabla');
            new MutationObserver(() => {
                console.log('Cambio detectado en la tabla');
                aplicarOverlayEnColumnas();
            }).observe(tabla, {
                childList: true,
                subtree: true
            });
        } else {
            console.log('No se encontró la tabla con id "table"');
        }
    }

    iniciar();
}, 3000); // Retraso de 3 segundos