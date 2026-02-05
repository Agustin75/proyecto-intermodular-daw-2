let pokemonNameInput;
let typeSelect;
let generationSelect;
let pokemonList;

document.addEventListener("DOMContentLoaded", function() {
    pokemonNameInput = document.getElementById("pokemonNameInput");
    typeSelect = document.getElementById("typeSelect");
    generationSelect = document.getElementById("generationSelect");
    pokemonList = document.getElementById("pokemonList");

    // AUTOCOMPLETADO SIN REDIRECCIÓN
    pokemonNameInput.addEventListener("input", function() {
        // Detectamos si el valor termina en el carácter invisible
        if (this.value.slice(-1) == "\u2063") {
            // Eliminamos el carácter invisible
            this.value = this.value.slice(0, -1);

            // Obtenemos el ID del Pokémon seleccionado
            let option = document.querySelector(`#pokemonList option[value='${this.value}\u2063']`);
            if (option) {
                this.value = option.dataset.id; // dejamos solo el ID
            }

            // IMPORTANTE: NO REDIRIGIMOS
            // (wiki.js sí lo hace, pero aquí no)
        }
    });

    // FILTRADO POR TIPO
    typeSelect.addEventListener("change", function() {
        pokemonNameInput.innerHTML = "Loading...";
        generationSelect.selectedIndex = 0;
        filterBy("wikiFilterByType", "type", this.value);
    });

    // FILTRADO POR GENERACIÓN
    generationSelect.addEventListener("change", function() {
        pokemonNameInput.innerHTML = "Loading...";
        typeSelect.selectedIndex = 0;
        filterBy("wikiFilterByGeneration", "generation", this.value);
    });
});

async function filterBy(action, field, value) {
    let petition = new Request(`index.php?ctl=${action}&${field}=${value}`, {
        method: "GET",
    });

    try {
        let info = await fetch(petition);
        if (info.ok) {
            let data = await info.json();
            populatePokemonDatalist(pokemonList, data, "id", "name");
        } else {
            console.log(info.status);
        }
    } catch (error) {
        console.log(error);
    }
}

let populatePokemonDatalist = (datalist, data, idField, valueField) => {
    datalist.innerHTML = "";
    data.forEach(item => {
        let option = document.createElement("option");
        option.dataset.id = item[idField];
        option.value = item[idField] + " - " + capializePokemonName(item[valueField]) + "\u2063";
        datalist.appendChild(option);
    });
}

let capializePokemonName = (string) => {
    let pokemonName = string.split("-");
    pokemonName = pokemonName.map(part => part.charAt(0).toUpperCase() + part.slice(1));
    return pokemonName.join(" ");
}
