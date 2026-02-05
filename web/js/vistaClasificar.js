let inputIdPokemon;

document.addEventListener("DOMContentLoaded", function () {
    inputIdPokemon = document.getElementById("idPokemon");

    inputIdPokemon.addEventListener("input", function () {
        // We check that an option was selected because it has the invisible character at the end
        if (this.value.slice(-1) == "\u2063") {
            // Obtenemos el ID del Pokémon seleccionado
            let option = document.querySelector(`#pokemonList option[value='${this.value}']`);
            if (option) {
                this.value = option.dataset.id; // dejamos solo el ID
            }
        }
    });
});
