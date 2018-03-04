import React from 'react';
import {observer,inject} from 'mobx-react'
import * as Excel from "exceljs/dist/exceljs.min.js";
import {saveAs} from 'file-saver';
export default inject("store")(observer(class DownloadExcel extends React.Component {
  constructor(props) {
    super(props);
    this.onButtonClick = this.onButtonClick.bind(this);
  }
  onButtonClick(){
    let fields = this.props.fields;
    let keys = Object.keys(this.props.fields).filter((field_name)=>{
      return fields[field_name].show;
    });
    var columns = keys.map((field_name)=>{
      return   { header: fields[field_name].title, key: field_name, width: 20 };
    });
    var rows = this.props.store.data.get().map((row)=>{
        let row_item = {};
        keys.map((field_name)=>{
          row_item[field_name] = row[field_name];

          if(fields[field_name].type=="map"){
            let options=fields[field_name].options;
            let currentOption=options.filter((o)=>o.id==row[field_name]);
            if(currentOption.length>0)
            row_item[field_name] =currentOption[0].value
          }

        })
        return row_item;
    })
    let workbook = new Excel.Workbook();
    workbook.creator = 'Bluetape';
    workbook.lastModifiedBy = 'nobody';
    workbook.created = new Date();
    var worksheet = workbook.addWorksheet('Report');
    worksheet.columns = columns;
    worksheet.addRows(rows);
    var buff = workbook.xlsx.writeBuffer().then(function (data) {
         var blob = new Blob([data], {type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"});
         saveAs(blob, "ReportData.xlsx");
       });

  }
  render() {
    return (<button onClick={this.onButtonClick} className="btn btn-success"><i className="fa fa-download"></i> Excel</button>);
  }
}))
