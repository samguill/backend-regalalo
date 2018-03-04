import {observable,computed,action} from 'mobx';
// JVA - 01-09-2017
// Estado que regirá los componentes de CRUD de React
class CrudStore {
    constructor(){
        this.callStatus=observable.map();
        this.callStatus.set('created',false);
        this.callStatus.set('updated',false);
        this.callStatus.set('deleted',false);
        this.advancedFilters=observable.map();
        this.searchField=observable("");
        this.params=observable.map();
        this.params.set("items-per-page",15);
        this.params.set("sort-by","");
        this.params.set('show-advanced-search',false);

        this.definition = observable.map();
        this.setDefinition = action((fields)=>{
          Object.keys(fields).map((key)=>{
              this.definition.set(key,fields[key]);
          })
        });
        this.params.set("sort-asc",true);
        this.isDataLoading=observable(false);
        this.isDataUpdating=observable(false);
        this.globalData=observable([]);
        this.currentElement=observable.map({})
        this.fields=observable([]);
        //accion de actualizacion de texto de busqueda
        this.updateSearch=action((v)=>{
           this.searchField.set(v);
        });
        this.updateFields=action((a)=>{
           a.map((v)=>{this.fields.push(v);})
        });
        this.updateCurrentElement=action((e)=>{
           Object.keys(e).map((key)=>{
                this.currentElement.set(key,e[key]);
           })
        });
        this.updateSortBy=action((field)=>{
            if(this.params.get("sort-by")!=field)
            {
                this.params.set("sort-by",field);
                this.params.set("sort-asc",true);
            }else{
                this.params.set("sort-asc",!this.params.get("sort-asc"));
            }

        });
        this.getData=action((url)=>{
            if(this.globalData.length<1)
            {
                this.isDataLoading.set(true);
                return axios.get(url).then((r)=>{
                  this.isDataLoading.set(false);
                  r.data.map((d)=>{this.globalData.push(d)});
                  return this.globalData.toJS();
                }).catch(()=>{
                  this.isDataLoading.set(false);
                });
            }else{
                return new Promise((r,d)=>{r(this.globalData.toJS())});
            }

        });
        this.updateData=action((url)=>{
                this.isDataUpdating.set(true);
                return axios.get(url).then((r)=>{
                  this.isDataUpdating.set(false);
                  this.globalData.replace(r.data);
                  return this.globalData.toJS();
                });
        });
        this.addElement=action((item)=>{
          this.globalData.push(item);
        })
        this.updateElement=action((item)=>{
              this.globalData.replace(this.globalData.map((data)=>{
                        if(item.id==data.id)
                        {
                          return item
                        }
                        return data
              }));
        })
        this.deleteElement=action((item)=>{
              this.globalData.replace(this.globalData.filter((data)=>{
                        if(item.id==data.id)
                        {
                          return false
                        }
                        return true
              }));
        })
        // JVA - 01-09-2017
        // Data es un valor calculado que representa toda la data que se presentara en la tabla
        this.data=computed(()=>{
              var sortby="";
              var asc=this.params.get("sort-asc");
              var result=[];
              if(this.params.get("sort-by")!="")
              {
                  sortby=this.params.get("sort-by");
              }
              result=this.globalData.toJS().filter((item)=>{
                  var r=true;
                  if(!this.params.get('show-advanced-search'))
                  {
                      var globalSearch=this.searchField.get().toUpperCase();
                      if(globalSearch!="")
                      {
                          r=false;
                          this.fields.map((f)=>{
                              if(item[f] != null)
                              {
                                let field = item[f];
                                let def = this.definition.get(f);

                                if(def.type!="map")
                                {
                                  if(field.toString().toUpperCase().indexOf(globalSearch)>=0){
                                      r=true;
                                  }
                                }else{
                                  let selected = def.options.toJS().filter((opt)=>{
                                        if(opt.id == field)
                                        {
                                          return true;
                                        }
                                  })[0];
                                  if(selected!==undefined)
                                  {
                                    if(selected.value.toString().toUpperCase().indexOf(globalSearch)>=0){
                                        r=true;
                                    }
                                  }
                                }


                              }

                          })
                      }
                  }else{
                            this.fields.map((f)=>{
                                if(this.advancedFilters.has(f))
                                {

                                      if(item[f] != null)
                                      if(item[f].toString().toUpperCase().indexOf(this.advancedFilters.get(f).toString().toUpperCase())<0){
                                            r=false;
                                       }
                                   }
                                })

                  }

                  return r;
              })
              if(sortby!=""){
                  result=_.sortBy(result,sortby);
              }
              if(!asc)
              {
                  result=result.reverse();
              }
              return result;
        });
        this.pageCount=computed(()=>{
              return this.data.get().length/this.params.get('items-per-page')
        });



    }

}

export default new CrudStore();
