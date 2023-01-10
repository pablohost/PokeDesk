import React, { useState, useEffect } from 'react';
import ReactDOM from 'react-dom';

function Home() {
    // Sets initial state for products to empty array 
    const [pokemon, setPokemon] = useState([]);    
    // Call this function to get products data 
    const getPokemon = () => {
        /* fetch API in action */
        fetch('show')
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
        console.log(id);
        /* fetch API in action */
        fetch('show_details/'+id)
        .then(response => {
            console.log(response.json());
            return response.json();
        })
        .then(pokemon => {
            //Fetched product is stored in the state 
            //setPokemon(pokemon);
        });
    };
    /*useEffect is a lifecycle hook 
    * that gets called after the component is rendered 
    */
    useEffect(() => {
        console.log(React.version);
        getPokemon();
      }, []);    
    // Render the products 
    const renderPokemon = () => {
        return pokemon.map(pokemon => {
            return (
                /* When using list you need to specify a key 
                * attribute that is unique for each list item 
                */
                <tr key={pokemon.id}>
                    <th scope="row">
                        <img src={pokemon.sprite} alt="" width="80px"></img>
                    </th>
                    <td>{pokemon.name}</td>
                    <td>
                        <button onClick={() => {detailsPokemon(pokemon.id);}} type="button" name="btn_details" className="btn btn-md btn-warning">Detalles</button>
                    </td>
                </tr>
            );
        })
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
                                                    <input type="text" name="txt_search" id="txt_search"></input>
                                                </div>
                                                <div className="col-4">
                                                    <button type="button" name="btn_search" className="btn btn-md btn-info">Buscar</button>
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

                        <div className="card-body">Seleccione un Pokemon</div>
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
