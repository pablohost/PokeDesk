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
            $pokemon = $this->process_pokemon($pokemon_list[$i]['name'], $response_pokemon->json());
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

    public function process_pokemon($name = '', $response_pokemon = [])
    {
        $pokemon = Pokemon::where('name', $name)->first();
        if (!$pokemon) {
            $pokemon = new Pokemon;
            $pokemon->name = $name;
            $pokemon->base_experience = $response_pokemon['base_experience'];
            $pokemon->height = $response_pokemon['height'];
            $pokemon->weight = $response_pokemon['weight'];
            $pokemon->sprite = $response_pokemon['sprites']['front_default'];
            $pokemon->save();
            $this->process_abilities($pokemon, $response_pokemon);
        }else{
            $this->process_abilities($pokemon, $response_pokemon);
        }

        return $pokemon;
    }

    public function home($value='')
    {
        return view("welcome");
    }

    public function show($offset = 0)
    {
        try{
            $response_list = Http::get('https://pokeapi.co/api/v2/pokemon?limit=10&offset='.$offset);
            if ($response_list->ok() == '200') {
                $pokemon_list = $response_list->json()['results'];
                $response = [];
                for ($i=0; $i < count($pokemon_list); $i++) { 
                    $response_pokemon = Http::get($pokemon_list[$i]['url']);
                    $pokemon = new Pokemon;
                    $pokemon->name = $pokemon_list[$i]['name'];
                    $pokemon->id = $response_pokemon->json()['id'];
                    $pokemon->sprite = $response_pokemon->json()['sprites']['front_default'];
                    $response[] = $pokemon;
                    /*actualizo pokemon en bd*/
                    $this->process_pokemon($pokemon_list[$i]['name'], $response_pokemon->json());
                }
                return $response;
            }else{
                $pokemon_list = Pokemon::skip($offset)->take(10)->get();
                for ($i=0; $i < count($pokemon_list); $i++) { 
                    $pokemon = new Pokemon;
                    $pokemon->name = $pokemon_list[$i]['name'];
                    $pokemon->id = $pokemon_list[$i]['id'];
                    $pokemon->sprite = $pokemon_list[$i]['sprite'];
                    $response[] = $pokemon;
                }
                return $response;
            }
        } catch (\Exception $e)
        {
            return [];
        }
    }

    public function show_details($pokemon='')
    {
        try{
            $response = [];
            $response_pokemon = Http::get('https://pokeapi.co/api/v2/pokemon/'.$pokemon);
            if ($response_pokemon->ok() == '200') {
                $pokemon = new Pokemon;
                $pokemon->name = $response_pokemon->json()['name'];
                $pokemon->id = $response_pokemon->json()['id'];
                $pokemon->sprite = $response_pokemon->json()['sprites']['front_default'];
                $pokemon->base_experience = $response_pokemon->json()['base_experience'];
                $pokemon->height = $response_pokemon->json()['height'];
                $pokemon->weight = $response_pokemon->json()['weight'];
                $response['pokemon'] = $pokemon;
                $abilities = [];
                for ($i=0; $i < count($response_pokemon['abilities']); $i++) { 
                    $ability = new Abilities;
                    $ability->name = $response_pokemon['abilities'][$i]['ability']['name'];
                    $abilities[] = $ability;
                }
                $response['abilities'] = $abilities;
                return $response;
            }else{
                $pokemon = Pokemon::join('pokemon_abilities as pa', 'pa.pokemon_id', '=', 'pokemon.id')->join('abilities as a', 'a.id', '=', 'pa.ability_id')->select('pokemon.*', 'a.name as ability')->where('pokemon.id', $pokemon)->get();
                $response['pokemon'] = $pokemon[0];
                $abilities = [];
                for ($i=0; $i < count($pokemon); $i++) { 
                    $ability = new Abilities;
                    $ability->name = $pokemon[$i]['ability'];
                    $abilities[] = $ability;
                }
                $response['abilities'] = $abilities;
                return $response;
            }
        } catch (\Exception $e)
        {
            return [];
        }
    }

    public function search($pokemon='')
    {
        try{
            $response_pokemon = Http::get('https://pokeapi.co/api/v2/pokemon/'.$pokemon);
            if ($response_pokemon->ok() == '200') {
                if ($response_pokemon=='Not Found') {
                    return [];
                }
                $pokemon = new Pokemon;
                $pokemon->name = $response_pokemon->json()['name'];
                $pokemon->id = $response_pokemon->json()['id'];
                $pokemon->sprite = $response_pokemon->json()['sprites']['front_default'];
                $pokemon->base_experience = $response_pokemon->json()['base_experience'];
                $pokemon->height = $response_pokemon->json()['height'];
                $pokemon->weight = $response_pokemon->json()['weight'];
                return [$pokemon];
            }else{
                $pokemon = Pokemon::where('pokemon.name', $pokemon)->first();
                return [$pokemon];
            }
        } catch (\Exception $e)
        {
            return [];
        }
    }
}
