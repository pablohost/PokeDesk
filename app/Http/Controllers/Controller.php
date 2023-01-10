<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use App\Models\Pokemon;
use App\Models\Abilities;
use App\Models\Pokemon_abilities;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function refresh($value='')
    {
        $response_list = Http::get('https://pokeapi.co/api/v2/pokemon?limit=100&offset=0');
        $pokemon_list = $response_list->json()['results'];
        for ($i=0; $i < count($pokemon_list); $i++) { 
            $response_pokemon = Http::get($pokemon_list[$i]['url']);
            $pokemon = Pokemon::where('name', $pokemon_list[$i]['name'])->first();
            if (!$pokemon) {
                $pokemon = new Pokemon;
                $pokemon->name = $pokemon_list[$i]['name'];
                $pokemon->base_experience = $response_pokemon->json()['base_experience'];
                $pokemon->height = $response_pokemon->json()['height'];
                $pokemon->weight = $response_pokemon->json()['weight'];
                $pokemon->sprite = $response_pokemon->json()['sprites']['front_default'];
                $pokemon->save();
                $this->process_abilities($pokemon, $response_pokemon->json());
            }else{
                $this->process_abilities($pokemon, $response_pokemon->json());
            }
        }
        echo 'Habilidades actualizadas correctamente';
    }

    public function process_abilities($pokemon = new Pokemon, $response_pokemon = [])
    {
        $pokemon_abilities_del = Pokemon_abilities::where('pokemon_id', $pokemon->id)->delete();
        for ($i=0; $i < count($response_pokemon['abilities']); $i++) { 
            $ability = Abilities::where('name', $response_pokemon['abilities'][$i]['ability']['name'])->first();
            if (!$ability) {
                $ability = new Abilities;
                $ability->name = $response_pokemon['abilities'][$i]['ability']['name'];
                $ability->save();
            }
            $pokemon_abilities = new Pokemon_abilities;
            $pokemon_abilities->pokemon_id = $pokemon->id;
            $pokemon_abilities->ability_id = $ability->id;
            $pokemon_abilities->save();
        }
    }

    public function home($value='')
    {
        $response_list = Http::get('https://pokeapi.co/api/v2/pokemon?limit=100&offset=0');
        $pokemon_list = $response_list->json()['results'];
        return view("welcome", compact('pokemon_list'));
    }

    public function show($value='')
    {
        $response_list = Http::get('https://pokeapi.co/api/v2/pokemon?limit=100&offset=0');
        $pokemon_list = $response_list->json()['results'];
        $response = [];
        for ($i=0; $i < count($pokemon_list); $i++) { 
            $response_pokemon = Http::get($pokemon_list[$i]['url']);
            $pokemon = new Pokemon;
            $pokemon->name = $pokemon_list[$i]['name'];
            $pokemon->id = $response_pokemon->json()['id'];
            $pokemon->sprite = $response_pokemon->json()['sprites']['front_default'];
            $response[] = $pokemon;
        }
        return $response;
    }

    public function show_details($pokemon='')
    {
        $response = [];
        $response_pokemon = Http::get('https://pokeapi.co/api/v2/pokemon/'.$pokemon);
        $pokemon = new Pokemon;
        $pokemon->name = $response_pokemon->json()['name'];
        $pokemon->id = $response_pokemon->json()['id'];
        $pokemon->sprite = $response_pokemon->json()['sprites']['front_default'];
        $pokemon->base_experience = $response_pokemon->json()['base_experience'];
        $pokemon->height = $response_pokemon->json()['height'];
        $pokemon->weight = $response_pokemon->json()['weight'];
        $response[] = $pokemon;
        $abilities = [];
        for ($i=0; $i < count($response_pokemon['abilities']); $i++) { 
            $ability = new Abilities;
            $ability->name = $response_pokemon['abilities'][$i]['ability']['name'];
            $abilities[] = $ability;
        }
        $response[] = $abilities;
        return $response;
    }
}
