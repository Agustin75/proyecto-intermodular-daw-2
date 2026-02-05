let bEliminarClasificar;
let inputIdPokemon;

document.addEventListener("DOMContentLoaded", function () {
    bEliminarClasificar = document.getElementById("bEliminarClasificar");
    inputIdPokemon = document.getElementById("idPokemon");

    // We check if the Delete button exist (If the admin is creating a new game, it won't be shown)
    if (bEliminarClasificar) {
        bEliminarClasificar.addEventListener("click", () => {
            this.location.replace("index.php?ctl=eliminarCategoria");
        });
    }

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
