import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Tooltip from 'react-tooltip-component';

export default class IncomingInventoryComponent extends React.Component {

    constructor(props) {
        super(props);
console.log(props.dataproducts);
    }

    render() {
        return (
            <div style={{margin: '10px'}}>
                <form>
                </form>
            </div>

        );
    }

}

if (document.getElementsByClassName('store-incoming-inventory')) {
    var elements = document.getElementsByClassName('store-incoming-inventory');
    var count = elements.length;
    for(var i = 0; i < count; i++) {

        let element = elements[i];
        var dataproducts = element.getAttribute("data-products");



        ReactDOM.render(<IncomingInventoryComponent

            dataproducts={dataproducts}

        />, element);
    }
}