import React from 'react';
import ReactDOM from 'react-dom';
import AutoForm from '../autoform/AutoForm';
export default class ProductImagesComponent extends React.Component {

    constructor(props){
        super(props);
        this.data_product_id = this.props.data_product_id;
        this.data_add_image_url = this.props.data_add_image_url;
        this.data_delete_image_url = this.props.data_delete_image_url;
        this.data_product_images_list = JSON.parse(this.props.data_store_images);
        this.data_store_images = this.data_product_images_list.map((element) => {
            var obj = {};
            obj['id'] = element.id;
            obj['image_path'] = element.image_path;
            return obj;
        });

        this.data_product_images_list = JSON.parse(this.props.data_product_images);

        this.data_product_images = this.data_product_images_list.map((element) => {
            var obj = {};
            obj['id'] = element.id;
            obj['store_image_id'] = element.store_image_id;
            obj['image_path'] = element.store_image['image_path'];
            return obj;
        });


        this.state = {
            store_images: this.data_store_images,
            product_images: this.data_product_images,
            is_loading: false
        };
        console.log(this.data_store_images);
        console.log(this.data_product_images);

    }

    addImage(data){
        this.setState({is_loading:true});
        data.product_id = this.data_product_id;
        axios.post(this.data_add_image_url, data)
            .then((response)=>{
                if(response.data.status === "ok"){
                    swal({  title: "Operación Exitosa",
                        text: "Se ha creado el registro.",
                        type: "success"});
                    this.setState({
                        product_images: this.state.product_images.concat({
                            id: response.data.data['id'],
                            store_image_id: response.data.data['store_image_id'],
                            image_path: response.data.data['store_image']['image_path'],
                        }),
                        is_loading:false
                    });
                }
                if(response.data.status === 'error') {
                    swal({
                        title: "Ha ocurrido un error.",
                        text: "Por favor intente una vez más.",
                        type: "error"
                    });
                    this.setState({is_loading:false});
                }
            })
            .catch((error)=>{
                swal({  title: "Ha ocurrido un error.",
                    text: "Por favor intente una vez más.",
                    type: "error"});
                console.log(error);
                this.setState({is_loading:false});
            });
    }

    removeImage(data){
        swal({
            title: "¿Estás seguro?",
            text: "Si eliminas este item no podrás recuperarlo",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Si, eliminar",
            showLoaderOnConfirm: true,
            closeOnConfirm: false
        },()=> {
            axios.post(this.data_delete_image_url,{id:data.id})
                .then((response)=>{
                    console.log(response);
                    if(response.data.status=='ok') {
                        swal({  title: "Eliminación Exitosa",
                            text: "El elemento ha sido eliminado de manera exitosa.",
                            type: "success"});
                        this.setState({
                            product_images: this.state.product_images.filter((item) => item.id != data.id)
                        });
                    }
                    if(response.data.status=='error') {
                    swal({  title: "Ha ocurrido un error al Eliminar",
                        text: "Por favor intente una vez más.",
                        type: "error"})
                    }
            }).catch((e)=>{
                swal({  title: "Ha ocurrido un error al Eliminar",
                    text: "Por favor intente una vez más.",
                    type: "error"});
            })
        });
    }

    render() {
        return (
            <div style={{margin: '10px'}}>
                {(this.state.is_loading) ? <div className="overlay"><i className="fa fa-refresh fa-spin"></i></div> : '' }
                <div className="media-list-files store row">
                    <h5 style={{width:'100%'}}>Seleccionadas</h5>
                    {
                        this.state.product_images.map((row, ri) => {
                            return <div key={ri} className="col-md-3 file">
                                <div className="file-item-containter">
                                    <div className="remove-item" onClick={(e) => this.removeImage(row, e)}>
                                        <i className="fa fa-remove"></i>
                                    </div>
                                    <div className="file-item" style={{ backgroundImage : "url(http://regalalo.test/"+ row.image_path +")" }}></div>
                                </div>
                            </div>
                        })
                    }
                    <hr/>
                </div>
                <div className="media-list-files store row">
                    <h5 style={{width:'100%'}}>Disponibles</h5>
                    {
                        this.state.store_images.map((row, ri) => {

                                return <div key={ri} className="col-md-3 file" style={{cursor:'pointer'}} onClick={(e) => this.addImage(row, e)}>
                                    <div className="file-item-containter">
                                        <div className="file-item" style={{ backgroundImage : "url(http://regalalo.test/"+ row.image_path +")" }}></div>
                                    </div>
                                </div>

                        })
                    }
                </div>
            </div>
        );
    }

}

if (document.getElementsByClassName('product-images-component')) {
    var elements=document.getElementsByClassName('product-images-component');
    var count=elements.length;
    for(var i=0;i<count;i++) {
        let element = elements[i];
        let data_store_images = element.getAttribute('data_store_images');
        let data_product_images = element.getAttribute('data_product_images');
        let data_product_id = element.getAttribute('data_product_id');
        let data_add_image_url = element.getAttribute('data_add_image_url');
        let data_delete_image_url = element.getAttribute('data_delete_image_url');

        ReactDOM.render(<ProductImagesComponent
            data_store_images={data_store_images}
            data_product_images={data_product_images}
            data_product_id={data_product_id}
            data_add_image_url={data_add_image_url}
            data_delete_image_url={data_delete_image_url}
        />, element);
    }
}
