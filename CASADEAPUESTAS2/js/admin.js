// Espera a que todo el DOM esté cargado
document.addEventListener("DOMContentLoaded", function () {
    // Selecciona todos los botones de editar, eliminar y agregar
    const botonesEditar = document.querySelectorAll(".editar-btn");
    const botonesEliminar = document.querySelectorAll(".eliminar-btn");
    const botonesAgregar = document.querySelectorAll(".agregar-btn");

    // Función para asignar evento de click al botón de editar
    function asignarBotonEditar(botonEditar) {
        botonEditar.addEventListener("click", function () {
            // Encuentra el contenedor del evento
            let evento = this.closest(".evento");
            let titulo = evento.querySelector("h4");
            let premiosTexto = evento.querySelector("p");

            // Extrae los datos actuales del evento
            let nombreEvento = titulo.innerText.replace("⚽ ", "");
            let [equipoLocal, equipoVisitante] = nombreEvento.split(" vs ");
            let premios = premiosTexto.innerText.split(": ")[1].split(" | ");

            // Solicita al usuario nuevos valores (o mantiene los actuales si no escribe nada)
            let nuevoEquipoLocal = prompt("Ingrese el nuevo nombre del equipo local:", equipoLocal) || equipoLocal;
            let nuevoEquipoVisitante = prompt("Ingrese el nuevo nombre del equipo visitante:", equipoVisitante) || equipoVisitante;
            let nuevoPremio1 = prompt("Ingrese el nuevo premio para gana visitante:", premios[0]) || premios[0];
            let nuevoPremio2 = prompt("Ingrese el nuevo premio para gana local:", premios[1]) || premios[1];
            let nuevoPremio3 = prompt("Ingrese el nuevo premio para empate:", premios[2]) || premios[2];

            // Actualiza los datos en el DOM
            titulo.innerText = `⚽ ${nuevoEquipoLocal} vs ${nuevoEquipoVisitante}`;
            premiosTexto.innerText = `Premio: ${nuevoPremio1} | ${nuevoPremio2} | ${nuevoPremio3}`;

            // Obtiene el ID del evento
            const idEvento = this.dataset.id;

            // Prepara los datos para enviar al servidor
            const formData = new URLSearchParams();
            formData.append("id", idEvento);
            formData.append("equipo_local", nuevoEquipoLocal);
            formData.append("equipo_visitante", nuevoEquipoVisitante);
            formData.append("premio_1", nuevoPremio1);
            formData.append("premio_2", nuevoPremio2);
            formData.append("premio_3", nuevoPremio3);

            // Envía los datos al servidor mediante fetch
            fetch("editar_evento.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: formData.toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Evento actualizado correctamente.");
                    location.reload(); // Recarga la página para mostrar los cambios
                } else {
                    alert("Error al actualizar: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Error al actualizar el evento.");
            });
        });
    }

    // Función para asignar evento de click al botón de eliminar
    function asignarBotonEliminar(botonEliminar) {
        botonEliminar.addEventListener("click", function () {
            const idEvento = this.dataset.id;
            const confirmacion = confirm("¿Seguro que quieres eliminar este evento?");

            if (confirmacion) {
                fetch("eliminar_evento.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id=" + encodeURIComponent(idEvento)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Evento eliminado exitosamente.");
                        location.reload(); // Recarga la página tras eliminar
                    } else {
                        alert("Error al eliminar: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Error al eliminar el evento.");
                });
            }
        });
    }

    // Asigna la función a todos los botones existentes
    botonesEditar.forEach(asignarBotonEditar);
    botonesEliminar.forEach(asignarBotonEliminar);

    // Asigna evento a cada botón de agregar
    botonesAgregar.forEach((boton) => {
        boton.addEventListener("click", function () {
            let seccion = this.parentElement.querySelector(".eventos");
            let id_torneo = this.getAttribute("data-torneo-id");

            if (!id_torneo) {
                alert("Falta el ID del torneo.");
                return;
            }

            // Pide los datos del nuevo evento
            let equipo_local = prompt("Ingrese el nombre del equipo local:");
            if (!equipo_local) return;

            let equipo_visitante = prompt("Ingrese el nombre del equipo visitante:");
            if (!equipo_visitante) return;

            let premio_1 = prompt("Ingrese el premio para gana visitante:") || "gana visitante";
            let premio_2 = prompt("Ingrese el premio para gana local:") || "gana local";
            let premio_3 = prompt("Ingrese el premio para empate:") || "empate";

            // Crea el nuevo evento visualmente
            let nuevoEvento = document.createElement("div");
            nuevoEvento.classList.add("evento");
            nuevoEvento.innerHTML = `
                <h4>⚽ ${equipo_local} vs ${equipo_visitante}</h4>
                <p>Premio: ${premio_1} | ${premio_2} | ${premio_3}</p>
                <div class="botones">
                    <button class="editar-btn">Editar</button>
                    <button class="eliminar-btn">Eliminar</button>
                </div>
            `;
            seccion.appendChild(nuevoEvento);

            // Guarda el nuevo evento en el servidor
            fetch("guardar_evento.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `id_torneo=${encodeURIComponent(id_torneo)}&equipo_local=${encodeURIComponent(equipo_local)}&equipo_visitante=${encodeURIComponent(equipo_visitante)}&premio_1=${encodeURIComponent(premio_1)}&premio_2=${encodeURIComponent(premio_2)}&premio_3=${encodeURIComponent(premio_3)}`
            })
            .then(response => response.text())
            .then(data => {
                console.log("Servidor:", data);
                alert(data);
                location.reload(); // Recarga la página para mostrar el nuevo evento
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Ocurrió un error al guardar el evento.");
            });
        });
    });
});

