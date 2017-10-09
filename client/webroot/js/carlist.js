var error_str = "We apologize, we have temporary problems with the service. return to us later"
var app = new Vue({
    el: '#app',
    data: {
       models : {},
       brands : {},
       colors: {},
        model : '',
        brand: '',
        color: '',
        year_from: '',
        year_to: '',
        speed_from: '',
        speed_to: '',
        engine_capacity_from: '',
        engine_capacity_to: '',
        price_from: '',
        price_to: '',
        cars: {},
        carsAll: {},
        carItem: {
            info:{},
            colors:{}
        },
        shortInfoCars: false,
        fullInfoCar: false,
        fullInfoCarRedirect: false,
        first_name: "",
        last_name: "",
        payment_method: "cash",
        err: '',
        isOrder: "",
        err_year: "",
        err_work: "",
        err_param:"",
        is_not_err_work :true,
        is_not_err_param : true,
        color_order: '',
        client_id: 1  //!!!!!!
        
    },
    created() {
        var self = this
        myAjax.get("api/cars/",
            function(dataCars){
               
                var data = JSON.parse(dataCars)
                 console.log(data.cars)
                self.cars = data.cars
                self.models = data.models
                self.brands = data.brands
                self.colors = data.colors
                //if( cars['status']== "200"){
                    self.carsAll = data.cars
                    self.shortInfoCars = true
                //}else{
                   // self.err_work = error_str
                   // self.is_not_err_work = false;
                //}
        });
    },
    methods:{
        autoInfo(car_id){
            this.clearFilter()
            this.shortInfoCars = false
            this.fullInfoCar = true
            var self = this
            myAjax.get("api/cars/"+ car_id+".json",
                function(dataCar){
                    var data = JSON.parse(dataCar)
                    
                    //if( data['sucess']== "1"){
                        self.carItem.info  = data
                        self.carItem.colors  = data['colors']
                        self.fullInfoCarRedirect = true
                    /*}else{
                        if(data['error']!=""){
                           self.err_param = data['error']
                           self.is_not_err_param = false;
                       } else{
                        self.err_work = error_str
                         self.is_not_err_work = false;
                        }*/
                       
                    //}
            });
        },
        
        ordered(car_id){
            this.shortInfoCars = false
            this.fullInfoCar = true
           //проверка авторизации
               this.err = ""
               // //id_car, color, id_user, payment_method
               var req_str = "orderInfo[id_car]="+ car_id+"&orderInfo[color]="+this.color_order+"&orderInfo[id_user]="+this.client_id+"&orderInfo[payment_method]="+this.payment_method
               var self = this;
               
               myAjax.post("api/order/",req_str,
                function(answer){
                    console.log(answer)
                    if(answer=='1') 
                        self.isOrder = "Your order is accepted"
                        self.payment_method = "cash"
                        self.color_order = ''
                        setTimeout(function () {
                           self.isOrder = ""
                        },1500);
                       
                });
            
        },

        changeSelect(){
            if(this.fullInfoCarRedirect == true)this.cars =  this.carsAll 
            if(this.year_from!='' && this.year_to=='') 
                    this.year_to = this.year_from
            this.shortInfoCars = true
            this.fullInfoCar = false
            var self = this          
            if(this.year_to == "" || this.year_from == "") this.err_year = "this parameter is required"
            else{
                this.err_year = ""
                if(this.speed_from!='' && this.speed_to=='') 
                    this.speed_to = this.speed_from
                if(this.price_from!='' && this.price_to=='') 
                    this.price_to = this.price_from
                
                 if(this.engine_capacity_from!='' && this.engine_capacity_to=='') 
                    this.engine_capacity_to = this.engine_capacity_from

                if(this.model=="")this.model='-'
                if(this.brand=="")this.brand='-'
                if(this.engine_capacity_from=="")this.engine_capacity_from='-'
                if(this.engine_capacity_to=="")this.engine_capacity_to='-'
                if(this.speed_from=="")this.speed_from='-'
                if(this.speed_to=="")this.speed_to='-'
                if(this.price_from=="")this.price_from='-'
                if(this.price_to=="")this.price_to='-'
                if(this.color=="")this.color='-'
                var req_str = "api/cars/"+this.year_from+"/"+this.year_to+"/"+this.model+"/"+this.brand
                 +"/"+this.engine_capacity_from+"/"+this.engine_capacity_to+"/"+this.speed_from+
                 "/"+this.speed_to+"/"+this.price_from+"/"+this.price_to+"/"+this.color+"/"
                 myAjax.get(req_str,
                function(dataCars){ 
                      var data = JSON.parse(dataCars)
                       //if( data['sucess']== "1"){
                         self.cars = data//['cars']
                         self.fullInfoCarRedirect = false
                      /* }else{
                        if(data['error']!=""){
                           //self.err_param = "The search has not given any results"
                           self.err_year = data['error']
                           //self.is_not_err_param = false;
                       }else{
                        self.err_work = error_str
                         self.is_not_err_work = false;
                        }*/
                       
                       //}
                    });
            }
            
        },
        
        clearFilter(){
            this.model = ''
            this.brand = ''
            this.color = ''
            this.year_from = ''
            this.year_to = ''
            this.speed_from = ''
            this.speed_to = ''
            this.engine_capacity_from = ''
            this.engine_capacity_to = ''
            this.price_from = ''
            this.price_to = '' 
            this.err_year = ''
            this.cars =  this.carsAll 
        },
        
        getOrders(client_id){
             myAjax.get("api/order/",
                function(data){
                    console.log(data)
            });
        }
    }
})
