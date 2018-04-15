import React from 'react';
import ReactDOM from 'react-dom';
import AutoForm from '../autoform/AutoForm';
export default class ProductImagesComponent extends React.Component {

    constructor(props){
        super(props);
        this.data_store_images = JSON.parse(this.props.data_store_images);
        console.log(this.data_store_images);
    }

    render() {
        return (
            <div style={{margin: '10px'}}>

            </div>
        );
    }

}

if (document.getElementsByClassName('product-images-component')) {
    var elements=document.getElementsByClassName('product-images-component');
    var count=elements.length;
    for(var i=0;i<count;i++) {
        let element = elements[i];
        var data_store_images = element.getAttribute('data_store_images');

        ReactDOM.render(<ProductImagesComponent
            data_store_images={data_store_images}
        />, element);
    }
}
