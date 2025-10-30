window.onload = function() {
    const formulario = document.getElementById("formulario");
    const imagenInput = document.getElementById("imagen");

    formulario.onsubmit = function(evento) {
        evento.preventDefault(); // Stop default form submit

        const datos = new FormData(formulario);
        const dataObjeto = Object.fromEntries(datos.entries()); // Convert to JS object

        // If there is an image, read and encode it as Base64
        if (imagenInput.files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(e) {
                dataObjeto.imagen = e.target.result; // Base64 string
                ajax("api/post/registro.php", dataObjeto);
            };
            reader.readAsDataURL(imagenInput.files[0]);
        } else {
            ajax("api/post/registro.php", dataObjeto);
        }
    };
};

function ajax(url, dataObjeto) {
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                console.log("Éxito:", xhr.responseText);
            } else {
                console.error("Error:", xhr.statusText);
            }
        }
    };
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhr.send(JSON.stringify(dataObjeto));
}

// Preview image
const preview = document.getElementById("preview");
const imagenInput = document.getElementById("imagen");
imagenInput.onchange = function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};
