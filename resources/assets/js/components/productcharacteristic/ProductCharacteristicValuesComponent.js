/**
 * Created by marzioperez on 04/03/18.
 */
import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Tooltip from 'react-tooltip-component';

export default class ProductCharacteristicValuesComponent extends React.Component {

    constructor(props) {
        super(props);

      this.data = this.props.data;

      this.state = {
            id: '',
            name: '',

        };


    }





    render() {


        return (
            <div style={{margin: '10px'}}>


                <table className="table">
                <thead>
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <tr>
                            <td>
                                <input type="text" className="form-control boxed" name="name" id="name" />
                            </td>

                            <td>
                                <input type="text" className="form-control boxed" name="name" id="name" />
                            </td>
                            <td>
                                <button type="button" className="btn btn-danger" onClick="eliminar_menu(1)"><i className="fa fa-minus-circle"></i> Quitar </button>
                            </td>

                        </tr>

            </tbody>
        <tfoot>
        <tr>
            <td>
                <button type="button" className="btn btn-info" onClick="agregar_menu()"><i className="fa fa-plus"></i> Agregar Opción </button>
            </td>
        </tr>
        </tfoot>
    </table>
            </div>
        );
    }

}

if (document.getElementsByClassName('product-characteristic-values')) {
    var elements = document.getElementsByClassName('product-characteristic-values');
    var count = elements.length;
    for(var i = 0; i < count; i++) {
        let element = elements[i];
        var data =JSON.parse( element.getAttribute("data"));


        ReactDOM.render(<ProductCharacteristicValuesComponent
            data={data}
        />, element);
    }
}