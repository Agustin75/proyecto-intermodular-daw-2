<?php
define("FIRST_FORM_ID", 10001);

class PokeAPI {
    // List that holds the name, id and URL of all Pokemon from the API (Forms excluded)
    private array $allPokemonList = [];
    private array $typesList = [];
    // Current number of Generations in the API
    private int $numGenerations = 0;
    // Current number of Pokemon in the API
    private int $numPokemon = 0;
    // The last Pokemon we obtained information from
    private $lastPokemonObtained = [];

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
            $this->allPokemonList = $this->obtainSortedPokemonList(
                $this->makeRequest("pokemon-species?limit=" . (FIRST_FORM_ID - 1))["results"]);
        }

        // We return the sorted list of Pokemon
        return $this->allPokemonList;
    }

    /**
     * Function to get the list of all existing official types. If the list hasn't been populated yet, it will make a request to the API. Otherwise, it will simply return the list.
     * @return array - a list of Pokemon types info with: name, URL and ID, sorted by ID
     */
    public function getTypesList(): array
    {
        if (empty($this->typesList)) {
            $types = $this->makeRequest("type")["results"];

            // We loop through the basic list of Pokemon
            for ($i = 0; $i < count($types); $i++) {
                // We get the current Pokemon
                $currType = $types[$i];
                // We split the URL to obtain the ID
                $elements = explode("/", $currType['url']);
                // We save the ID in the current Pokemon info. The ID is the last element before the last slash
                $currType['id'] = (int)$elements[count($elements) - 2];

                // Types not present in the main games also start at FIRST_FORM_ID, so we exclude them here
                if ($currType["id"] < FIRST_FORM_ID) {
                    // We add the current Pokemon to the list to return
                    $this->typesList[] = $currType;
                }
            }

            // We sort the new list by ID
            array_multisort(array_column($this->typesList, 'id'), SORT_ASC, $this->typesList);
        }

        return $this->typesList;
    }

    /**
     * Function to get the number of Pokemon. If the number wasn't obtained yet, it sets it as a count of the array of all Pokemon
     * @return int - the number of Pokemon
     */
    public function getNumPokemon() : int
    {
        if ($this->numPokemon === 0) {
            $this->numPokemon = count($this->getAllPokemon());
        }

        return $this->numPokemon;
    }

    /**
     * Function to get the number of Pokemon generations. If the number wasn't obtained yet, it makes a request to the api. Otherwise, it returns the number saved
     * @return int - the number of Pokemon generations
     */
    public function getNumGenerations() : int
    {
        if ($this->numGenerations === 0) {
            $generationsData = $this->makeRequest("generation");

            $this->numGenerations = $generationsData["count"];
        }

        return $this->numGenerations;
    }

    /**
     * @param int|string $type - the name or id of the type to filter by (fire, water, etc.)
     * @return array - a list of Pokemon info with: name, URL and ID, sorted by ID. If there was an error, returns an empty array
     */
    public function getPokemonByType($type) : array {
        $typeInformation = $this->makeRequest("type/$type?limit=" . (FIRST_FORM_ID - 1));

        // If $typeInformation is null there was an error, so we return an empty array
        if (!$typeInformation) {
            return [];
        }

        // We normalize the response to save the Pokemon information as ID, name and URL
        $pokemonOfType = $this->normalizePokemonByTypeResponse($typeInformation['pokemon']);

        return $this->sortNormalizedPokemonListById($pokemonOfType);
    }

    /**
     * @param int $generation - the Generation to filter by (1 to 9 currently)
     * @return array - a list of Pokemon info with: name, URL and ID, sorted by ID. If there was an error, returns an empty array
     */
    public function getPokemonByGeneration(int $generation) : array {
        // TODO: Validate generation being an int?

        // We obtain the information about the generation
        $generationInformation = $this->makeRequest("generation/$generation?limit=" . (FIRST_FORM_ID - 1));

        // If $generationInformation is null there was an error, so we return an empty array
        if (!$generationInformation) {
            return [];
        }

        // We normalize the response to save the Pokemon information as ID, name and URL
        $pokemonOfGeneration = $this->normalizePokemonByGenerationResponse($generationInformation['pokemon_species']);

        return $this->sortNormalizedPokemonListById($pokemonOfGeneration);
    }

    /**
     * Function to obtain a list of Pokemon whose names start with the given string
     * @param string $name - the starting characters of a Pokemon's name to filter by
     * @return array - a list of Pokemon info with: name, URL and ID, sorted by ID. If there was an error, returns an empty array
     */
    public function getPokemonByName(string $name) : array {
        $listToSearch = $this->getAllPokemon();
        
        $pokemonMatches = [];
        
        // We loop through all Pokemon and check if their name starts with the given string
        foreach ($listToSearch as $pokemon) {
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

        // If no Pokemon was obtained before || the last Pokemon obtained is different than the requested one
        if (count($this->lastPokemonObtained) == 0 || $this->lastPokemonObtained["id"] != $id) {
            // We obtain the Pokemon's information. if it's null there was an error, so we set lastPokemonObtained to an empty array
            $this->lastPokemonObtained = $this->makeRequest("pokemon/$id") ?? [];
            // We obtain the species information from that Pokemon to save the generation as well
            $speciesInformation = $this->makeRequest("pokemon-species/$id") ?? [];
            // If there was no error
            if (count($speciesInformation) > 0) {
                // We add a generation field to $lastPokemonObtained
                $this->lastPokemonObtained["generation"] = $this->getURLId($speciesInformation["generation"]["url"]);
                // We use the species name, so it doesn't show forms on the name itself
                $this->lastPokemonObtained["name"] = $speciesInformation["name"];
            }
        }

        return $this->lastPokemonObtained;
    }

    /**
     * @param int $id - the id of the Pokemon to obtain the information from
     * @return string - the name of the Pokémon
     */
    public function getPokemonName(int $id) : string {
        // TODO: Validate id being an int?

        // We obtain the data for the requested Pokemon
        $pokemon = $this->getPokemonById($id);

        // TODO: Make sure this doesn't break when the pokemon data doesn't exist
        return ucfirst($pokemon["name"] ?? "");
    }

    /**
     * @param int $id - the id of the Pokemon to obtain the information from
     * @return string - the url to the front sprite, or an empty string if no Pokemon with that id could be found
     */
    public function getPokemonNormalSprite(int $id) : string {
        // TODO: Validate id being an int?

        // We obtain the data for the requested Pokemon
        $pokemon = $this->getPokemonById($id);

        // TODO: Make sure this doesn't break when the pokemon data doesn't exist
        return $pokemon["sprites"]["front_default"] ?? "";
    }

    /**
     * @param int $id - the id of the Pokemon to obtain the information from
     * @return int - the generation the Pokemon belongs to
     */
    public function getPokemonGeneration(int $id) : int {
        // TODO: Validate id being an int?

        // We obtain the data for the requested Pokemon
        $pokemon = $this->getPokemonById($id);

        // TODO: Make sure this doesn't break when the pokemon data doesn't exist
        return $pokemon["generation"] ?? 0;
    }

    /**
     * @param int $id - the id of the Pokemon to obtain the information from
     * @return array - an array with the name of the types the Pokemon has
     */
    public function getPokemonTypes(int $id) : array {
        // TODO: Validate id being an int?

        // We obtain the data for the requested Pokemon
        $pokemon = $this->getPokemonById($id);

        $types = [];

        // We loop through the Pokemon's type structure
        foreach ($pokemon["types"] as $type) {
            // We save the name of each type to the types array
            $types[] = $type["type"]["name"];
        }

        // We return the types
        return $types;
    }

    /////////////////////
    // HELPER FUNCTIONS
    /////////////////////
    /**
     * @param string $endpoint - The endpoint or full URL call to the API
     * @param bool $fullURL - whether $endpoint is the full URL or just the endpoint. Default is false (just endpoint)
     * @return array|null - an array containing the response from the API. If there was an error, returns an empty array
     */
    private function makeRequest(string $endpoint, bool $fullURL = false)
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
            // We save the ID in the current Pokemon info. The ID is the last element before the last slash
            $currPokemon['id'] = $this->getURLId($currPokemon["url"]);

            // If the ID does not correspond to a Form, we add it to the list
            if ($currPokemon["id"] < FIRST_FORM_ID) {
                // We add the current Pokemon to the list to return
                $pokemonList[] = $currPokemon;
            }
        }

        return $this->sortNormalizedPokemonListById($pokemonList);
    }
    
    /**
     * @param array $response a list of Pokemon Type info from the API. it contains a list of Pokemon with only name and url
     * @return array the normalized list of Pokemon information (ID, name and URL) that belong to the specific type
     */
    private function normalizePokemonByTypeResponse(array $response): array
    {
        // We return the list of Pokemon of the given type from the list saved in API, since that one uses more visually appealing names (They don't have the form in the name for Pokemon that have multiple forms)
        $normalizedList = [];
        $fullList = $this->getAllPokemon();
        
        foreach ($response as $pokemonEntry) {
            $id = $this->getURLId($pokemonEntry["pokemon"]["url"]);

            if ($id < FIRST_FORM_ID) {
                // -1 because the list is 0-indexed
                $normalizedList[] = $fullList[$id - 1];
            }
        }

        return $normalizedList;
    }
    
    /**
     * @param array $response a list of Pokemon generation info from the API. it contains a list of Pokemon with only name and url
     * @return array the normalized list of Pokemon information (ID, name and URL) that belong to the specific generation
     */
    private function normalizePokemonByGenerationResponse(array $response): array
    {
        // We return the list of Pokemon of the given generation from the list saved in API, since that one uses more visually appealing names (They don't have the form in the name for Pokemon that have multiple forms)
        $normalizedList = [];
        $fullList = $this->getAllPokemon();
        
        foreach ($response as $pokemonEntry) {
            $id = $this->getURLId($pokemonEntry["url"]);

            if ($id < FIRST_FORM_ID) {
                // -1 because the list is 0-indexed
                $normalizedList[] = $fullList[$id - 1];
            }
        }

        return $normalizedList;
    }

    /**
     * @param string $urlToExtract the URL string to extract the ID from.
     * @return int the ID in the URL
     */
    private function getURLId(string $urlToExtract): int {
            // We split the URL to obtain the ID
            $elements = explode("/", $urlToExtract);
            // We save the ID in the current Pokemon info. The ID is the last element before the last slash
            $id = (int)$elements[count($elements) - 2];

            return $id;
    }

    /**
     * @param array $pokemonList list of normalized Pokemon information to sort
     * @return array the sorted list
     */
    private function sortNormalizedPokemonListById(array $pokemonList): array
    {
        // We sort the new list by ID
        array_multisort(array_column($pokemonList, 'id'), SORT_ASC, $pokemonList);
        return $pokemonList;
    }

    public function getPokemonDescriptionEs(int $id) : string
{
    $species = $this->makeRequest("pokemon-species/$id");

    if (!$species || empty($species["flavor_text_entries"])) {
        return "Descripción no disponible.";
    }

    foreach ($species["flavor_text_entries"] as $entry) {
        if ($entry["language"]["name"] === "es") {
            return str_replace(["\n", "\f"], " ", $entry["flavor_text"]);
        }
    }

    return "Descripción no disponible en español.";
}


}
?>