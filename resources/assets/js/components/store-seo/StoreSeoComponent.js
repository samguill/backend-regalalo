import React from 'react';
import ReactDOM from 'react-dom';
import AutoForm from '../autoform/AutoForm';
export default class StoreSeoComponent extends React.Component {

    constructor(props) {
        super(props);
        this.data_update_url = this.props.data_update_url;
        this.store_id = this.props.store_id;
        this.meta_title = this.props.meta_title;
        this.meta_description = this.props.meta_description;

        this.fields = {
            id: {type: "hidden", default:this.store_id},
            meta_title: {title: "Título", type: "text", default:this.meta_title ,required: true, width: 12},
            meta_description: {title: "Descripción", type: "text", default:this.meta_description, verbose:true,required: true, width: 12},
        };

        this.state = {
            is_loading: false
        };
    }

    render() {
        return (
            <div style={{margin: '10px'}}>
                <AutoForm fields={this.fields} url={this.data_update_url}/>
            </div>
        );
    }
}


if (document.getElementsByClassName('store-seo-component')) {
    let elements=document.getElementsByClassName('store-seo-component');
    let count=elements.length;
    for(let i=0;i<count;i++) {
        let element = elements[i];
        let store_id = element.getAttribute('store_id');
        let meta_title = element.getAttribute('meta_title');
        let meta_description = element.getAttribute('meta_description');
        let data_update_url = element.getAttribute('data_update_url');

        ReactDOM.render(<StoreSeoComponent
            data_update_url={data_update_url}
            meta_title={meta_title}
            meta_description={meta_description}
            store_id={store_id} />, element);
    }
}