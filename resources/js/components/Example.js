import React from 'react';
import ReactDOM from 'react-dom';

function Example() {
    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-6">
                    <div className="container">
                        <div className="row justify-content-center">
                            <div className="col-12">
                                <div className="card">
                                    <div className="card-header">Busqueda</div>

                                    <div className="card-body">I'm an example component!</div>
                                </div>
                            </div>
                            <div className="col-12">
                                <div className="card">
                                    <div className="card-header">Listado Pokemon</div>

                                    <div className="card-body">I'm an example component!</div>
                                    <div className="card-body">Que dice</div>
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

export default Example;

if (document.getElementById('example')) {
    ReactDOM.render(<Example />, document.getElementById('example'));
}
