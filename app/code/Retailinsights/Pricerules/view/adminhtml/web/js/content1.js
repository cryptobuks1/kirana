require(['jquery', 'jquery/ui',"mage/calendar"], function($) { 
    $("#fromdate_offer").datetimepicker({
    stepMinute: 5,
    dateFormat:'yy-mm-dd',
    beforeShowDay: $.datepicker.noWeekends,
    hourMin: 1,
    hourMax: 24,
           minDate: new Date(),
           onSelect: function(dateStr) 
           {         
               $("#todate_offer").datepicker("option",{ minDate: new Date(dateStr)})
           }
       });
  
    $('#todate_offer').datetimepicker({
    stepMinute: 5,
    dateFormat:'yy-mm-dd',
    //  beforeShowDay: $.datepicker.noWeekends,
    hourMin: 1,
    hourMax: 24,
        onSelect: function(dateStr) {
        toDate = new Date(dateStr);
         }
       }); 
   $(".btnfixed").click(function(event) {
   
   //$(".btnclass").click(function(event) {
          //event.preventDefault();
  
          console.log("==============");
  
          var post_id = document.getElementById('post_id').value;
  
          var buy_product = document.getElementById('buy_product').value;
          var quantity = document.getElementById('quantity').value;
          var fixed_price = document.getElementById('fixed_price').value;
          var priority = document.getElementById('priority').value;
          var status = document.getElementById('status').value;
          var name = document.getElementById('name').value;
          var store_id = document.getElementById('store_id').value;

          var priority = document.getElementById('priority').value;
          
          var fromdate_offer = document.getElementById('fromdate_offer').value;
          var todate_offer = document.getElementById('todate_offer').value;
          var customer_group = jQuery('.selectpicker').val();
        
  
          console.log(post_id);
          console.log(name);
        console.log(store_id);
          console.log(buy_product);
          console.log(quantity);
          console.log(fixed_price);
          console.log(priority);
          console.log(status);
        
          console.log(fromdate_offer);
          console.log(todate_offer);
          console.log(customer_group);
  
          
  
          if(buy_product == '' || quantity == '' || fixed_price == ''||priority == '' ||  fromdate_offer == '' || todate_offer == '' || customer_group == '')
          {
  
  
          }    
          else
          {
            if(post_id){

                var param={ 
                    post_id:post_id,
                    name:name,
                    store_id:store_id,
                    buy_product:buy_product,
                    quantity:quantity,
                    status:status,
                    priority:priority,
                  fixed_price:fixed_price,
                  priority:priority,
                
                  fromdate_offer:fromdate_offer,
                  todate_offer:todate_offer,
                  customer_group:customer_group
                  }

            }else{
                var param={ buy_product:buy_product,
                    name:name,
                    store_id:store_id,
                    quantity:quantity,
                  fixed_price:fixed_price,
                  priority:priority,
                  status:status,
                  priority:priority,
                  
                  fromdate_offer:fromdate_offer,
                  todate_offer:todate_offer,
                  customer_group:customer_group
                  }

            }
            //var orderid = this.id;
           
  
          
            //['tot_type='+tot_type+'category='+category+'seller='+seller+'volume='+volume+'uom='+uom+'commission='+commission;
            var admin_path = window.location.pathname.split('/')[1];
            //alert(admin_path);
            var customurl = window.location.origin+'/'+admin_path+'/retailinsights_pricerules/postFixed/save';
           // alert(customurl);
            console.log("customurl");
            console.log(customurl);
            $.ajax({
                showLoader: true,
                url: customurl,
                data: param,
                type: "POST",
                dataType: 'json',
                complete:function(response){
                  console.log("++++++++");
                  console.log(response);
              //  location.reload();
                  //var redirecturl = window.location.origin +'/hnb/'+admin_path+ '/retailinsights_pricerules/Post/index';
  
                  //window.location.href=redirecturl;
                  console.log("redirecturl------");
                  console.log(redirecturl);
                },
                error:function(xhr,status,errorThrown){
                    
                }
            });
          } 
  
          //return false;
  
      });
  
  
  /*
   $(".editclass").click(function() {
    console.log("==============");
        var id=document.getElementById('id').value;
        var tot_type=document.getElementById('tot_type').value;
        var category=document.getElementById('category').value;
        var seller=document.getElementById('seller').value;
        var volume=document.getElementById('volume').value;
        var uom=document.getElementById('uom').value;
        var commission=document.getElementById('commission').value;
  console.log(tot_type);
  
  console.log(category);
  console.log(seller);
  console.log(volume);
  console.log(uom);
  console.log(commission);
  
  
      var orderid = this.id;
      var param={ id:id,
            tot_type:tot_type,
            category:category,
            seller:seller,
            volume:volume,
            uom:uom,
            commission:commission
          }
  
  
      //['tot_type='+tot_type+'category='+category+'seller='+seller+'volume='+volume+'uom='+uom+'commission='+commission;
      var admin_path = window.location.pathname.split('/')[1];
  
      var customurl = window.location.origin +'/'+admin_path+ '/contracts/index/updateinfo/';
          $.ajax({
              showLoader: true,
              url: customurl,
              data: param,
              type: "POST",
              dataType: 'json',
              complete:function(response){
                console.log("++++++++");
                console.log(response);
            //  location.reload();
                var redirecturl = window.location.origin +'/'+admin_path+ '/contracts/index/index/';
  
                window.location.href=redirecturl;
              },
              error:function(xhr,status,errorThrown){
  
              }
          });
          });*/
  
  });