import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';

function Home() {
    // Sets initial state for products to empty array 
    const [pokemon, setPokemon] = useState([]);    
    const [pokemon_details, setPokemonDetails] = useState({'pokemon' : {'name' : 'No Seleccionado'},'abilities' : [{'name' : ' '}]});
    const [search, setSearch] = useState("");
    const [page, setPage] = useState(0); 
    // Call this function to get products data 
    const getPokemon = (page_req) => {
        setPage(page_req);
        /* fetch API in action */
        fetch('show/'+page_req)
        .then(response => {
            return response.json();
        })
        .then(pokemon => {
            //Fetched product is stored in the state 
            setPokemon(pokemon);
        });
    };
    // Call this function to get products data 
    const detailsPokemon = (id) => {
        /* fetch API in action */
        fetch('show_details/'+id)
        .then(response => {
            return response.json();
        })
        .then(pokemon => {
            //Fetched product is stored in the state 
            setPokemonDetails(pokemon);
        });
    };
    // Call this function to get products data 
    const searchPokemon = () => {
        /* fetch API in action */
        if(search!=''){
            fetch('search/'+search)
            .then(response => {
                return response.json();
            })
            .then(pokemon => {
                //Fetched product is stored in the state 
                setPokemon(pokemon);
            });
        }
    };
    /*useEffect is a lifecycle hook 
    * that gets called after the component is rendered 
    */
    useEffect(() => {
        getPokemon(0);
      }, []);    
    // Render the products 
    const renderPokemon = () => {
        if(pokemon.length>0){
            return pokemon.map(pokemon => {
                return (
                    /* When using list you need to specify a key 
                    * attribute that is unique for each list item 
                    */
                    <tr key={pokemon.id}>
                        <th scope="row">
                            <img src={pokemon.sprite} alt="" width="60px"></img>
                        </th>
                        <td>{pokemon.name}</td>
                        <td>
                            <button onClick={() => {detailsPokemon(pokemon.id);}} type="button" name="btn_details" className="btn btn-md btn-warning">Detalles</button>
                        </td>
                    </tr>
                );
            })
        }else{
            return (
                    <tr className="">
                      <th className=""></th>
                      <td className="spinner-border" role="status"><span className="sr-only">Cargando...</span></td>
                      <td className=""></td>
                    </tr>
            );
        }
        
    };
    // Render the products 
    const renderPokemonFooter = () => {
        if(pokemon.length>0){
            if(pokemon.length==1){
                return (
                    /* When using list you need to specify a key 
                    * attribute that is unique for each list item 
                    */
                    <tr>
                        <th>
                            <button onClick={() => {getPokemon(0);}} type="button" name="btn_back" className="btn btn-md btn-secondary">Ver Todo</button>
                        </th>
                        <td></td>
                        <td></td>
                    </tr>
                );
            }else{
                return (
                    <tr>
                        <th>
                            <button onClick={() => {backPage();}} type="button" name="btn_prev" className="btn btn-md btn-secondary">Anterior</button>
                        </th>
                        <td>
                            {page} - {page+10}
                        </td>
                        <td>
                            <button onClick={() => {nextPage();}} type="button" name="btn_forw" className="btn btn-md btn-secondary">Siguiente</button>
                        </td>
                    </tr>
                );
            }
        }else{
            return (
                    <tr className="">
                      <th className=""></th>
                      <td className=""></td>
                      <td className=""></td>
                    </tr>
            );
        }
        
    };
    // Render the products 
    const renderAbilities = () => {
        return pokemon_details['abilities'].map(pokemon_details => {
            return (
                <h5 key={pokemon_details['name']}>{pokemon_details['name']}</h5>
            );
        })
    };
    // Render the products 
    const backPage = () => {
        if(page>0){
            getPokemon(page-10);
        }
    };
    const nextPage = () => {
        if(page<90){
            getPokemon(page+10);
        }
    };

    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-12 text-center py-2">
                    <h2>PokeDesk</h2>
                </div>
                <div className="col-md-6">
                    <div className="container">
                        <div className="row justify-content-center">
                            <div className="col-12">
                                <div className="card">
                                    <div className="card-header">Busqueda</div>

                                    <div className="card-body">
                                        <div className="container">
                                            <div className="row justify-content-center">
                                                <div className="col-8">
                                                    <input onChange={(e) => setSearch(e.target.value)} type="text" name="txt_search" id="txt_search"></input>
                                                </div>
                                                <div className="col-4">
                                                    <button onClick={() => {searchPokemon();}} type="button" name="btn_search" className="btn btn-md btn-info">Buscar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="col-12">
                                <div className="card">
                                    <div className="card-header">Listado Pokemon</div>

                                    <div className="card-body">
                                        <div className="table-responsive">
                                            <table className="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Nombre</th>
                                                        <th scope="col">#</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    { renderPokemon() }
                                                </tbody>
                                                <tfoot>
                                                    { renderPokemonFooter() }
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
                <div className="col-md-6">
                    <div className="card">
                        <div className="card-header">Detalles Pokemon</div>

                        <div className="card-body">
                            <div className="container">
                                <div className="row justify-content-center">
                                    <div className="col-12">
                                        <h4>{pokemon_details['pokemon'].name}</h4>
                                    </div>
                                    <div className="col-6">
                                        <img src={pokemon_details['pokemon'].sprite} alt="" width="80px"></img>
                                    </div>
                                    <div className="col-6">
                                        <h6>Experiencia Base <b>{pokemon_details['pokemon'].base_experience}</b></h6>
                                        <h6>Altura <b>{pokemon_details['pokemon'].height}</b></h6>
                                        <h6>Peso <b>{pokemon_details['pokemon'].weight}</b></h6>
                                    </div>
                                    <div className="col-12">
                                        { renderAbilities() }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Home;

if (document.getElementById('home')) {
    ReactDOM.render(<Home />, document.getElementById('home'));
}
