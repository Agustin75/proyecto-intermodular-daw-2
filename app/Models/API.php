<?php
define("FIRST_FORM_ID", 10001);

class API {
    // List that holds the name, id and URL of all Pokemon from the API (Forms excluded)
    private array $allPokemonList = [];

    public function __construct() {

    }

    /**
     * Function to get the list of all Pokemon. If the list hasn't been populated yet, it will make a request to the API. Otherwise, it will simply return the list.
     * @return array - a list of Pokemon info with: name, URL and ID, sorted by ID
     */
    public function getAllPokemon() : array {
        // We check if we have already obtained the list
        if (empty($this->allPokemonList)) {
            // If we haven't, we obtain it from the API and sort it. The request will get all Pokemon up to the first Form ID to avoid getting Forms.
            $this->allPokemonList = $this->obtainSortedPokemonList($this->makeRequest("pokemon?limit=" . FIRST_FORM_ID - 1)["results"]);
        }

        // We return the sorted list of Pokemon
        return $this->allPokemonList;
    }

    /**
     * @param int|string $type - the name or id of the type to filter by (fire, water, etc.)
     * @return array - a list of Pokemon info with: name, URL and ID, sorted by ID. If there was an error, returns an empty array
     */
    public function getPokemonByType(int|string $type) : array {
        $typeInformation = $this->makeRequest("type/$type?limit=" . FIRST_FORM_ID - 1);

        // If $typeInformation is null there was an error, so we return an empty array
        if (!$typeInformation) {
            return [];
        }

        return $this->obtainSortedPokemonList($typeInformation['pokemon']);
    }

    /**
     * @param int $generation - the Generation to filter by (1 to 9 currently)
     * @return array - a list of Pokemon info with: name, URL and ID, sorted by ID. If there was an error, returns an empty array
     */
    public function getPokemonByGeneration(int $generation) : array {
        // TODO: Validate generation being an int?

        // We obtain the information about the generation
        $generationInformation = $this->makeRequest("generation/$generation?limit=" . FIRST_FORM_ID - 1);

        // If $generationInformation is null there was an error, so we return an empty array
        if (!$generationInformation) {
            return [];
        }

        return $this->obtainSortedPokemonList($generationInformation['pokemon_species']);
    }

    /**
     * Function to obtain a list of Pokemon whose names start with the given string
     * @param string $name - the starting characters of a Pokemon's name to filter by
     * @return array - a list of Pokemon info with: name, URL and ID, sorted by ID. If there was an error, returns an empty array
     */
    public function getPokemonByName(string $name) : array {
        $pokemonMatches = [];
        
        // We loop through all Pokemon and check if their name starts with the given string
        foreach ($this->getAllPokemon() as $pokemon) {
            // We check if the name starts with the given string (ignoring cases)
            if (preg_match('/^('.$name.')/i', $pokemon['name'])) {
                // We add the Pokemon to the list of matches
                $pokemonMatches[] = $pokemon;
            }
        }

        return $pokemonMatches;
    }

    /**
     * @param int $id - the id of the Pokemon to obtain (it's National Dex number, except for forms)
     * @return array - a single element array containing the Pokemon information. If there was an error, returns an empty array
     */
    public function getPokemonById(int $id) : array {
        // TODO: Validate id being an int?

        // We obtain the Pokemon's information
        $pokemonData = $this->makeRequest("pokemon/$id");

        // If $pokemonData is null there was an error, so we return an empty array
        return $pokemonData ?? [];
    }

    /////////////////////
    // HELPER FUNCTIONS
    /////////////////////
    /**
     * @param string $endpoint - The endpoint or full URL call to the API
     * @param bool $fullURL - whether $endpoint is the full URL or just the endpoint. Default is false (just endpoint)
     * @return array|null - an array containing the response from the API. If there was an error, returns an empty array
     */
    private function makeRequest(string $endpoint, bool $fullURL = false): array | null
    {
        // If $fullURL is true, then $endpoint is the full call to the API. Otherwise, we append it to the base URL.
        $url = ($fullURL ? "" :  "https://pokeapi.co/api/v2/") . $endpoint;

        // We initialize the cURL
        $curl = curl_init();

        // We set the options for the cURL
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10
        ]);

        // We execute the cURL
        $response = curl_exec($curl);

        // If there was and error, we handle it and stop the execution
        if (curl_errno($curl)) {
            return [];
        }

        // We return the list of all Pokemon (each one with its name and URL)
        return json_decode($response, true);
    }

    /**
     * @param array $basicPokemonList a list of Pokemon info from the API (Only with name and URL)
     * @return array an extended list of Pokemon info with: name, URL and ID, sorted by ID
     */
    private function obtainSortedPokemonList(array $basicPokemonList): array
    {
        $pokemonList = [];

        // We loop through the basic list of Pokemon
        for ($i = 0; $i < count($basicPokemonList); $i++) {
            // We get the current Pokemon
            $currPokemon = $basicPokemonList[$i];
            // We split the URL to obtain the ID
            $elements = explode("/", $currPokemon['url']);
            // We save the ID in the current Pokemon info. The ID is the last element before the last slash
            $currPokemon['id'] = (int)$elements[count($elements) - 2];

            // If the ID does not correspond to a Form, we add it to the list
            if ($currPokemon["id"] < FIRST_FORM_ID) {
                // We add the current Pokemon to the list to return
                $pokemonList[] = $currPokemon;
            }
        }

        // We sort the new list by ID
        array_multisort(array_column($pokemonList, 'id'), SORT_ASC, $pokemonList);
        return $pokemonList;
    }
}
?>