import React from 'react';
import ReactDOM from 'react-dom';

function Form() {
    return (
        <div className="card">
            <div className="card-header">Fetch Star Wars Films and Peoples</div>
            <div className="card-body">
                <div className="row">
                    <div className="offset-md-4 col-md-4">
                        <button type="button" className="btn btn-outline-primary">Pull Films</button>
                        <button type="button" className="btn btn-outline-secondary ml-1">Pull Peoples</button>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Form;

if (document.getElementById('form')) {
    ReactDOM.render(<Form />, document.getElementById('form'));
}