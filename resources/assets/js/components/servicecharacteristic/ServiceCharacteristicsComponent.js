import React  from 'react';
import ReactDOM from 'react-dom';
import Select from 'react-select';
import * as swal from 'bootstrap-sweetalert';
import 'bootstrap-sweetalert/dist/sweetalert.css';
import Tooltip from 'react-tooltip-component';

export default class ServiceCharacteristicsComponent extends React.Component {

    constructor(props) {
        super(props);
        this.data_product_characteristics = JSON.parse(this.props.data_product_characteristics);
        this.product_id = this.props.product_id;
        this.data_update_url = this.props.data_update_url;
        this.characteristic_values = this.props.characteristic_values;
        this.characteristic_id = this.props.characteristic_id;

        this.product_characteristics = this.data_product_characteristics.map((element) => {
            var obj = {};
            obj["label"] = element.name;
            obj["value"] = element.id;
            return obj;
        });

        this.state = {
            id: this.characteristic_id,
            values_array: [],
            values: [],
            product_characteristics: this.data_product_characteristics,
            is_loading: false
        };

        this.onSelectCharacteristic = this.onSelectCharacteristic.bind(this);
        this.onSelectValue = this.onSelectValue.bind(this);
        this.setValues = this.setValues.bind(this);
    }

    componentDidMount(){
        if(this.characteristic_id){
            this.setValues(this.characteristic_id);
        }
    }

    onSelectCharacteristic(option){
        let characteristic = this.data_product_characteristics.filter((item) => item.id == option.value);
        let values = characteristic[0].values.map((element) => {
            var obj = {};
            obj["label"] = element.value;
            obj["value"] = element.id;
            return obj;
        });
        this.setState({id:option.value, values_array:values, values:[]});
    }

    setValues(id){
        let characteristic = this.data_product_characteristics.filter((item) => item.id == parseInt(id));
        let values = characteristic[0].values.map((element) => {
            var obj = {};
            obj["label"] = element.value;
            obj["value"] = element.id;
            return obj;
        });

        let current_values = this.characteristic_values.split(",");

        let filter = [];
        values.filter(function(newData){
            return current_values.filter(function (oldData){
                if(newData.label === oldData){
                    filter.push(newData)
                }
            });
        });

        this.setState({values_array:values, values: filter});
    }

    onSelectValue(options){
        this.setState({values:options});
    }

    storeCharacteristics(){
        this.setState({is_loading:true});
        var obj = [];
        let values = this.state.values.map((element) => {
            obj.push(element.label)
        });
        let data = {
            service_id: this.product_id,
            service_characteristic_id: this.state.id,
            service_characteristic_values: obj.toString()
        };
        axios.post(this.data_update_url, data)
            .then((response) => {
                if(response.data.status === "ok") {
                    swal({
                        title: "Operación Exitosa",
                        text: "La información se ha actualizado de manera exitosa.",
                        type: "success"
                    });
                }
                if(response.data.status === 'error') {
                    swal({
                        title: "Ha ocurrido un error.",
                        text: "Por favor intente una vez más.",
                        type: "error"
                    });
                }
                this.setState({is_loading:false});
            })
            .catch((error) => {
                this.setState({is_loading:false});
                swal({
                    title: "Ha ocurrido un error.",
                    text: "Por favor intente una vez más.",
                    type: "error"
                });
            });
    }

    render() {
        return (
            <div style={{margin: '10px'}}>
                <div className="row">
                    <div className="col-md-4">
                        <div className="form-group">
                            <lable>Característica</lable>
                            <Select
                                className="form-control"
                                name="id"
                                options={this.product_characteristics}
                                value={this.state.id}
                                onChange={this.onSelectCharacteristic}
                            />
                        </div>
                    </div>
                    <div className="col-md-8">
                        <div className="form-group">
                            <lable>Valores</lable>
                            <Select
                                className="form-control"
                                name="values"
                                value={this.state.values}
                                options={this.state.values_array}
                                multi={true}
                                onChange={this.onSelectValue}
                            />
                        </div>
                    </div>
                    <div className={"col-md-12"}>
                        <a className="btn btn-success text-white btn-block" onClick={(e) => { this.storeCharacteristics()}}>
                            { (this.state.is_loading) ? <em className="fa fa-refresh fa-spin"></em> : 'Guardar'}
                        </a>
                    </div>
                </div>
            </div>
        )
    }

}

if (document.getElementsByClassName('characteristics-service-component')) {
    var elements = document.getElementsByClassName('characteristics-service-component');
    var count = elements.length;
    for(let i = 0; i < count; i++) {
        let element = elements[i];
        let data_product_characteristics = element.getAttribute("data_service_characteristics");
        let product_id = element.getAttribute("service_id");
        let data_update_url = element.getAttribute("data_update_url");
        let characteristic_id = element.getAttribute("characteristic_id");
        let characteristic_values = element.getAttribute("characteristic_values");

        ReactDOM.render(<ServiceCharacteristicsComponent
            data_product_characteristics={data_product_characteristics}
            product_id={product_id}
            data_update_url={data_update_url}
            characteristic_id={characteristic_id}
            characteristic_values={characteristic_values}
        />, element);
    }
}