document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("typeSelect").addEventListener("change", function() {
        console.log("Selected type:", this.options[this.selectedIndex].value);
        // We empty the select so the player can't use it while it's loading
        document.getElementById("pokemonSelect").innerHTML = "Loading...";
        filterBy("wikiFilterByType", "type", this.options[this.selectedIndex].value);
    });
});

async function filterBy(action, field, value) {
    let petition = new Request(`index.php?ctl=${action}&${field}=${value}`, 
        {
            method: "GET",
        });

    try {
        let info = await fetch(petition);
            console.log(info);
        if (info.ok) {
            let data = await info.json();
            console.log(data);
            populateSelect(document.getElementById("pokemonSelect"), data, "id", "name");
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

let populateSelect = (select, data, valueField, displayField) => {
    select.innerHTML = "";
    data.forEach(item => {
        let option = document.createElement("option");
        option.value = item[valueField];
        option.text = capializePokemonName(item[displayField]);
        select.appendChild(option);
    });
}

let capializePokemonName = (string) => {
    pokemonName = string.split("-");
    pokemonName.forEach((part, index) => {
        pokemonName[index] = part.charAt(0).toUpperCase() + part.slice(1);
    });
    return pokemonName.join(" ");
}