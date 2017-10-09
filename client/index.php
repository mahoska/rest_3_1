<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Cars shop</title>

    <!-- Bootstrap -->
    <link href="webroot/css/bootstrap.min.css" rel="stylesheet">
    <link href="webroot/css/bootstrap.min.css.map" rel="stylesheet">
    <link href="webroot/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="webroot/css/bootstrap-theme.min.css.map" rel="stylesheet">
    <link href="webroot/css/style.css" rel="stylesheet">
    
    <script src="https://unpkg.com/vue"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
        <div class="container centr">
            <h3>Cars shop</h3>
            <div id="app">
                <p class="bg-danger" id="err">{{err_work}}</p>
                <div class="row" v-if="is_not_err_work">                   
                    <div class="col-sm-4 col-md-4">
                <div class="filter_selection  filter-form">
                    <div class="form-group">
                        <label>Year (obligatory):</label>
                        <div class="row">
                             <p class="bg-danger" id="err">{{err_year}}</p>
                            <div class="col-sm-6 col-md-6">
                            <input type="text" class="form-control"placeholder="from"  v-model.trim="year_from">   
                            </div> 
                            <div class="col-sm-6 col-md-6">
                                <input type="text" class="form-control" placeholder="to" v-model.trim="year_to" @blur="changeSelect()">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <select id="brands" class="form-control"  v-model="brand" @change="changeSelect()">
                            <option value="">brands</option>
                            <option v-for="brand in brands" :value="brand.id">
                                {{brand.name}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select id="models" class="form-control"  v-model="model" @change="changeSelect()">
                            <option value="">models</option>
                            <option v-for="model in models" :value="model.id">
                                {{model.name}}
                            </option>
                        </select>
                    </div>
  
                    <div class="form-group">
                        <label>Engine capacity:</label>
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                            <input type="text" class="form-control"placeholder="from"  v-model.trim="engine_capacity_from">   
                            </div> 
                            <div class="col-sm-6 col-md-6">
                                <input type="text" class="form-control" placeholder="to" v-model.trim="engine_capacity_to" @blur="changeSelect()">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Speed:</label>
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                            <input type="text" class="form-control"placeholder="from" v-model.trim="speed_from">   
                            </div> 
                            <div class="col-sm-6 col-md-6">
                                <input type="text" class="form-control" placeholder="to" v-model.trim="speed_to" @blur="changeSelect()">
                            </div>
                        </div>
                    </div>
                       <div class="form-group">
                        <label>Price:</label>
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                            <input type="text" class="form-control"placeholder="from" v-model.trim="price_from">   
                            </div> 
                            <div class="col-sm-6 col-md-6">
                                <input type="text" class="form-control" placeholder="to" v-model.trim="price_to" @blur="changeSelect()">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <select id="color" v-model="color" class="form-control" @change="changeSelect()">
                            <option value="">colors</option>
                            <option  v-for="color in colors" :value="color.id">
                                    {{color.name}}
                            </option>
                         </select>
                    </div> 
                     <button class="btn add_bag_btn large" @click="changeSelect()">Filter</button>
                     <button class="btn add_bag_btn large" @click="clearFilter()" style="margin-top:10px;">Clear filter</button>
               </div>
                </div>
                    <div class="col-sm-8 col-md-8">
                        <div v-if="shortInfoCars">
                        <table class="table">
                           <tr v-for="car in cars">
                                <td>
                                    <img alt="car" class="car-img"  :src="car.img" 
                                         @click="autoInfo(car.id)"/>
                                </td>
                                <td>{{ car.brand }}</td>
                                <td>{{ car.model }}</td>
                           </tr>
                        </table>
                    </div>
                        <p class="bg-danger" id="err">{{err_param}}</p>
                        <div v-if="fullInfoCar && is_not_err_param">
                        <div class="panel panel-default">
                            <!-- Default panel contents -->
                            <div class="panel-heading">
                                <figure>
                                    <figcaption><h3>{{carItem.info.name}}</h3></figcaption>
                                 <img alt="car"  :src="carItem.info.img" 
                                    style="height:200px;"/>
                                 
                                </figure>
                            </div>
                            <table class="table panel_table">
                                <tr>
                                     <td class="bold">Year of issue:</td>
                                     <td>{{carItem.info.year_of_issue}}</td>
                                </tr>
                                 <tr>
                                     <td class="bold">Max speed:</td>
                                     <td>{{carItem.info.max_speed}}</td>
                                </tr>
                                 <tr>
                                     <td class="bold">Price:</td>
                                     <td>{{carItem.info.price}} EUR</td>
                                </tr>
                                 <tr>
                                     <td class="bold">Colors:</td>
                                     <td>
                                         <ul v-for="color in carItem.colors">
                                             <li>{{color}}</li>
                                         </ul>
                                     </td>
                                </tr>
                            </table>
                        </div> 
                            <div class="order-form left ">
                            <h4>Order form</h4>
                            <p class="bg-success" id="isOrder" style="margin-top: 10px; color:blue; text-align: right;" >
                                 <b>{{isOrder}}</b>
                            </p>
                            <p class="bg-danger" id="err">{{err}}</p>
                                <div class="form-group">
                                <select id="color" v-model="color_order" class="form-control">
                                    <option value="">colors</option>
                                    <option  v-for="color in carItem.colors" :value="color">
                                            {{color}}
                                    </option>
                                 </select>
                                </div> 
                                <label>Select Payment Method</label>
                                    <div class="form-group radio_div">
                                        <div class="radio">
                                                <input type="radio" id="cash"  name="payment_method" v-model="payment_method"  value="cash"   />
                                                <label for="cash" class="small_check">Cash</label>
                                        </div>
                                        <div class="radio">
                                                <input type="radio" id="credit_card" name="payment_method" v-model="payment_method"  value="credit card">
                                                <label for="credit_card" >Credit card</label>
                                        </div>
                                    </div> 
                                <button class="btn add_bag_btn large" @click="ordered(carItem.info.id)">Create order</button>

                                <button class="btn add_bag_btn " @click="getOrders(client_id)" style="margin-top: 20px">testing Get Orders</button> 
                        </div>
                            
                            
                        </div>
                   </div>    
                </div>
            </div>
                </div>
            </div>
    <script src="webroot/js/lib_ajax.js"></script> 
    <script src="webroot/js/carlist.js"></script>  
  </body>
</html>