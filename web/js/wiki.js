let pokemonNameInput;
let typeSelect;
let generationSelect;
let pokemonList;

document.addEventListener("DOMContentLoaded", function() {
    pokemonNameInput = document.getElementById("pokemonNameInput");
    typeSelect = document.getElementById("typeSelect");
    generationSelect = document.getElementById("generationSelect");
    pokemonList = document.getElementById("pokemonList");

    pokemonNameInput.addEventListener("input", function() {
        // We check that an option was selected because it has the invisible character at the end
        if (this.value.slice(-1) == "\u2063") {
            // This line makes it so the invisible character is removed from the input, but it's not necessary because it triggers a redirection in our page
            // this.value = this.value.slice(0, 1);

            // NOTE: This could be coded better, but it works as it is
            window.location.replace(`index.php?ctl=verPokemon&pokemonId=` + document.querySelector(`#pokemonList option[value='${this.value}']`).dataset.id);
        }
    });

    typeSelect.addEventListener("change", function() {
        // We empty the select so the player can't use it while it's loading
        pokemonNameInput.innerHTML = "Loading...";
        // Reset generation filter when type filter is used
        generationSelect.selectedIndex = 0;
        filterBy("wikiFilterByType", "type", this.options[this.selectedIndex].value);
    });

    generationSelect.addEventListener("change", function() {
        // We empty the select so the player can't use it while it's loading
        pokemonNameInput.innerHTML = "Loading...";
        // Reset type filter when generation filter is used
        typeSelect.selectedIndex = 0;
        filterBy("wikiFilterByGeneration", "generation", this.options[this.selectedIndex].value);
    });
});

async function filterBy(action, field, value) {
    let petition = new Request(`index.php?ctl=${action}&${field}=${value}`, 
        {
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

/////////////////////////////////////////////////////////////////////////////////////
// These functions are more generic, might be worth moving to a different file later
/////////////////////////////////////////////////////////////////////////////////////

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
    pokemonName = string.split("-");
    pokemonName.forEach((part, index) => {
        pokemonName[index] = part.charAt(0).toUpperCase() + part.slice(1);
    });
    return pokemonName.join(" ");
}